<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDriverStorePivotTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('driver_store', function (Blueprint $table) {
			$table->integer('driver_id')->unsigned()->index();
			$table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade');
			$table->integer('store_id')->unsigned()->index();
			$table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
			$table->primary(['driver_id',
			                 'store_id']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('driver_store');
	}
}
