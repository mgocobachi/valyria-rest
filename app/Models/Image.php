<?php
namespace Valyria\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    /**
     * The database table used by the model
     *
     * @var string
     */
    protected $table = 'images';

    /**
     * The attributes excluded from the model's JSON form
     *
     * @var array
     */
    protected $hidden = ['path', 'thumb', 'created_by'];

    /**
     * The image belong to an user
     *
     * @see Valyria\Models\User
     */
    public function user()
    {
        return $this->belongsTo('Valyria\Models\User');
    }

    /**
     * The image has many comments
     *
     * @see Valyria\Models\Comments
     */
    public function comments()
    {
        return $this->hasMany('Valyria\Models\Comment');
    }
}