<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveSoftDeletesFromMDiscountTable extends Migration
{
    public function up()
    {
        Schema::table('m_discount', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }

    public function down()
    {
        Schema::table('m_discount', function (Blueprint $table) {
            $table->softDeletes();
        });
    }
}
