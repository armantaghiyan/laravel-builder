<?php

namespace App\Services\Domain\Common\Models;

use App\Services\Domain\Common\Constants\StatusCodes;
use App\Services\Infrastructure\Exceptions\ErrorMessageException;
use Illuminate\Database\Eloquent\Builder;


/**
 * @method static \Illuminate\Database\Eloquent\Builder|static page(int $loadedCount, int $perPage = 20)
 * @method static \Illuminate\Database\Eloquent\Builder|static filter(string $col, mixed $value)
 * @method static mixed firstOrError(array $cols = ['*'], string $message = 'not fount item.')
 * @method static \Illuminate\Database\Eloquent\Builder|static page2(int $perPage = 20)
 * @method static \Illuminate\Database\Eloquent\Builder|static search(array $cols, $value)
 * @method static \Illuminate\Database\Eloquent\Builder|static searchInColumns($value, array $cols)
 */
trait BaseModel {

    private function checkParam(mixed $value): bool {
        if ($value === null || $value === '')
            return false;

        return true;
    }

    public function scopePage(Builder $query, int $loadedCount, int $perPage = 20): Builder {
        return $query->skip($loadedCount)->limit($perPage);
    }

    public function scopeFilter(Builder $query, string $col, mixed $value): Builder {
        if ($this->checkParam($value)) {
            if (gettype($value) === 'string' && str_contains($value, '%')) {
                return $query->where($col, 'LIKE', $value);
            }

            return $query->where($col, $value);
        }

        return $query;
    }

    public function scopeSearch(Builder $query, array $cols, mixed $value): Builder {
        if ($this->checkParam($value)) {
            $query->where(function ($query) use ($cols, $value) {
                foreach ($cols as $col) {
                    $query->orWhere($col, 'LIKE', "%$value%");
                }
            });

            return $query;
        }

        return $query;
    }

    /**
     * @throws ErrorMessageException
     */
    public function scopeFirstOrError(Builder $query, array $cols = ['*'], string $message = 'not fount item.'): mixed {
        $result = $query->first($cols);

        if (!$result) {
            throw new ErrorMessageException($message, StatusCodes::Not_found);
        }

        return $result;
    }

    public function scopePage2(Builder $query, $perPage = 20) {
        $offset = ((int)request('page', 1) - 1) * $perPage;
        return $query->offset($offset)->limit($perPage);
    }

    public function scopeSearchInColumns($query, string|null $value, array $columns) {
        if ($this->checkParam($value)) {
            return $query->where(function ($q) use ($value, $columns) {
                foreach ($columns as $column) {
                    $q->orWhere($column, 'like', "%$value%");
                }
            });
        }

        return $query;
    }
}
