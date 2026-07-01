<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected array $with = [];

    protected array $searchable = [];

    abstract protected function modelClass(): string;

    protected function query(array $relations = []): Builder
    {
        $relations = array_values(array_unique(array_merge($this->with, $relations)));

        return ($this->modelClass())::query()->with($relations);
    }

    protected function applySearch(Builder $query, ?string $search): Builder
    {
        if (! $search || empty($this->searchable)) {
            return $query;
        }

        return $query->where(function (Builder $builder) use ($search): void {
            foreach ($this->searchable as $index => $column) {
                $method = $index === 0 ? 'where' : 'orWhere';
                $builder->{$method}($column, 'like', "%{$search}%");
            }
        });
    }

    public function all(array $relations = []): Collection
    {
        return $this->query($relations)->latest()->get();
    }

    public function paginate(?string $search = null, int $perPage = 15, array $relations = []): LengthAwarePaginator
    {
        return $this->applySearch($this->query($relations), $search)
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function find(int $id, array $relations = []): Model
    {
        return $this->query($relations)->findOrFail($id);
    }

    public function create(array $data): Model
    {
        $modelClass = $this->modelClass();

        return $modelClass::create($data);
    }

    public function update(Model|int $model, array $data): Model
    {
        $record = $model instanceof Model ? $model : $this->find($model);
        $record->fill($data)->save();

        return $record->refresh();
    }

    public function delete(Model|int $model): bool
    {
        $record = $model instanceof Model ? $model : $this->find($model);

        return (bool) $record->delete();
    }
}
