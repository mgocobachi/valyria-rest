<?php
namespace Valyria\Models\Repositories;

use Auth;
use Illuminate\Http\Request;
use Valyria\Models\Repositories\Eloquent\Repository;
use Valyria\Models\Image as ImageModel;
use Valyria\Events\Comment as CommentEvent;

class Comment extends Repository
{
    /**
     * Create a new comment
     *
     * @param Request $request
     *
     * @throws \Exception
     * @return array
     */
    public function create(Request $request)
    {
        $image  = ImageModel::find($request->route('images'));
        $entity = $this->model;
        $body   = strip_tags($request->body);

        $entity->created_by = Auth::user()->id;
        $entity->body       = htmlspecialchars($request->body, ENT_QUOTES, 'UTF-8');

        $image->comments()->save($entity);

        event(new CommentEvent($entity));

        return $entity->toArray();
    }

    /**
     * Find an element
     *
     * @param $id
     * @param array $columns
     *
     * @return mixed
     */
    public function find($id, $columns = array('*'))
    {
        return $this->with('image')->find($id, $columns);
    }
}