<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
	/**
	 * Run the migrations
	 *
	 * @return void
	 */
	public function up()
	{
		if (!Schema::hasTable('users')) {
			Schema::create('users', function (Blueprint $table) {
				$table->bigIncrements('id');
				$table->string('email', 128)->unique();
				$table->string('first_name', 128)->nullable();
				$table->string('last_name', 128)->nullable();
				$table->string('password', 64);
				$table->timestamps();
				$table->bigInteger('created_by')->unsigned();
				$table->foreign('created_by')->references('id')->on('consumers')->onDelete('cascade');
			});
		}
	}

	/**
	 * Reverse the migrations
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('users');
	}
}