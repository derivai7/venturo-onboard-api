<?php

namespace App\Helpers\Report;

use App\Helpers\Venturo;
use App\Models\SaleModel;

class SalePromoHelper extends Venturo
{
    private $sale;

    public function __construct()
    {
        $this->sale = new SaleModel();
    }

    public function get(array $filter, int $itemPerPage = 0, string $sort = ''): array
    {
        $sale = $this->sale->getSalePromo($filter, $itemPerPage, $sort);

        return [
            'status' => true,
            'data'   => $sale
        ];
    }
}
