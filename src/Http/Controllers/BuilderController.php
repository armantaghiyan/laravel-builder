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
		$this->createRepository($model, $columnInfo);

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


		FileWriter::put(app_path("Services/{$model}/Controllers/{$model}Controller.php"), $content);
	}

	public function createService($model, $columnInfo) {
		$content = $this->getStub('service.text');

		$content = str_replace('{model}', $model, $content);
		$content = str_replace('{sumModel}', strtolower($model), $content);

		$items = "";
		foreach ($columnInfo as $key => $value) {
			if (!in_array($key, $this->ignoreCols)) {
				$items .= sprintf("%s => \$data->%s,\n\t\t\t", $this->createConst($model, $key), $key);

			}
		}

		$content = str_replace('{items}', $items, $content);

		$content = str_replace('{model}', $model, $content);
		FileWriter::put(app_path("Services/{$model}/Services/{$model}Service.php"), $content);
	}

	public function createRepository($model, $columnInfo) {
		$content = $this->getStub('repository.text');

		$filters = "";
		foreach ($columnInfo as $key => $value) {
			if (!in_array($key, $this->ignoreFilters)) {
				$filters .= sprintf("\$query->filter(%s, \$data->%s);\n\t\t", $this->createConst($model, $key), $key);
			}
		}

		$content = str_replace('{filters}', $filters, $content);
		$content = str_replace('{model}', $model, $content);
		FileWriter::put(app_path("Services/{$model}/Repositories/{$model}Repository.php"), $content);
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

		FileWriter::put(app_path("Services/$model/Dto/{$model}{$action}Data.php"), $content);
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

		FileWriter::put(app_path("Services/{$model}/Resources/{$model}Resource.php"), $content);
	}

	public function createRescueController($model, $action, $columnInfo) {
		if($action === 'Index'){
			$content = $this->getStub('resource-controller-index.text');
		}else{
			$content = $this->getStub('resource-controller-store-update.text');
		}

		$content = str_replace('{model}', $model, $content);
		$content = str_replace('{model-action}', $model . $action, $content);

		FileWriter::put(app_path("Services/$model/Resources/{$model}{$action}Resource.php"), $content);
	}

	private function createConst($model, $column) {
		return sprintf("%s::%s", $model, Str::upper($column));
	}

	private function getStub($name) {
		$content = file_get_contents(__DIR__ . "../../../../stubs/$name");

		return $content;
	}
}