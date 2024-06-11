<?php

namespace App\Helpers\Sale;

use App\Helpers\Venturo;
use App\Models\SaleDetailModel;
use App\Models\SaleModel;
use App\Models\VoucherModel;
use Throwable;

/**
 * @property $startDate
 * @property $endDate
 */
class SaleHelper extends Venturo
{
    private $sale;
    private $saleDetail;
    private $voucher;

    public function __construct()
    {
        $this->sale = new SaleModel();
        $this->saleDetail = new SaleDetailModel();
        $this->voucher = new VoucherModel();
    }

    public function create(array $payload): array
    {
        try {
            $this->beginTransaction();

            $payload['no_receipt'] = $this->generateReceiptNumber();
            $payload['date'] = now();

            $sale = $this->sale->store($payload);

            $this->insertDetail($payload['details'] ?? [], $sale->id);

            if ($payload['m_voucher_id']) {
                $this->voucher->useVoucher($payload['m_voucher_id']);
            }

            $this->commitTransaction();

            return [
                'status' => true,
                'data' => $sale
            ];
        } catch (Throwable $th) {
            $this->rollbackTransaction();

            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''): array
    {
        $sale = $this->sale->getAll($filter, $itemPerPage, $sort);

        return [
            'status' => true,
            'data' => $sale
        ];
    }

    private function insertDetail(array $details, string $saleId): void
    {
        foreach ($details as $detail) {
            $detail['t_sales_id'] = $saleId;
            $this->saleDetail->store($detail);
        }
        $this->updateIdDetail();
    }

    public function updateIdDetail() {
        $salesDetail = $this->saleDetail->where('t_sales_id', '0')->get();

        if (!$salesDetail->isEmpty()) {
            $saleNewest = $this->sale->getNewest();
            $saleId = $saleNewest->id;

            $this->saleDetail->where('t_sales_id', '0')->update(['t_sales_id' => $saleId]);
        }
    }

    public function generateReceiptNumber(): string
    {
        $date = now()->format('m/Y');
        $lastSale = $this->sale->getLastSaleByDate($date);
        $lastNumber = $lastSale ? intval(explode('/', $lastSale->no_receipt)[0]) : 0;
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        return "$newNumber/KWT/$date";
    }
}
