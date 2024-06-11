<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ReportSaleCategory implements FromView
{
    private $reports;
    private $isCategoryFiltered;

    public function __construct(array $sales, bool $isCategoryFiltered)
    {
        $this->reports = $sales;
        $this->isCategoryFiltered = $isCategoryFiltered;
    }

    public function view() : View
    {
        return view('generate.excel.report-sale', [
            'reports' => $this->reports,
            'isCategoryFiltered' => $this->isCategoryFiltered
        ]);
    }
}

