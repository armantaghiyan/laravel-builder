<?php

namespace Arman\LaravelBuilder\Http\Controllers;

use Arman\LaravelBuilder\Http\Helpers\FileWriter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BuilderController {

	private $ignoreCols = ['id', 'created_at', 'updated_at', 'deleted_at'];
	private $ignoreFilters = ['created_at', 'updated_at', 'deleted_at'];
	private $ignoreResourceModel = ['deleted_at'];
	private $ignoreTable = ['id', 'deleted_at'];


	public function getTables() {
		$tables = DB::select('SHOW TABLES');
		$connection = config('database.default');
		$dbName = config("database.connections.$connection.database");

		$tableList = array_map(function ($table) use ($dbName) {
			return $table->{"Tables_in_{$dbName}"};
		}, $tables);


		$tableList = array_filter($tableList, function ($table) {
			return !in_array($table, [
				'sessions',
				'personal_access_tokens',
				'migrations',
				'jobs',
				'job_batches',
				'failed_jobs',
				'cache_locks',
				'cache',
				'password_reset_tokens',
			]);
		});

		return array_values($tableList);
	}

	public function generateCode($table) {
		$columnInfo = [];
		foreach (DB::select("SHOW COLUMNS FROM $table") as $column) {
			$columnInfo[$column->Field] = $column->Type;
		}

		$model = Str::studly(Str::singular($table));

		$this->createModel($model, $columnInfo, $table);
		$this->createController($model, $columnInfo, $table);

		$this->createAction($model, 'Index', $columnInfo);
		$this->createAction($model, 'Store', $columnInfo);
		$this->createAction($model, 'Update', $columnInfo);
		$this->createAction($model, 'Show', $columnInfo);
		$this->createAction($model, 'Destroy', $columnInfo);

		$this->createRepository($model, $columnInfo);
		$this->createRescueModel($model, $columnInfo);

		$this->createDto($model, 'Index', $columnInfo);
		$this->createDto($model, 'Store', $columnInfo);
		$this->createDto($model, 'Update', $columnInfo);

		$this->createRescueController($model, 'Index', $columnInfo);
		$this->createRescueController($model, 'Store', $columnInfo);
		$this->createRescueController($model, 'Update', $columnInfo);
		$this->createRescueController($model, 'Show', $columnInfo);
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->createInterface($model, $columnInfo, $table);
		$this->createListComposable($model, $columnInfo, $table);
		$this->createShowComposable($model, $columnInfo, $table);
		$this->createStoreUpdateComposable($model, $columnInfo, $table);
		$this->createDestroyComposable($model, $columnInfo, $table);

		$this->createVueTemplate($model, 'index', $columnInfo, $table);
		$this->createVueTemplate($model, 'create', $columnInfo, $table);
		$this->createVueTemplate($model, 'show', $columnInfo, $table);
		$this->createVueRoutes($model, $table);
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$this->createPermissions($model, $table);
	}

	public function createModel($model, $columnInfo, $table) {
		$content = $this->getBackendStub('model.text');

		$cols = "";
		foreach ($columnInfo as $key => $value) {
			$cols .= sprintf("const %s = '%s';\n\t", strtoupper($key), $key);
		}

		$fillable = "";
		foreach ($columnInfo as $key => $value) {
			if (!in_array($key, $this->ignoreCols)) {
				$fillable .= sprintf("%s::%s,\n\t\t", $model, strtoupper($key));
			}
		}

		$content = str_replace('{cols}', $cols, $content);
		$content = str_replace('{fillable}', $fillable, $content);
		$content = str_replace('{model}', $model, $content);
		$content = str_replace('{sumModel}', $table, $content);

		FileWriter::put(app_path("Core/Domain/{$model}/Models/{$model}.php"), $content);
	}

	public function createController($model, $columnInfo, $table) {
		$content = $this->getBackendStub('controller.text');

		$content = str_replace('{model}', $model, $content);
		$content = str_replace('{sumModel}', strtolower($model), $content);
		$content = str_replace('{upperModel}', strtoupper(Str::singular($table)), $content);


		FileWriter::put(app_path("Http/Controllers/Admin/{$model}Controller.php"), $content);
	}

	public function createAction($model, $action, $columnInfo) {
		$lowerAction = strtolower($action);
		$content = $this->getBackendStub("$lowerAction-action.text");

		$content = str_replace('{model}', $model, $content);
		$content = str_replace('{sumModel}', lcfirst($model), $content);

		$items = "";
		foreach ($columnInfo as $key => $value) {
			if (!in_array($key, $this->ignoreCols)) {
				$items .= sprintf("%s => \$data->%s,\n\t\t\t", $this->createConst($model, $key), $key);
			}
		}

		$content = str_replace('{items}', $items, $content);
		FileWriter::put(app_path("Core/Application/Actions/{$model}/{$model}{$action}Action.php"), $content);
	}

	public function createRepository($model, $columnInfo) {
		$content = $this->getBackendStub('repository.text');

		$filters = "";
		foreach ($columnInfo as $key => $value) {
			if (!in_array($key, $this->ignoreFilters)) {
				$filters .= sprintf("%sfilter(%s, \$data->%s)\n\t\t\t", $filters === ""? '':'->' , $this->createConst($model, $key), $key);
			}
		}

		$content = str_replace('{filters}', $filters, $content);
		$content = str_replace('{model}', $model, $content);
		FileWriter::put(app_path("Core/Domain/{$model}/Repositories/{$model}Repository.php"), $content);
	}

	public function createDto($model, $action, $columnInfo) {
		$content = $this->getBackendStub('dto.text');

		$items = "";
		foreach ($columnInfo as $key => $value) {
			if (in_array($key, $this->ignoreCols)) {
				continue;
			}

			$items .= sprintf("public $%s,\n\t\t", $key);
		}

		if($action === 'Index'){
			$content = str_replace('{indexTrait}', 'use WithIndexData;', $content);
		}else{
			$content = str_replace('{indexTrait}', '', $content);
		}

		$content = str_replace('{model}', $model, $content);
		$content = str_replace('{items}', $items, $content);
		$content = str_replace('{action}', $action, $content);

		FileWriter::put(app_path("Http/Data/Admin/$model/{$model}{$action}Data.php"), $content);
	}

	public function createRescueModel($model, $columnInfo) {
		$content = $this->getBackendStub('resource-model.text');

		$items = "";
		foreach ($columnInfo as $key => $value) {
			if (in_array($key, $this->ignoreResourceModel)) {
				continue;
			}

			$const =  $this->createConst($model, $key);
			$items .= sprintf("%s => %sthis->whenHas(%s,%sthis[%s]),\n\t\t\t", $const, '$' , $const, '$', $const);
		}

		$content = str_replace('{model}', $model, $content);
		$content = str_replace('{items}', $items, $content);

		FileWriter::put(app_path("Http/Resources/Admin/{$model}/{$model}Resource.php"), $content);
	}

	public function createRescueController($model, $action, $columnInfo) {
		if($action === 'Index'){
			$content = $this->getBackendStub('resource-controller-index.text');
		}else{
			$content = $this->getBackendStub('resource-controller-store-update.text');
		}

		$content = str_replace('{model}', $model, $content);
		$content = str_replace('{model-action}', $model . $action, $content);

		FileWriter::put(app_path("Http/Resources/Admin/$model/{$model}{$action}Resource.php"), $content);
	}

	private function createConst($model, $column) {
		return sprintf("%s::%s", $model, Str::upper($column));
	}

	private function getBackendStub($name) {
		$content = file_get_contents(__DIR__ . "../../../../stubs/backend/$name");

		return $content;
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function createInterface($model, $columnInfo, $table) {
		$lowerModel = Str::camel(Str::singular($table));

		$content = $this->getFrontStub('interface.text');

		$params = '';
		foreach ($columnInfo as $key => $value) {
			$params .= sprintf("%s: string,\n\t", $key);
		}

		$content = str_replace('{model}', $model, $content);
		$content = str_replace('{params}', $params, $content);


		FileWriter::put(resource_path("js/utils/models/$model.ts"), $content);

		$content = $this->getFrontStub('api.text');
		$content = str_replace('{model}', $model, $content);
		$content = str_replace('{name}', $lowerModel, $content);
		FileWriter::put(resource_path("js/utils/api/$lowerModel.ts"), $content);
	}

	public function createListComposable($model, $columnInfo, $table) {
		$content = $this->getFrontStub('use-list.text');

		$lowerModel = Str::camel(Str::singular($table));
		$params = $this->createSearchParams($columnInfo);

		$content = str_replace('{params}', $params, $content);
		$content = str_replace('{model}', $model, $content);
		$content = str_replace('{name}', $lowerModel, $content);

		FileWriter::put(resource_path("js/composables/$lowerModel/use{$model}List.ts"), $content);
	}

	public function createShowComposable($model, $columnInfo, $table) {
		$content = $this->getFrontStub('use-show.text');

		$lowerModel = Str::camel(Str::singular($table));

		$content = str_replace('{model}', $model, $content);
		$content = str_replace('{name}', $lowerModel, $content);

		FileWriter::put(resource_path("js/composables/$lowerModel/use{$model}Show.ts"), $content);
	}

	public function createStoreUpdateComposable($model, $columnInfo, $table) {
		$content = $this->getFrontStub('use-store-update.text');

		$lowerModel = Str::camel(Str::singular($table));
		$params = $this->createUpdateStoreParams($columnInfo);

		$content = str_replace('{params}', $params, $content);
		$content = str_replace('{model}', $model, $content);
		$content = str_replace('{name}', $lowerModel, $content);

		FileWriter::put(resource_path("js/composables/$lowerModel/use{$model}StoreUpdate.ts"), $content);
	}

	public function createDestroyComposable($model, $columnInfo, $table) {
		$content = $this->getFrontStub('use-destroy.text');

		$lowerModel = Str::camel(Str::singular($table));

		$content = str_replace('{model}', $model, $content);
		$content = str_replace('{name}', $lowerModel, $content);

		FileWriter::put(resource_path("js/composables/$lowerModel/use{$model}Destroy.ts"), $content);
	}

	public function createVueTemplate($model, $template,$columnInfo, $table) {
		$content = $this->getFrontStub("$template.text");

		$lowerModel = Str::camel(Str::singular($table));

		$content = str_replace('{model}', $model, $content);
		$content = str_replace('{name}', $lowerModel, $content);


		$filters = "";
		foreach ($columnInfo as $key => $value) {
			if (!in_array($key, $this->ignoreFilters)) {
				$filters .= "<text-input :title=\"t('global.{$key}')\" v-model=\"params.{$key}\"/>\n\t\t\t\t\t\t";
			}
		}
		$content = str_replace('{filter}', $filters, $content);

		$thead = "";
		$tbody = "";
		foreach ($columnInfo as $key => $value) {
			if (!in_array($key, $this->ignoreTable)) {
				$thead .= "<custom-th sort-key=\"{$key}\" v-model:sort=\"params.sort\" v-model:sort-type=\"params.sort_type\">{{ t('global.{$key}') }}</custom-th>\n\t\t\t\t\t\t";
				$tbody .= "<custom-td>{{ item.{$key} }}</custom-td>\n\t\t\t\t\t";
			}
		}

		$content = str_replace('{thead}', $thead, $content);
		$content = str_replace('{tbody}', $tbody, $content);
		$content = str_replace('{upperModel}', strtoupper(Str::singular($table)), $content);
		$content = str_replace('{name2}', Str::singular($table), $content);


		$updateStoreParams = "";
		$inputItems = "";
		foreach ($columnInfo as $key => $value) {
			if (!in_array($key, $this->ignoreCols)) {
				$updateStoreParams .= "storeAndUpdateParams.$key = $lowerModel.{$key};";
				$inputItems .= "<text-input id=\"{$key}\" :title=\"t('global.{$key}')\" v-model=\"storeAndUpdateParams.{$key}\"/>";
			}
		}

		$labelsList = '';
		foreach ($columnInfo as $key => $value) {
			if (!in_array($key, $this->ignoreResourceModel)) {
				$labelsList .= "<label-item :title=\"t('global.{$key}')\">{{item?.{$key}}}</label-item>\n\t\t\t";
			}
		}

		$content = str_replace('{updateStoreParams}', $updateStoreParams, $content);
		$content = str_replace('{inputItems}', $inputItems, $content);
		$content = str_replace('{labels-list}', $labelsList, $content);


		FileWriter::put(resource_path("js/pages/$lowerModel/$template.vue"), $content);
	}

	public function createVueRoutes($model, $table) {
		$content = file_get_contents(resource_path('js/router.ts'));

		if (str_contains($content, $model)) {
			return;
		}

		$lowerModel = Str::camel(Str::singular($table));


		$content = sprintf("import %sIndexPage from '@/pages/%s/index.vue';\n\n", $model, $lowerModel) . $content;
		$content = sprintf("import %sCreatePage from '@/pages/%s/create.vue';\n", $model, $lowerModel) . $content;
		$content = sprintf("import %sShowPage from '@/pages/%s/show.vue';\n", $model, $lowerModel) . $content;

		$routes = '';
		$routes .= "\n\t{path: '/{$lowerModel}', name: '{$model}IndexPage', component: {$model}IndexPage},\n";
		$routes .= "\t{path: '/{$lowerModel}/create/:id?', name: '{$model}CreatePage', component: {$model}CreatePage},\n";
		$routes .= "\t{path: '/{$lowerModel}/:id', name: '{$model}ShowPage', component: {$model}ShowPage},\n];";

		$content = str_replace('];', $routes, $content);

		FileWriter::put(resource_path("js/router.ts"), $content);
	}

	private function getFrontStub($name) {
		$content = file_get_contents(__DIR__ . "../../../../stubs/front/$name");

		return $content;
	}

	private function createSearchParams($columnInfo) {
		$params = '';

		foreach ($columnInfo as $key => $value) {
			if (!in_array($key, $this->ignoreResourceModel)) {
				$params .= sprintf("%s: '',\n\t\t", $key);
			}
		}

		return $params;
	}

	private function createUpdateStoreParams($columnInfo) {
		$params = '';

		foreach ($columnInfo as $key => $value) {
			if (!in_array($key, $this->ignoreCols)) {
				$params .= sprintf("%s: '',\n\t\t", $key);
			}
		}

		return $params;
	}


	private function createPermissions($model, $table) {
		$content = file_get_contents(app_path('Http/Constants/Permissions.php'));

		$lowerModel = strtolower(Str::singular($table));
		$upperModel = strtoupper(Str::singular($table));

		$perms = "Permissions {\n
	const {$upperModel}_INDEX = '{$lowerModel}.index';
	const {$upperModel}_UPDATE = '{$lowerModel}.update';
	const {$upperModel}_STORE = '{$lowerModel}.store';
	const {$upperModel}_DESTROY = '{$lowerModel}.destroy';\n
";

		$content = str_replace('Permissions {', $perms, $content);
		FileWriter::put(app_path("Http/Constants/Permissions.php"), $content);


		$content = file_get_contents(resource_path('js/utils/models/enums.ts'));

		$permsFront = "Permissions { \n
	{$upperModel}_INDEX = '{$lowerModel}.index',
    {$upperModel}_UPDATE = '{$lowerModel}.update',
    {$upperModel}_STORE = '{$lowerModel}.store',
    {$upperModel}_DESTROY = '{$lowerModel}.destroy',\n
";

		$content = str_replace('Permissions {', $permsFront, $content);

		FileWriter::put(resource_path('js/utils/models/enums.ts'), $content);
	}
}
