<?php

namespace App\Repositories\Eloquent;

use App\Models\Supplier;
use App\Repositories\Contracts\SupplierRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class SupplierRepository extends BaseRepository implements SupplierRepositoryInterface
{
    protected array $searchable = [
        'supplier_code',
        'supplier_name',
        'email',
        'phone',
    ];

    protected function modelClass(): string
    {
        return Supplier::class;
    }

    public function delete(Model|int $model): bool
    {
        return DB::transaction(function () use ($model): bool {
            $record = $model instanceof Supplier ? $model : $this->find($model);

            if ($record->purchaseInvoices()->exists()) {
                throw new RuntimeException('Cannot delete a supplier that has purchase invoices. Remove the related invoices first.');
            }

            return parent::delete($record);
        });
    }
}
