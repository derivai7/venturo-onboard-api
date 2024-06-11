<?php

namespace App\Helpers\Report;

use App\Helpers\Pivot;
use App\Models\SaleModel;
use Exception;

class SaleCategoryHelper extends Pivot
{
    public $dates;
    public $endDate;
    public $sale;
    public $startDate;
    public $total;
    public $totalPerDate;

    public function __construct()
    {
        $this->sale = new SaleModel();
    }

    /**
     * @throws Exception
     */
    private function reformatReport($list): array
    {
        $list = $list->toArray();
        $periods = $this->getPeriod();
        $saleDetail = [];

        foreach ($list as $sale) {
            foreach ($sale['details'] as $detail) {
                if (empty($detail['product'])) {
                    continue;
                }

                $date = date('Y-m-d', strtotime($sale['date']));
                $categoryId = $detail['product']['m_product_category_id'];
                $categoryName = $detail['product']['category']['name'];
                $productId = $detail['product']['id'];
                $productName = $detail['product']['name'];
                $totalSale = $detail['price'] * $detail['total_item'];
                $listTransactions = $saleDetail[$categoryId]['products'][$productId]['transactions'] ?? $periods;
                $subTotal = $saleDetail[$categoryId]['products'][$productId]['transactions'][$date]['total_sale'] ?? 0;
                $totalPerProduct = $saleDetail[$categoryId]['products'][$productId]['transactions_total'] ?? 0;
                $totalPerCategory = $saleDetail[$categoryId]['category_total'] ?? 0;

                $saleDetail[$categoryId] = [
                    'category_id' => $categoryId,
                    'category_name' => $categoryName,
                    'category_total' => $totalPerCategory + $totalSale,
                    'products' => $saleDetail[$categoryId]['products'] ?? [],
                ];

                $saleDetail[$categoryId]['products'][$productId] = [
                    'product_id' => $productId,
                    'product_name' => $productName,
                    'transactions' => $listTransactions,
                    'transactions_total' => $totalPerProduct + $totalSale
                ];

                $saleDetail[$categoryId]['products'][$productId]['transactions'][$date] = [
                    'date_transaction' => $date,
                    'total_sale' => $totalSale + $subTotal
                ];

                $this->totalPerDate[$date] = ($this->totalPerDate[$date] ?? 0) + $totalSale;
                $this->total = ($this->total ?? 0) + $totalSale;
            }
        }

        return $this->convertNumericKey($saleDetail);
    }

    private function convertNumericKey($saleDetail): array
    {
        $indexSale = 0;

        foreach ($saleDetail as $sale) {
            $list[$indexSale] = [
                'category_id' => $sale['category_id'],
                'category_name' => $sale['category_name'],
                'category_total' => $sale['category_total']
            ];

            $indexProducts = 0;
            foreach ($sale['products'] as $product) {
                $list[$indexSale]['products'][$indexProducts] = [
                    'product_id' => $product['product_id'],
                    'product_name' => $product['product_name'],
                    'transactions' => array_values($product['transactions']),
                    'transactions_total' => $product['transactions_total']
                ];

                $indexProducts++;
            }

            $indexSale++;
        }

        unset($saleDetail);

        return $list ?? [];
    }

    /**
     * @throws Exception
     */
    public function get($startDate, $endDate, $categoryId = ''): array
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;

        $sale = $this->sale->getSaleByCategory($startDate, $endDate, $categoryId);

        return [
            'status' => true,
            'data' => $this->reformatReport($sale),
            'dates' => array_values($this->dates),
            'total_per_date' => array_values($this->totalPerDate),
            'grand_total' => $this->total
        ];
    }
}
