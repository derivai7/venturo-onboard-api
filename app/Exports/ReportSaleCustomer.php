<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ReportSaleCustomer implements FromView
{
    private $reports;

    public function __construct(array $sales)
    {
        $this->reports = $sales;
    }

    public function view() : View
    {
        return view('generate.excel.report-customer', $this->reports);
    }
}

