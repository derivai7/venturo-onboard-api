<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Report\TotalSaleHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SaleSummaryController extends Controller
{
    private $sale;

    public function __construct()
    {
        $this->sale = new TotalSaleHelper();
    }

    public function getDiagramPerYear()
    {
        $sale = $this->sale->getTotalPerYears();
        return response()->success($sale['data']);
    }

    public function getDiagramPerMonth($year)
    {
        $sale = $this->sale->getTotalPerMonths($year);
        return response()->success($sale['data']);
    }

    public function getDiagramPerCustomDate(Request $request)
    {
        $startDate = $request->startDate ?? null;
        $endDate = $request->endDate ?? null;

        $sale = $this->sale->getTotalPerCustomDate($startDate, $endDate);
        return response()->success($sale['data']);
    }

    public function getTotalSummary()
    {
        $sale = $this->sale->getTotalInPeriod();
        return response()->success($sale['data']);
    }
}
