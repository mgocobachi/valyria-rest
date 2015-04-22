<?php
namespace Valyria\Models\Repositories\Eloquent;

use Auth;
use Illuminate\Http\Request;
use Valyria\Models\Repositories\Contracts\Repository as RepositoryContract;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;

abstract class Repository implements RepositoryContract
{
    /**
     * Laravel application reference
     *
     * @var object
     */
    protected $app;

    /**
     * Model object
     *
     * @var object
     */
    protected $model;

    /**
     * Name of the model
     *
     * @var string
     */
    protected $name;

    /**
     * Constructor
     *
     * @param Container $app
     * @throws \Exception
     */
    public function __construct(Container $app)
    {
        $this->app = $app;

        $this->makeModel();
    }

    /**
     * Create the model and assign to its property
     *
     * @return Model
     * @throws \Exception
     */
    protected function makeModel()
    {
        preg_match('/\\\\([A-Za-z]+)$/', get_class($this), $matches);
        $class  = (isset($matches[1]) ? 'Valyria\\Models\\' . $matches[1] : null);
        $model  = $this->app->make($class);

        $this->name = $matches[1];

        if (!$model instanceof Model) {
            throw new \Exception('Class must be an instance of Illuminate\\Database\\Eloquent\\Model');
        }

        return $this->model = $model;
    }

    /**
     * @param array $columns
     *
     * @return mixed
     */
    public function all($columns = array('*'))
    {
        return $this->model->get($columns);
    }

    /**
     * @param int $perPage
     * @param array $columns
     *
     * @return mixed
     */
    public function paginate($perPage = 15, $columns = array('*'))
    {
        return $this->model->where('created_by', '=', Auth::user()->id)->paginate($perPage, $columns);
    }

    /**
     * @param array $data
     *
     * @return mixed
     */
    public function create(Request $request)
    {
        return $this->model->create($request->all());
    }

    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     *
     * @return mixed
     */
    public function update(array $data, $id, $attribute="id")
    {
        return $this->model->where($attribute, '=', $id)->update($data);
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
        return $this->model->find($id, $columns);
    }

    /**
     * Find by criteria
     *
     * @param $attribute
     * @param $value
     * @param array $columns
     *
     * @return mixed
     */
    public function findBy($attribute, $value, $columns = array('*'))
    {
        return $this->model->where($attribute, '=', $value)->first($columns);
    }

    /**
     * Delete an item
     *
     * @param $id
     *
     * @return mixed
     */
    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    /**
     * Where condition
     *
     * @param $attribute
     * @param $value
     * @param string $operator
     *
     * @return mixed
     */
    final public function where($attribute, $value, $operator = '=')
    {
        return $this->model->where($attribute, $operator, $value);
    }

    /**
     * Gets the elements from the relationship provided
     *
     * @param $relationship
     *
     * @return mixed
     */
    final public function with($relationship = null)
    {
        if (!is_string($relationship)) {
            return $this->model;
        }

        return $this->model->with($relationship);
    }
}