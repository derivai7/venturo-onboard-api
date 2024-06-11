<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMPromo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_promo', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 150)
                ->comment('Fill with name of promo');
            $table->enum('status', ['voucher', 'discount'])
                ->comment('Fill with type of promo');
            $table->integer('expired_in_day')->nullable()
                ->comment('Fill with total of active day, Fill with 1 for 1 day, 7 for 1 week, and 30 for 1 month');
            $table->double('nominal_percentage', 15, 8)
                ->nullable()->comment('Fill when status = discount');
            $table->double('nominal_rupiah', 15, 2)
                ->nullable()->comment('Fill when status = voucher');
            $table->text('term_conditions')->comment('Fill with term and condition to get this promo');
            $table->string('photo', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            $table->index('name');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_promo');
    }
}
