<?php

namespace App\Repositories\Eloquent;

use App\Models\Budget;
use App\Repositories\Contracts\BudgetRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class BudgetRepository extends BaseRepository implements BudgetRepositoryInterface
{
    protected array $with = [
        'expenses',
    ];

    protected array $searchable = [
        'budget_name',
        'budget_type',
    ];

    protected function modelClass(): string
    {
        return Budget::class;
    }

    public function delete(Model|int $model): bool
    {
        return DB::transaction(function () use ($model): bool {
            $record = $model instanceof Budget ? $model : $this->find($model);

            if ($record->expenses()->exists()) {
                throw new RuntimeException('Cannot delete a budget that has linked expenses. Remove the expenses first.');
            }

            return parent::delete($record);
        });
    }
}
