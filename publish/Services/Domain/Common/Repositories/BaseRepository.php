<?php

namespace App\Services\Domain\Common\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository {

    protected Model $model;

    abstract public function model(): string;

    public function __construct() {
        $this->model = app($this->model());
    }

    public function create(array $data): Model {
        return $this->model->create($data);
    }

    public function findById(int|string $id): ?Model {
        return $this->model->find($id);
    }

    public function findOrErrorById(int|string $id): ?Model {
        return $this->model->where('id', $id)->firstOrError();
    }

    public function update($model, array $data): bool {
        return $model->update($data);
    }

    public function delete($model): bool {
        return $model->delete();
    }

    public function exists(int|string $id): bool {
        return $this->model->where('id', $id)->exists();
    }

    public function all() {
        return $this->model->all();
    }
}
