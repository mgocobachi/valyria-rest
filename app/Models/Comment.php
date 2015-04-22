<?php
namespace Valyria\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /**
     * The database table used by the model
     *
     * @var string
     */
    protected $table = 'comments';

    /**
     * The attributes excluded from the model's JSON form
     *
     * @var array
     */
    protected $hidden = ['created_by'];

    /**
     * The image belong to an user
     *
     * @see Valyria\Models\Image
     */
    public function image()
    {
        return $this->belongsTo('Valyria\Models\Image');
    }
}