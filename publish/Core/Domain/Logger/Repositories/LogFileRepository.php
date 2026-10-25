<?php

namespace App\Core\Domain\Logger\Repositories;

use App\Core\Infrastructure\Exceptions\ErrorMessageException;
use DirectoryIterator;

class LogFileRepository {

    private const PREVIEW_BYTES = 2 * 1024 * 1024;

    public function index(string $path): array {
        if ($path === '' && !is_dir(storage_path('logs'))) {
            return [];
        }

        $directory = $this->resolve($path);
        $this->ensure(is_dir($directory));
        $items = [];

        foreach (new DirectoryIterator($directory) as $entry) {
            $name = $entry->getFilename();

            if (str_starts_with($name, '.') || $entry->isLink() || !$entry->isReadable()) {
                continue;
            }

            $isDirectory = $entry->isDir();

            if (!$isDirectory && (!$entry->isFile() || !$this->isLogFile($name))) {
                continue;
            }

            $items[] = [
                'name' => $name,
                'path' => ltrim($path . '/' . $name, '/'),
                'is_directory' => $isDirectory,
                'size' => $isDirectory ? null : $entry->getSize(),
                'modified_at' => $entry->getMTime(),
            ];
        }

        usort($items, function (array $first, array $second): int {
            return ($second['is_directory'] <=> $first['is_directory'])
                ?: (!$first['is_directory'] ? $second['modified_at'] <=> $first['modified_at'] : 0)
                    ?: strnatcasecmp($first['name'], $second['name']);
        });

        return $items;
    }

    public function show(string $path, int $lines): array {
        $file = $this->file($path);
        $stream = @fopen($file, 'rb');
        $this->ensure($stream !== false);

        try {
            $stat = fstat($stream);
            $position = $stat['size'];
            $buffer = '';
            $newlines = 0;

            // Read backwards in small chunks, with a byte limit for unusually long lines.
            while ($position > 0 && $newlines <= $lines && strlen($buffer) < self::PREVIEW_BYTES) {
                $length = min(8192, $position, self::PREVIEW_BYTES - strlen($buffer));
                $position -= $length;
                $this->ensure(fseek($stream, $position) === 0);
                $chunk = fread($stream, $length);
                $this->ensure($chunk !== false);
                $buffer = $chunk . $buffer;
                $newlines += substr_count($chunk, "\n");
            }
        } finally {
            fclose($stream);
        }

        $buffer = str_replace("\r\n", "\n", $buffer);

        if (str_ends_with($buffer, "\n")) {
            $buffer = substr($buffer, 0, -1);
        }

        $rows = $stat['size'] === 0 ? [] : explode("\n", $buffer);
        $truncated = $position > 0 && count($rows) <= $lines;
        $rows = array_slice($rows, -$lines);

        return [
            'name' => basename($file),
            'path' => $path,
            'size' => $stat['size'],
            'modified_at' => $stat['mtime'],
            'content' => mb_convert_encoding(implode("\n", $rows), 'UTF-8', 'UTF-8'),
            'line_count' => count($rows),
            'requested_lines' => $lines,
            'truncated' => $truncated,
        ];
    }

    public function file(string $path): string {
        $file = $this->resolve($path);
        $this->ensure(is_file($file) && $this->isLogFile(basename($file)));

        return $file;
    }

    private function resolve(string $path): string {
        $root = realpath(storage_path('logs'));
        $segments = explode('/', $path);

        $this->ensure($root !== false && !preg_match('/[\\\\:\x00-\x1f]/', $path));

        if ($path !== '') {
            foreach ($segments as $segment) {
                $this->ensure($segment !== '' && !str_starts_with($segment, '.'));
            }
        }

        $resolved = realpath($root . DIRECTORY_SEPARATOR . $path);
        $this->ensure($resolved !== false);
        $this->ensure($resolved === $root || str_starts_with($resolved, $root . DIRECTORY_SEPARATOR));
        $this->ensure(is_readable($resolved));

        return $resolved;
    }

    private function isLogFile(string $name): bool {
        return preg_match('/\.log(?:\.\d+)?$/i', $name) === 1;
    }

    private function ensure(bool $available): void {
        if (!$available) {
            throw new ErrorMessageException(__('error.log_file_unavailable'), 404);
        }
    }
}
