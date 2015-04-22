<?php
namespace Valyria\Http\Controllers;

use Auth;
use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Valyria\Models\Repositories\Eloquent\Repository;
use Valyria\Models\User as UserModel;

/**
 * REST API Controller
 *
 * Methods:
 *    store(Request)
 *    show(Request, integer)
 *    update(Request, integer)
 *    destroy(Request, integer)
 *
 * @package Valyria\Http\Controllers
 */
abstract class RestController extends BaseController
{
    use DispatchesCommands;

    const STAT_OK = 'ok';
    const STAT_ERR = 'error';
    const STAT_UNKNOWN = 'unknown';

    /**
     * Model repository reference
     *
     * @var Repository
     */
    protected $repository = null;

    /**
     * HTTP headers
     *
     * @var array
     */
    protected $headers = array();

    /**
     * User Authenticated
     *
     * @var Valyria\Models\Consumer|null
     */
    protected $user = null;

    /**
     * Errors of validation
     *
     * @var array
     */
    protected $errors = [];

    /**
     * Constructor
     *
     * @param Repository $repository
     */
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Gets all the elements
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        return $this->notImplemented();
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
        $result = $this->repository->create($request);

        if (!empty($result)) {
            return $this->response($result, [
                'stat' => self::STAT_OK,
                'code' => Response::HTTP_CREATED,
            ]);
        }

        return $this->response('The element can not be created', [
            'code' => Response::HTTP_NOT_ACCEPTABLE
        ]);
    }

    /**
     * Display the specified resource
     *
     * @param Request $request
     * @param int $id
     *
     * @return Response
     */
    public function show(Request $request, $id)
    {
        if (!ctype_digit($id)) {
            return $this->response('The parameter is not valid', [
                'code' => Response::HTTP_LENGTH_REQUIRED
            ]);
        }

        $result = $this->repository->find($id);

        if (empty($result)) {
            return $this->response('The element does not exists', [
                'code' => Response::HTTP_NOT_FOUND
            ]);
        }

        if (!$this->hasPermission()) {
            return $this->deny();
        }

        return $this->response($result, [
            'stat' => self::STAT_OK,
            'code' => Response::HTTP_OK,
        ]);
    }

    /**
     * Update the specified resource in storage
     *
     * @param Request $request
     * @param int $id
     *
     * @return Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage
     *
     * @param Request $request
     * @param int $id
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        if (!ctype_digit($id)) {
            return $this->response('The parameter is not valid');
        }

        $result = $this->repository->delete($id);

        if (!empty($result)) {
            return $this->response('The element has been deleted', [
                'stat' => self::STAT_OK,
                'code' => Response::HTTP_OK,
            ]);
        }

        return $this->response('The element can not be deleted');
    }

    /**
     * Add a new element to the errors of response
     *
     * @param array $data
     */
    public function addError(array $data)
    {
        array_push($this->errors, $data);
    }

    /**
     * Gets an array of validation errors
     *
     * @return array
     */
    final protected function getErrors()
    {
        return $this->errors;
    }

    /**
     * Sets HTTP headers
     *
     * @param array $data
     */
    final protected function setHeaders(array $data)
    {
        $this->headers = $data;
    }

    /**
     * Gets the HTTP headers
     *
     * @return array
     */
    final protected function getHeaders()
    {
        return $this->headers;
    }

    /**
     * JSON response
     *
     * @param mixed $data
     * @param array $options
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    final protected function response($data, array $options = array())
    {
        $http_status = (isset($options['code']) ? $options['code'] : Response::HTTP_BAD_REQUEST);
        $result = array(
            'stat' => (isset($options['stat']) ? $options['stat'] : RESTController::STAT_ERR),
            'result' => (is_string($data) ? ['id' => $data] : $data),
        );

        return response()->json($result, $http_status, $this->getHeaders());
    }

    /**
     * Validate an array with the filter defined on the controller
     *
     * @param array $data
     * @param array $rules
     *
     * @return Response
     */
    final protected function hasParameters(array $data, array $rules)
    {
        $validator = Validator::make($data, $rules);

        if (!$validator->fails()) {
            return true;
        }

        $this->errors = $validator->errors();

        return false;
    }

    /**
     * Gets the user authenticated
     *
     * @return mixed
     */
    final protected function getUserAuthenticate()
    {
        if (is_null($this->user)) {
            $this->user = Auth::user();
        }

        return $this->user;
    }

    /**
     * Verify if the user authenticated is the owner of the asset
     *
     * @param integer $id
     * @param string $column
     *
     * @return bool
     */
    final protected function hasPermission($id = 0, $column = 'id')
    {
        if ($id == 0) {
            return UserModel::where('created_by', '=', $this->getUserAuthenticate()->id)
                ->exists();
        }

        return UserModel::where('created_by', '=', $this->getUserAuthenticate()->id)
            ->where($column, '=', $id)
            ->exists();
    }

    /**
     * Return response with denied result
     *
     * @return Response
     */
    final protected function deny()
    {
        return $this->response('Permission denied', [
            'stat' => self::STAT_ERR,
            'code' => Response::HTTP_UNAUTHORIZED,
        ]);
    }
}