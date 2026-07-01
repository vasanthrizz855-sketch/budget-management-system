<?php

namespace App\Repositories\Contracts;

use App\Models\PurchaseInvoice;

interface PurchaseInvoiceRepositoryInterface extends BaseRepositoryInterface
{
    public function saveInvoice(array $data, array $items, ?PurchaseInvoice $invoice = null): PurchaseInvoice;
}

