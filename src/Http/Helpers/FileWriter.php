<?php

namespace Arman\LaravelBuilder\Http\Helpers;

class FileWriter {

	public static function put(string $path, string $content, int $permissions = 0755): bool {
		$directory = dirname($path);

		if (!is_dir($directory)) {
			mkdir($directory, $permissions, true);
		}

		return file_put_contents($path, $content) !== false;
	}
}