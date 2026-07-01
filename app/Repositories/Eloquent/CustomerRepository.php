<?php

namespace App\Repositories\Eloquent;

use App\Models\Customer;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CustomerRepository extends BaseRepository implements CustomerRepositoryInterface
{
    protected array $searchable = [
        'customer_code',
        'customer_name',
        'email',
        'phone',
    ];

    protected function modelClass(): string
    {
        return Customer::class;
    }

    public function delete(Model|int $model): bool
    {
        return DB::transaction(function () use ($model): bool {
            $record = $model instanceof Customer ? $model : $this->find($model);

            if ($record->salesInvoices()->exists()) {
                throw new RuntimeException('Cannot delete a customer that has sales invoices. Remove the related invoices first.');
            }

            return parent::delete($record);
        });
    }
}
