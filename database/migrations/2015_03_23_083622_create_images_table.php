<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
	/**
	 * Run the migrations
	 *
	 * @return void
	 */
	public function up()
	{
		if (!Schema::hasTable('images')) {
			Schema::create('images', function (Blueprint $table) {
				$table->bigIncrements('id');
				$table->string('name')->index();
				$table->string('path', 255);
				$table->string('thumb', 255)->nullable();
				$table->string('type', 10);
				$table->integer('size');
				$table->timestamps();
				$table->bigInteger('created_by')->unsigned();
				$table->foreign('created_by')->references('id')->on('consumers')->onDelete('cascade');
				$table->bigInteger('user_id')->unsigned();
				$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
		Schema::dropIfExists('images');
	}
}