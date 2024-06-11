<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNoReceiptAndTotalPaymentToTSalesTable extends Migration
{
    public function up()
    {
        Schema::table('t_sales', function (Blueprint $table) {
            $table->string('no_receipt')->unique()->after('id')->comment('Unique receipt number for each transaction');
            $table->decimal('total_payment', 15, 2)->after('no_receipt')->comment('Total payment amount in rupiah');
        });
    }

    public function down()
    {
        Schema::table('t_sales', function (Blueprint $table) {
            $table->dropColumn(['no_receipt', 'total_payment']);
        });
    }
}
