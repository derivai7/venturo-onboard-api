<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNoteInTSalesDetailTable extends Migration
{
    public function up()
    {
        Schema::table('t_sales_detail', function (Blueprint $table) {
            $table->text('note')->nullable()->after('discount_nominal');
        });
    }

    public function down()
    {
        Schema::table('t_sales_detail', function (Blueprint $table) {
            $table->dropColumn('note');
        });
    }
}
