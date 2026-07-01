<?php

namespace App\Repositories\Contracts;

use App\Models\SalesInvoice;

interface SalesInvoiceRepositoryInterface extends BaseRepositoryInterface
{
    public function saveInvoice(array $data, array $items, ?SalesInvoice $invoice = null): SalesInvoice;
}

