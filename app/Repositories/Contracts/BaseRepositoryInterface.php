<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{
    public function all(array $relations = []): Collection;

    public function paginate(?string $search = null, int $perPage = 15, array $relations = []): LengthAwarePaginator;

    public function find(int $id, array $relations = []): Model;

    public function create(array $data): Model;

    public function update(Model|int $model, array $data): Model;

    public function delete(Model|int $model): bool;
}

