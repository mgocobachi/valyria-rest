<?php
namespace Valyria\Http\Controllers\Users\Images;

use Valyria\Http\Requests;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Valyria\Http\Controllers\RESTController as Controller;
use Valyria\Models\Repositories\Comment as Repository;
use Valyria\Models\Image as ImageModel;

class CommentController extends Controller
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
        $userId  = $request->route('users');
        $imageId = $request->route('images');

        if (!ctype_digit($userId)) {
            $this->addError(['user_id' => 'The user id field is required']);
        }

        if (!ctype_digit($imageId)) {
            $this->addError(['image_id' => 'The user id field is required']);
        }

        $this->hasParameters($request->all(), [
            'body' => 'required',
        ]);

        if (!empty($this->errors)) {
            return $this->response($this->getErrors());
        }

        $image = ImageModel::find($imageId);

        if (!empty($image)) {
            if (!$this->hasPermission($image->user->id)) {
                return $this->deny();
            }

            return parent::store($request);
        }

        return $this->response('The image does not exists', [
            'code' => Response::HTTP_NOT_FOUND
        ]);
    }
}