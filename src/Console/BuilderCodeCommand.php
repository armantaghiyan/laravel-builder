<?php

namespace Arman\LaravelBuilder\Console;

use Arman\LaravelBuilder\Helpers\FileWriter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BuilderCodeCommand extends Command {

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'builder:code';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'builder command';

	private array $ignoreCols = ['id', 'created_at', 'updated_at', 'deleted_at'];
	private array $ignoreFilters = ['created_at', 'updated_at', 'deleted_at'];
	private array $ignoreResourceModel = ['deleted_at'];
	private array $ignoreTable = ['id', 'deleted_at'];

	private array $tablesToSkip = [
		'sessions',
		'personal_access_tokens',
		'migrations',
		'jobs',
		'job_batches',
		'failed_jobs',
		'cache_locks',
		'cache',
		'password_reset_tokens',
	];

	/**
	 * All table/model name variants are created only once in boot() and
	 * all parts of the code read from this same array. No other method
	 * is allowed to call Str::studly / Str::camel / strtoupper, etc. again
	 * on the table or model name, as this was causing inconsistencies
	 * between different parts of the generated code.
	 *
	 * @var array<string,string>
	 */
	private array $names = [];

	/**
	 * name => type of columns in the selected table. Populated only once in boot().
	 *
	 * @var array<string,string>
	 */
	private array $columns = [];

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function handle(): void {
		if (config('app.env') !== 'local') {
			$this->error('Please run this command in local environment.');
			return;
		}

		$table = $this->choice('please select the table?', $this->getTables());
		$this->info('select the table: ' . $table);

		$this->boot($table);
		$this->generateCode();

		$this->info('Builder generated!');
	}

	/**
	 * Builds all name variants for the selected table once and caches its columns.
	 * From this point onward, no method should rebuild these names.
	 */
	private function boot(string $table): void {
		$singular = Str::singular($table);

		$this->names = [
			'table'      => $table,                 // e.g. user_roles -> actual table name
			'singular'   => $singular,              // e.g. user_role  -> raw singular name (snake_case)
			'model'      => Str::studly($singular), // e.g. UserRole   -> class name
			'modelCamel' => Str::camel($singular),  // e.g. userRole   -> variables, route paths, file names
			'modelSnake' => strtolower($singular),  // e.g. user_role  -> permission keys
			'modelUpper' => strtoupper($singular),  // e.g. USER_ROLE  -> constants (const)
		];

		$columns = [];
		foreach (DB::select("SHOW COLUMNS FROM $table") as $column) {
			$columns[$column->Field] = $column->Type;
		}
		$this->columns = $columns;
	}

	private function getTables(): array {
		$tables = DB::select('SHOW TABLES');
		$connection = config('database.default');
		$dbName = config("database.connections.$connection.database");

		$tableList = array_map(fn($table) => $table->{"Tables_in_{$dbName}"}, $tables);
		$tableList = array_filter($tableList, fn($table) => !in_array($table, $this->tablesToSkip));

		return array_values($tableList);
	}

	private function generateCode(): void {
		$this->createModel();
		$this->createController();

		$this->createAction('Index');
		$this->createAction('Store');
		$this->createAction('Update');
		$this->createAction('Show');
		$this->createAction('Destroy');

		$this->createRepository();
		$this->createRescueModel();

		$this->createDto('Index');
		$this->createDto('Store');
		$this->createDto('Update');

		$this->createRescueController('Index');
		$this->createRescueController('Store');
		$this->createRescueController('Update');
		$this->createRescueController('Show');
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->createInterface();
		$this->createListComposable();
		$this->createShowComposable();
		$this->createStoreUpdateComposable();
		$this->createDestroyComposable();

		$this->createVueTemplate('index');
		$this->createVueTemplate('create');
		$this->createVueTemplate('show');
		$this->createVueRoutes();
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$this->createPermissions();
	}

	//------------------------------------------------------------------------------------------------------------------
	//------------------------------------------------------ Backend ---------------------------------------------------
	//------------------------------------------------------------------------------------------------------------------

	private function createModel(): void {
		['model' => $model, 'table' => $table] = $this->names;

		$content = $this->getBackendStub('model.text');

		$cols = '';
		foreach ($this->columns as $key => $value) {
			$cols .= sprintf("const %s = '%s';\n\t", strtoupper($key), $key);
		}

		$fillable = '';
		foreach ($this->columns as $key => $value) {
			if (!in_array($key, $this->ignoreCols)) {
				$fillable .= sprintf("%s::%s,\n\t\t", $model, strtoupper($key));
			}
		}

		$content = str_replace('{cols}', $cols, $content);
		$content = str_replace('{fillable}', $fillable, $content);
		$content = str_replace('{model}', $model, $content);
		$content = str_replace('{table}', $table, $content);

		FileWriter::put(app_path("Core/Domain/{$model}/Models/{$model}.php"), $content);
	}

	private function createController(): void {
		['model' => $model, 'modelUpper' => $modelUpper] = $this->names;

		$content = $this->getBackendStub('controller.text');

		$content = str_replace('{model}', $model, $content);
		$content = str_replace('{upperModel}', $modelUpper, $content);

		FileWriter::put(app_path("Http/Controllers/Admin/{$model}Controller.php"), $content);
	}

	private function createAction(string $action): void {
		['model' => $model, 'modelCamel' => $modelCamel] = $this->names;

		$lowerAction = strtolower($action);
		$content = $this->getBackendStub("$lowerAction-action.text");

		$content = str_replace('{model}', $model, $content);
		$content = str_replace('{modelCamel}', $modelCamel, $content);

		$items = '';
		foreach ($this->columns as $key => $value) {
			if (!in_array($key, $this->ignoreCols)) {
				$items .= sprintf("%s => \$data->%s,\n\t\t\t", $this->createConst($key), $key);
			}
		}

		$content = str_replace('{items}', $items, $content);
		FileWriter::put(app_path("Core/Application/Actions/{$model}/{$model}{$action}Action.php"), $content);
	}

	private function createRepository(): void {
		['model' => $model] = $this->names;

		$content = $this->getBackendStub('repository.text');

		$filters = '';
		foreach ($this->columns as $key => $value) {
			if (!in_array($key, $this->ignoreFilters)) {
				$filters .= sprintf("%sfilter(%s, \$data->%s)\n\t\t\t", $filters === '' ? '' : '->', $this->createConst($key), $key);
			}
		}

		$content = str_replace('{filters}', $filters, $content);
		$content = str_replace('{model}', $model, $content);
		FileWriter::put(app_path("Core/Domain/{$model}/Repositories/{$model}Repository.php"), $content);
	}

	private function createDto(string $action): void {
		['model' => $model] = $this->names;

		$content = $this->getBackendStub('dto.text');

		$items = '';
		foreach ($this->columns as $key => $value) {
			if (in_array($key, $this->ignoreCols)) {
				continue;
			}

			$items .= sprintf("public $%s,\n\t\t", $key);
		}

		$content = str_replace('{indexTrait}', $action === 'Index' ? 'use WithIndexData;' : '', $content);
		$content = str_replace('{model}', $model, $content);
		$content = str_replace('{items}', $items, $content);
		$content = str_replace('{action}', $action, $content);

		FileWriter::put(app_path("Http/Data/Admin/$model/{$model}{$action}Data.php"), $content);
	}

	private function createRescueModel(): void {
		['model' => $model] = $this->names;

		$content = $this->getBackendStub('resource-model.text');

		$items = '';
		foreach ($this->columns as $key => $value) {
			if (in_array($key, $this->ignoreResourceModel)) {
				continue;
			}

			$const = $this->createConst($key);
			$items .= sprintf("%s => \$this->whenHas(%s,\$this[%s]),\n\t\t\t", $const, $const, $const);
		}

		$content = str_replace('{model}', $model, $content);
		$content = str_replace('{items}', $items, $content);

		FileWriter::put(app_path("Http/Resources/Admin/{$model}/{$model}Resource.php"), $content);
	}

	private function createRescueController(string $action): void {
		['model' => $model] = $this->names;

		$content = $action === 'Index'
			? $this->getBackendStub('resource-controller-index.text')
			: $this->getBackendStub('resource-controller-store-update.text');

		$content = str_replace('{model}', $model, $content);
		$content = str_replace('{model-action}', $model . $action, $content);

		FileWriter::put(app_path("Http/Resources/Admin/$model/{$model}{$action}Resource.php"), $content);
	}

	private function createConst(string $column): string {
		return sprintf('%s::%s', $this->names['model'], Str::upper($column));
	}

	private function getBackendStub(string $name): string {
		return file_get_contents(__DIR__ . "/../../stubs/backend/$name");
	}

	//------------------------------------------------------------------------------------------------------------------
	//------------------------------------------------------- Front ----------------------------------------------------
	//------------------------------------------------------------------------------------------------------------------

	private function createInterface(): void {
		['model' => $model, 'modelCamel' => $modelCamel] = $this->names;

		$content = $this->getFrontStub('interface.text');

		$params = '';
		foreach ($this->columns as $key => $value) {
			$params .= sprintf("%s: string,\n\t", $key);
		}

		$content = str_replace('{model}', $model, $content);
		$content = str_replace('{params}', $params, $content);

		FileWriter::put(resource_path("js/utils/models/$model.ts"), $content);

		$content = $this->getFrontStub('api.text');
		$content = str_replace('{model}', $model, $content);
		$content = str_replace('{name}', $modelCamel, $content);
		FileWriter::put(resource_path("js/utils/api/$modelCamel.ts"), $content);
	}

	private function createListComposable(): void {
		['model' => $model, 'modelCamel' => $modelCamel] = $this->names;

		$content = $this->getFrontStub('use-list.text');
		$params = $this->createSearchParams();

		$content = str_replace('{params}', $params, $content);
		$content = str_replace('{model}', $model, $content);
		$content = str_replace('{name}', $modelCamel, $content);

		FileWriter::put(resource_path("js/composables/$modelCamel/use{$model}List.ts"), $content);
	}

	private function createShowComposable(): void {
		['model' => $model, 'modelCamel' => $modelCamel] = $this->names;

		$content = $this->getFrontStub('use-show.text');

		$content = str_replace('{model}', $model, $content);
		$content = str_replace('{name}', $modelCamel, $content);

		FileWriter::put(resource_path("js/composables/$modelCamel/use{$model}Show.ts"), $content);
	}

	private function createStoreUpdateComposable(): void {
		['model' => $model, 'modelCamel' => $modelCamel] = $this->names;

		$content = $this->getFrontStub('use-store-update.text');
		$params = $this->createUpdateStoreParams();

		$content = str_replace('{params}', $params, $content);
		$content = str_replace('{model}', $model, $content);
		$content = str_replace('{name}', $modelCamel, $content);

		FileWriter::put(resource_path("js/composables/$modelCamel/use{$model}StoreUpdate.ts"), $content);
	}

	private function createDestroyComposable(): void {
		['model' => $model, 'modelCamel' => $modelCamel] = $this->names;

		$content = $this->getFrontStub('use-destroy.text');

		$content = str_replace('{model}', $model, $content);
		$content = str_replace('{name}', $modelCamel, $content);

		FileWriter::put(resource_path("js/composables/$modelCamel/use{$model}Destroy.ts"), $content);
	}

	private function createVueTemplate(string $template): void {
		['model' => $model, 'modelCamel' => $modelCamel, 'modelUpper' => $modelUpper, 'singular' => $singular] = $this->names;

		$content = $this->getFrontStub("$template.text");

		$content = str_replace('{model}', $model, $content);
		$content = str_replace('{name}', $modelCamel, $content);

		$filters = '';
		foreach ($this->columns as $key => $value) {
			if (!in_array($key, $this->ignoreFilters)) {
				$filters .= "<text-input :title=\"t('global.{$key}')\" v-model=\"params.{$key}\"/>\n\t\t\t\t\t\t";
			}
		}
		$content = str_replace('{filter}', $filters, $content);

		$thead = '';
		$tbody = '';
		foreach ($this->columns as $key => $value) {
			if (!in_array($key, $this->ignoreTable)) {
				$thead .= "<custom-th sort-key=\"{$key}\" v-model:sort=\"params.sort\" v-model:sort-type=\"params.sort_type\">{{ t('global.{$key}') }}</custom-th>\n\t\t\t\t\t\t";
				$tbody .= "<custom-td>{{ item.{$key} }}</custom-td>\n\t\t\t\t\t";
			}
		}

		$content = str_replace('{thead}', $thead, $content);
		$content = str_replace('{tbody}', $tbody, $content);
		$content = str_replace('{upperModel}', $modelUpper, $content);
		$content = str_replace('{name2}', $singular, $content);

		$updateStoreParams = '';
		$inputItems = '';
		foreach ($this->columns as $key => $value) {
			if (!in_array($key, $this->ignoreCols)) {
				$updateStoreParams .= "storeAndUpdateParams.$key = $modelCamel.{$key};";
				$inputItems .= "<text-input id=\"{$key}\" :title=\"t('global.{$key}')\" v-model=\"storeAndUpdateParams.{$key}\"/>\n\t\t\t\t";
			}
		}

		$labelsList = '';
		foreach ($this->columns as $key => $value) {
			if (!in_array($key, $this->ignoreResourceModel)) {
				$labelsList .= "<label-item :title=\"t('global.{$key}')\">{{item?.{$key}}}</label-item>\n\t\t\t";
			}
		}

		$content = str_replace('{updateStoreParams}', $updateStoreParams, $content);
		$content = str_replace('{inputItems}', $inputItems, $content);
		$content = str_replace('{labels-list}', $labelsList, $content);

		FileWriter::put(resource_path("js/pages/$modelCamel/$template.vue"), $content);
	}

	private function createVueRoutes(): void {
		['model' => $model, 'modelCamel' => $modelCamel] = $this->names;

		$content = file_get_contents(resource_path('js/router.ts'));

		if (str_contains($content, $model)) {
			return;
		}

		$content = sprintf("import %sIndexPage from '@/pages/%s/index.vue';\n\n", $model, $modelCamel) . $content;
		$content = sprintf("import %sCreatePage from '@/pages/%s/create.vue';\n", $model, $modelCamel) . $content;
		$content = sprintf("import %sShowPage from '@/pages/%s/show.vue';\n", $model, $modelCamel) . $content;

		$routes = '';
		$routes .= "\n\t{path: '/{$modelCamel}', name: '{$model}IndexPage', component: {$model}IndexPage},\n";
		$routes .= "\t{path: '/{$modelCamel}/create/:id?', name: '{$model}CreatePage', component: {$model}CreatePage},\n";
		$routes .= "\t{path: '/{$modelCamel}/:id', name: '{$model}ShowPage', component: {$model}ShowPage},\n];";

		$content = str_replace('];', $routes, $content);

		FileWriter::put(resource_path('js/router.ts'), $content);
	}

	private function getFrontStub(string $name): string {
		return file_get_contents(__DIR__ . "/../../stubs/front/$name");
	}

	private function createSearchParams(): string {
		$params = '';

		foreach ($this->columns as $key => $value) {
			if (!in_array($key, $this->ignoreResourceModel)) {
				$params .= sprintf("%s: '',\n\t\t", $key);
			}
		}

		return $params;
	}

	private function createUpdateStoreParams(): string {
		$params = '';

		foreach ($this->columns as $key => $value) {
			if (!in_array($key, $this->ignoreCols)) {
				$params .= sprintf("%s: '',\n\t\t", $key);
			}
		}

		return $params;
	}

	//------------------------------------------------------------------------------------------------------------------
	//---------------------------------------------------- Permissions -------------------------------------------------
	//------------------------------------------------------------------------------------------------------------------

	private function createPermissions(): void {
		['modelSnake' => $modelSnake, 'modelUpper' => $modelUpper] = $this->names;

		$content = file_get_contents(app_path('Http/Constants/Permissions.php'));

		if (!str_contains($content, "const {$modelUpper}_INDEX = '{$modelSnake}.index';")) {
			$perms = "Permissions {\n
    const {$modelUpper}_INDEX = '{$modelSnake}.index';
    const {$modelUpper}_UPDATE = '{$modelSnake}.update';
    const {$modelUpper}_STORE = '{$modelSnake}.store';
    const {$modelUpper}_DESTROY = '{$modelSnake}.destroy';\n
";

			$content = str_replace('Permissions {', $perms, $content);
			FileWriter::put(app_path('Http/Constants/Permissions.php'), $content);
		}

		$content = file_get_contents(resource_path('js/utils/models/enums.ts'));

		if (!str_contains($content, "{$modelUpper}_INDEX = '{$modelSnake}.index',")) {
			$permsFront = "Permissions { \n
    {$modelUpper}_INDEX = '{$modelSnake}.index',
    {$modelUpper}_UPDATE = '{$modelSnake}.update',
    {$modelUpper}_STORE = '{$modelSnake}.store',
    {$modelUpper}_DESTROY = '{$modelSnake}.destroy',\n
";

			$content = str_replace('Permissions {', $permsFront, $content);

			FileWriter::put(resource_path('js/utils/models/enums.ts'), $content);
		}
	}
}