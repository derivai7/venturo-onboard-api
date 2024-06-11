<?php

namespace Database\Seeders;

use App\Helpers\Sale\SaleHelper;
use App\Models\SaleDetailModel;
use App\Models\SaleModel;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    private $sale;
    private $helper;

    public function __construct()
    {
        $this->sale = new SaleModel();
        $this->helper = new SaleHelper();
    }

    public function setId()
    {
        $this->helper->updateIdDetail();
    }

    public function update($payload, $id)
    {
        return $this->sale->edit($payload, $id);
    }

    public function run()
    {
        for ($i = 0; $i < 500; $i++) {
            $saleDetail = SaleDetailModel::factory()->count(rand(1, 4))->create([
                't_sales_id' => '0'
            ]);

            $subtotal = $saleDetail->sum(function ($detail) {
                return $detail->price * $detail->total_item;
            });

            $this->sale->factory()->create([
                'subtotal' => $subtotal,
                'total_payment' => $subtotal * 1.11
            ]);

            $this->setId();
            sleep(3);
        }
    }
}
