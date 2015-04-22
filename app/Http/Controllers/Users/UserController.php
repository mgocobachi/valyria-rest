<?php
namespace Valyria\Http\Controllers\Users;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Valyria\Http\Controllers\RestController as Controller;
use Valyria\Models\Repositories\User as Repository;

class UserController extends Controller
{
    /**
     * Constructor
     *
     * @param Repository $repository
     */
    public function __construct(Repository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Store a newly created resource in storage
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        if (!$this->hasParameters($request->all(), [
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'firstname' => 'required',
            'lastname' => 'required',
        ])
        ) {
            return $this->response($this->getErrors());
        }

        return parent::store($request);
    }

    /**
     * Delete the specific resource
     *
     * @param Request $request
     * @param integer $id
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        if (!$this->hasPermission()) {
            return $this->deny();
        }

        return parent::destroy($request, $id);
    }
}