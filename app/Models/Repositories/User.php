<?php
namespace Valyria\Models\Repositories;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Valyria\Models\Repositories\Eloquent\Repository;

class User extends Repository
{
    /**
     * Create a new user
     *
     * @param Request $request
     *
     * @return array
     */
    public function create(Request $request)
    {
        $entity = $this->model;

        $entity->email      = $request->email;
        $entity->first_name = $request->firstname;
        $entity->last_name  = $request->lastname;
        $entity->password   = Hash::make($request->password);
        $entity->created_by = Auth::user()->id;

        $entity->save();

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
        return $this->with('images')->find($id, $columns);
    }

    /**
     * Delete an element
     *
     * @param $id
     *
     * @return mixed
     */
    public function delete($id)
    {
        // TODO: Remove image files from the filesystem, trigger event
        return parent::delete($id);
    }
}