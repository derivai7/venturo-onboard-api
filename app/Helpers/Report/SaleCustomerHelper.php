<?php

namespace App\Helpers\Report;

use App\Helpers\Pivot;
use App\Models\CustomerModel;
use App\Models\SaleModel;
use Exception;

class SaleCustomerHelper extends Pivot
{
    public $dates;
    public $endDate;
    public $sale;
    public $customers;
    public $startDate;
    public $total;
    public $totalPerDate;

    public function __construct()
    {
        $this->sale = new SaleModel();
        $this->customers = new CustomerModel();
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
            $customerId = $sale['customer']['id'];

            $date = date('Y-m-d', strtotime($sale['date']));
            $customerName = $sale['customer']['name'];
            $totalSale = $sale['total_payment'];
            $listTransactions = $saleDetail[$customerId]['transactions'] ?? $periods;
            $subTotal = $saleDetail[$customerId]['transactions'][$date]['total_sale'] ?? 0;

            $saleDetail[$customerId] = [
                'customer_id' => $customerId,
                'customer_name' => $customerName,
                'transactions' => $listTransactions,
                'transactions_total' => ($saleDetail[$customerId]['transactions_total'] ?? 0) + $totalSale,
            ];

            $saleDetail[$customerId]['transactions'][$date] = [
                'date_transaction' => $date,
                'total_sale' => $totalSale + $subTotal,
            ];

            $this->totalPerDate[$date] = ($this->totalPerDate[$date] ?? 0) + $totalSale;
            $this->total = ($this->total ?? 0) + $totalSale;
        }

        return $this->convertNumericKey($saleDetail);
    }

    private function convertNumericKey($saleDetail): array
    {
        $indexSale = 0;

        foreach ($saleDetail as $sale) {
            $list[$indexSale] = [
                'customer_id' => $sale['customer_id'],
                'customer_name' => $sale['customer_name'],
                'transactions' => array_values($sale['transactions']),
                'transactions_total' => $sale['transactions_total'],
            ];

            $indexSale++;
        }

        unset($saleDetail);

        return $list ?? [];
    }

    /**
     * @throws Exception
     */
    public function get($startDate, $endDate, $customerId): array
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $customer = $customerId ? explode(',', $customerId) : [];

        $sale = $this->sale->getSaleByCustomers($startDate, $endDate, $customer);

        return [
            'status' => true,
            'data' => $this->reformatReport($sale),
            'dates' => array_values($this->dates),
            'total_per_date' => array_values($this->totalPerDate),
            'grand_total' => $this->total
        ];
    }

    private function reformatDetail($saleDetail): array
    {
        if ($saleDetail->isEmpty()) {
            return [];
        }

        $saleDetail = $saleDetail->toArray();
        $date = $saleDetail[0]['date'];
        $customerName = $saleDetail[0]['customer']['name'];

        $list = [
            'date' => $date,
            'customer_name' => $customerName,
            'transactions' => [],
            'transactions_total' => 0,
        ];

        foreach ($saleDetail as $item) {
            $discount = 0;
            if (!empty($saleDetail[0]['m_discount_id'])) {
                foreach ($item['details'] as $detail) {
                    $discount += $detail['discount_nominal'];
                }
            }

            $list['transactions'][] = [
                'no_receipt' => $item['no_receipt'],
                'subtotal' => $item['subtotal'],
                'tax' => $item['subtotal'] * 0.11,
                'voucher' => $item['voucher_nominal'],
                'discount' => $discount,
                'total_payment' => $item['total_payment'],
            ];

            $list['transactions_total'] += $item['total_payment'];
        }

        return $list;
    }

    public function getDetail($customerId, $date): array
    {
        $saleDetail = $this->sale->getSaleDetailCustomer($customerId, $date);

        return $this->reformatDetail($saleDetail);
    }
}
