<?php
namespace Valyria\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class User extends Model implements AuthenticatableContract
{
	use Authenticatable;

	/**
	 * The database table used by the model
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable
	 *
	 * @var array
	 */
	protected $fillable = ['first_name', 'last_name', 'email', 'password'];

	/**
	 * The attributes excluded from the model's JSON form
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'created_by'];

	/**
	 * The user has many images
	 *
	 * @see Valyria\Models\Image
	 */
	public function images()
	{
		return $this->hasMany('Valyria\Models\Image');
	}

	/**
	 * Sets the email in lowercarse
	 *
	 * @param string $value
	 */
	public function setEmailAttribute($value)
	{
		$this->attributes['email'] = strtolower($value);
	}

	/**
	 * Sets the first name in lowercase
	 *
	 * @param string $value
	 */
	public function setFirstNameAttribute($value)
	{
		$this->attributes['first_name'] = strtolower($value);
	}

	/**
	 * Gets the first name uppercase each word
	 *
	 * @return string
	 */
	public function getFirstNameAttribute()
	{
		return ucwords($this->attributes['first_name']);
	}

	/**
	 * Sets the last name in lowercase
	 *
	 * @param string $value
	 */
	public function setLastNameAttribute($value)
	{
		$this->attributes['last_name'] = strtolower($value);
	}

	/**
	 * Gets the last name in uppercase each word
	 *
	 * @return string
	 */
	public function getLastNameAttribute()
	{
		return ucwords($this->attributes['last_name']);
	}
}