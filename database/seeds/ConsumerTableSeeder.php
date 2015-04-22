<?php
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Valyria\Models\Consumer;

class ConsumerTableSeeder extends Seeder
{
	/**
	 * Run the database seeds
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		Consumer::create([
			'username' => 'test',
			'password' => Hash::make('test')
		]);

		Consumer::create([
			'username' => 'foo',
			'password' => Hash::make('foo')
		]);
	}
}