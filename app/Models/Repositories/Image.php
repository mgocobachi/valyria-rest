<?php
namespace Valyria\Models\Repositories;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Container\Container;
use Valyria\Models\Repositories\Eloquent\Repository;
use Valyria\Models\User as UserModel;

class Image extends Repository
{
    protected $storage = null;

    /**
     * Constructor
     *
     * @param Container $app
     */
    public function __construct(Container $app)
    {
        $this->storage = storage_path('app/assets/images');

        parent::__construct($app);
    }

    /**
     * Create a new image
     *
     * @param Request $request
     *
     * @throws \Exception
     * @return array
     */
    public function create(Request $request)
    {
        if (!extension_loaded('GD')) {
            throw new \Exception('The GD library has been not loaded');
        }

        $file   = $request->file('file');
        $user   = UserModel::find($request->route('users'));

        // TODO: throwing exception if the user does not exists

        $entity = $this->model;

        $entity->created_by = Auth::user()->id;

        $entity->path  = $this->generatePath($file);
        $entity->thumb = $this->generateThumb($entity->path, $file->getClientMimeType());
        $entity->name  = str_replace('.' . $file->getClientOriginalExtension(), '', $file->getClientOriginalName());
        $entity->type  = $file->getClientMimeType();
        $entity->size  = $file->getClientSize();

        $user->images()->save($entity);

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
        return $this->with('user')->with('comments')->find($id, $columns);
    }

    /**
     * Generate the path to storage the image
     *
     * @param object $file
     *
     * @return string
     */
    protected function generatePath($file)
    {
        $filename = $file->getClientOriginalName();

        $path = $this->storage . DIRECTORY_SEPARATOR
                . Auth::user()->id . DIRECTORY_SEPARATOR
                . md5($filename . Auth::user()->id . time()) . DIRECTORY_SEPARATOR;

        @mkdir($path, 0777, true);
        $file->move($path, $filename);

        return $path . $filename;
    }

    /**
     * Generates the thumb of the image
     *
     * @param string  $filename
     * @param string  $type
     * @param integer $size
     *
     * @return string
     */
    protected function generateThumb($filename, $type = 'image/jpeg', $size = 128)
    {
        $name = basename($filename);
        $path = realpath(dirname($filename)) . DIRECTORY_SEPARATOR . 'thumb' . DIRECTORY_SEPARATOR . $name;
        @mkdir(dirname($path), 0777, true);

        if ($type == 'image/jpeg') {
            $source = imagecreatefromjpeg($filename);
        } else if ($type == 'image/png') {
            $source = imagecreatefrompng($filename);
        } else {
            @copy($filename, $path);

            return $path;
        }

        $width      = imagesx($source);
        $height     = imagesy($source);
        $new_height = floor($height * ($size / $width));
        $dest       = imagecreatetruecolor($size, $new_height);

        @imagecopyresized($dest, $source, 0, 0, 0, 0, $size, $new_height, $width, $height);
        @imagejpeg($dest, $path);
        @imagedestroy($dest);

        return $path;
    }
}