<?php
use App\Store;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHoursTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('hours', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('store_id')->unsigned()->index();
			$table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
			$table->string('name');
			$table->boolean('active')->default(false);
			$days = ["monday",
			         "tuesday",
			         "wednesday",
			         "thursday",
			         "friday",
			         "saturday",
			         "sunday"];
			foreach ($days as $day) {
				$table->time($day . "start")->default("00:00:00");
				$table->time($day . "end")->default("00:00:00");
				$table->boolean($day . "open")->default(true);
			}
			$table->timestamps();
		});
		Store::create(['email' => 'info@boozrunapp.com',
		               'name' => 'demoStore']);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('hours');
	}
}
