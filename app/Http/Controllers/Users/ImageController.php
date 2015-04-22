<?php
namespace Valyria\Http\Controllers\Users;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Valyria\Http\Controllers\RESTController as Controller;
use Valyria\Models\Repositories\Image as Repository;

class ImageController extends Controller
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
        $userId = $request->route('users');

        if ($request->isJson()) {
            $this->addError(['format' => 'JSON is not supported for upload image']);
        }

        if (!ctype_digit($userId)) {
            $this->addError(['user_id' => 'The user id field is required']);
        }

        $this->hasParameters($request->all(), [
            'file' => 'required|mimes:jpeg,png',
        ]);

        if (!empty($this->errors)) {
            return $this->response($this->getErrors());
        }

        if (!$this->hasPermission($userId)) {
            return $this->deny();
        }

        return parent::store($request);
    }
}