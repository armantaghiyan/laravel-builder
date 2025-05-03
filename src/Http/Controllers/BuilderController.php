<?php

namespace Arman\LaravelBuilder\Http\Controllers;

use Arman\LaravelBuilder\Http\Helpers\FileWriter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BuilderController {

	private $ignoreCols = ['id', 'created_at', 'updated_at', 'deleted_at'];
	private $ignoreFilters = ['created_at', 'updated_at', 'deleted_at'];
	private $ignoreResourceModel = ['deleted_at'];


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

		$this->createController($model, $columnInfo);

		$this->createService($model, $columnInfo);
		$this->createQueryService($model, $columnInfo);
		$this->createCommandService($model, $columnInfo);

		$this->createRescueModel($model, $columnInfo);

		$this->createDto($model, 'Index', $columnInfo);
		$this->createDto($model, 'Store', $columnInfo);
		$this->createDto($model, 'Update', $columnInfo);

		$this->createRescueController($model, 'Index', $columnInfo);
		$this->createRescueController($model, 'Store', $columnInfo);
		$this->createRescueController($model, 'Update', $columnInfo);
		$this->createRescueController($model, 'Show', $columnInfo);
	}

	public function createController($model, $columnInfo) {
		$content = $this->getStub('controller.text');

		$content = str_replace('{model}', $model, $content);
		$content = str_replace('{sumModel}', strtolower($model), $content);


		FileWriter::put(app_path("Http/Controllers/{$model}Controller.php"), $content);
	}

	public function createService($model, $columnInfo) {
		$content = $this->getStub('service.text');

		$content = str_replace('{sumModel}', strtolower($model), $content);
		$content = str_replace('{model}', $model, $content);

		FileWriter::put(app_path("Services/Domain/{$model}Service.php"), $content);
	}

	public function createQueryService($model, $columnInfo) {
		$content = $this->getStub('query-service.text');

		$items = "";
		$filters = "";
		foreach ($columnInfo as $key => $value) {

			if (!in_array($key, $this->ignoreFilters)) {
				$symbol = '->';
				if($key === 'id'){
					$symbol = '::';
				}
				$filters .= sprintf("%sfilter(%s, %sdata->%s)\n\t\t\t", $symbol,$this->createConst($model,$key), '$', $key);
			}
			if (!in_array($key, $this->ignoreCols)) {
				$items .= sprintf("%sitem[%s] = %sdata->%s;\n\t\t", '$', $this->createConst($model,$key), '$' ,$key);
			}
		}

		$content = str_replace('{id}', $this->createConst($model,'id'), $content);
		$content = str_replace('{filters}', $filters, $content);
		$content = str_replace('{items}', $items, $content);
		$content = str_replace('{model}', $model, $content);

		FileWriter::put(app_path("Services/Domain/{$model}QueryService.php"), $content);
	}

	public function createCommandService($model, $columnInfo) {
		$content = $this->getStub('query-command-service.text');

		$items = "";
		foreach ($columnInfo as $key => $value) {
			if (!in_array($key, $this->ignoreCols)) {
				$items .= sprintf("%sitem[%s] = %sdata->%s;\n\t\t", '$', $this->createConst($model,$key), '$' ,$key);
			}
		}

		$content = str_replace('{id}', $this->createConst($model,'id'), $content);
		$content = str_replace('{items}', $items, $content);
		$content = str_replace('{model}', $model, $content);

		FileWriter::put(app_path("Services/Domain/{$model}CommandService.php"), $content);
	}

	public function createDto($model, $action, $columnInfo) {
		$content = $this->getStub('dto.text');

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

		FileWriter::put(app_path("Dto/$model/{$model}{$action}Data.php"), $content);
	}

	public function createRescueModel($model, $columnInfo) {
		$content = $this->getStub('resource-model.text');

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

		FileWriter::put(app_path("Http/Resources/Models/{$model}Resource.php"), $content);
	}

	public function createRescueController($model, $action, $columnInfo) {
		$content = $this->getStub('resource-controller-store-update.text');

		$content = str_replace('{model}', $model, $content);
		$content = str_replace('{model-action}', $model . $action, $content);

		FileWriter::put(app_path("Http/Resources/{$model}{$action}Resource.php"), $content);
	}

	private function createConst($model, $column) {
		if($column == 'created_at' || $column == 'updated_at' || $column == 'deleted_at') {
			return 'COL_' . Str::upper($column);
		}

		return 'COL_' . Str::upper($model) . '_' . Str::upper($column);
	}

	private function getStub($name) {
		$content = file_get_contents(__DIR__ . "../../../../stubs/$name");

		return $content;
	}
}