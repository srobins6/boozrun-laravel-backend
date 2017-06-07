<?php
	use App\Admin;
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;

	class CreateAdminsTable extends Migration {

		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up() {
			Schema::create('admins',
				function (Blueprint $table) {
					$table->increments('id');
					$table->string('email')->unique();
					$table->string('name');
					$table->string('password', 60)->default(bcrypt(env("PW_DEFAULT_ADMIN")));
					$table->boolean('control')->default(true);
					$table->rememberToken();
					$table->timestamps();
				});
			Admin::create(["email" => "admin@boozrunapp.com",
			               "name"  => "admin"]);
			Admin::create(["email" => "srobins6@gmail.com",
			               "name"  => "Sol Robinson"]);
		}

		/**
		 * Reverse the migrations.
		 *
		 * @return void
		 */
		public function down() {
			Schema::drop('admins');
		}
	}
