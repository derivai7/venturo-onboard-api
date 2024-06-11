<?php

namespace App\Http\Controllers\Api;

use App\Exports\ReportSaleCategory;
use App\Exports\ReportSaleCustomer;
use App\Helpers\Report\SaleCategoryHelper;
use App\Helpers\Report\SaleCustomerHelper;
use App\Helpers\Report\SalePromoHelper;
use App\Helpers\Report\SaleTransactionHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Report\SalePromoCollection;
use App\Http\Resources\Report\SaleTransactionCollection;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportSaleController extends Controller
{
    private $salePromo;
    private $saleTransaction;
    private $saleCategory;
    private $saleCustomer;

    public function __construct()
    {
        $this->salePromo = new SalePromoHelper();
        $this->saleTransaction = new SaleTransactionHelper();
        $this->saleCategory = new SaleCategoryHelper();
        $this->saleCustomer = new SaleCustomerHelper();
    }

    public function viewSalePromo(Request $request)
    {
        $filter = [
            'start_date' => $request->start_date ?? null,
            'end_date' => $request->end_date ?? null,
            'customer_id' => isset($request->customer_id) ? explode(',', $request->customer_id) : [],
            'promo_id' => isset($request->promo_id) ? explode(',', $request->promo_id) : [],
        ];

        $sale = $this->salePromo->get($filter, $request->per_page ?? 25, $request->sort ?? '');
        return response()->success(new SalePromoCollection($sale['data']));
    }

    public function viewSaleTransaction(Request $request)
    {
        $filter = [
            'start_date' => $request->start_date ?? null,
            'end_date' => $request->end_date ?? null,
            'customer_id' => isset($request->customer_id) ? explode(',', $request->customer_id) : [],
            'menu_id' => isset($request->menu_id) ? explode(',', $request->menu_id) : [],
        ];

        $sale = $this->saleTransaction->get($filter, $request->per_page ?? 25, $request->sort ?? '');
        return response()->success(new SaleTransactionCollection($sale['data']));
    }

    /**
     * @throws Exception
     */
    public function viewSaleCategories(Request $request)
    {
        $startDate = $request->start_date ?? null;
        $endDate = $request->end_date ?? null;
        $categoryId = $request->category_id ?? null;
        $isExportExcel = $request->is_export_excel ?? null;

        $sale = $this->saleCategory->get($startDate, $endDate, $categoryId);

        if ($isExportExcel) {
            return Excel::download(
                new ReportSaleCategory($sale, $categoryId != null), 'report-sale-category.xls'
            );
        }

        return response()->success($sale['data'], '', [
            'dates' => $sale['dates'] ?? [],
            'total_per_date' => $sale['total_per_date'] ?? [],
            'grand_total' => $sale['grand_total'] ?? 0
        ]);
    }

    /**
     * @throws Exception
     */
    public function viewSaleCustomers(Request $request)
    {
        $startDate = $request->start_date ?? null;
        $endDate = $request->end_date ?? null;
        $customerId = $request->customer_id ?? null;
        $isExportExcel = $request->is_export_excel ?? null;

        $sale = $this->saleCustomer->get($startDate, $endDate, $customerId);

        if ($isExportExcel) {
            return Excel::download(new ReportSaleCustomer($sale), 'report-sale-customer.xls');
        }

        return response()->success($sale['data'], '', [
            'dates' => $sale['dates'] ?? [],
            'total_per_date' => $sale['total_per_date'] ?? [],
            'grand_total' => $sale['grand_total'] ?? 0
        ]);
    }

    public function showSaleDetailCustomer($customerId, $date)
    {
        $saleDetail = $this->saleCustomer->getDetail($customerId, $date);

        return response()->success($saleDetail);
    }
}
