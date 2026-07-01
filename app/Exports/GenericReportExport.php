<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class GenericReportExport implements FromArray, WithHeadings, WithTitle
{
    public function __construct(
        private readonly string $title,
        private readonly array $headers,
        private readonly array $rows,
        private readonly array $summary = [],
    ) {
    }

    public function array(): array
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return $this->headers;
    }

    public function title(): string
    {
        return $this->title;
    }
}

