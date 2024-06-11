<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RecreateMDiscountIdInTSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_sales', function (Blueprint $table) {
            $table->dropColumn('m_discount_id');
        });

        Schema::table('t_sales', function (Blueprint $table) {
            $table->string('m_discount_id')->nullable()->after('m_voucher_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_sales', function (Blueprint $table) {
            $table->dropColumn('m_discount_id');
        });

        Schema::table('t_sales', function (Blueprint $table) {
            $table->bigInteger('m_discount_id')->nullable()->after('m_voucher_id');
        });
    }
}
