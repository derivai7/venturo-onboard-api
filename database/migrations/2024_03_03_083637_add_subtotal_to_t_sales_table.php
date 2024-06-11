<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubtotalToTSalesTable extends Migration
{
    public function up()
    {
        Schema::table('t_sales', function (Blueprint $table) {
            $table->decimal('subtotal', 15)
                ->after('no_receipt')->comment('Subtotal amount in rupiah');
        });
    }

    public function down()
    {
        Schema::table('t_sales', function (Blueprint $table) {
            $table->dropColumn('subtotal');
        });
    }
}
