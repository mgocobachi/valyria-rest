<?php
namespace Valyria\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class Consumer extends Model implements AuthenticatableContract
{
	use Authenticatable;

	/**
	 * The database table used by the model
	 *
	 * @var string
	 */
	protected $table = 'consumers';

	/**
	 * The attributes excluded from the model's JSON form
	 *
	 * @var array
	 */
	protected $hidden = ['password'];

	/**
	 * The consumer has many users
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function users()
	{
		return $this->hasMany('Valyria\Models\User');
	}
}