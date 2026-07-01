<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    protected array $searchable = [
        'product_code',
        'product_name',
        'description',
    ];

    protected function modelClass(): string
    {
        return Product::class;
    }

    public function delete(Model|int $model): bool
    {
        return DB::transaction(function () use ($model): bool {
            $record = $model instanceof Product ? $model : $this->find($model);

            $isUsed = $record->purchaseInvoiceItems()->exists() || $record->salesInvoiceItems()->exists();

            if ($isUsed) {
                throw new RuntimeException('Cannot delete a product that is already used in invoice history. Mark it inactive instead.');
            }

            return parent::delete($record);
        });
    }
}
