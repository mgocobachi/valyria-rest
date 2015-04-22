<?php
use Mockery as m;
use Illuminate\Http\Request;
use Valyria\Models\Consumer;
use Valyria\Http\Controllers\UserController;

class UserControllerTest extends TestCase
{
    protected $repository;
    protected $controller;

    public function setUp()
    {
        parent::setUp();

        $this->repository = m::mock('Valyria\Models\Repositories\User');
        $this->controller = new UserController($this->repository);
    }

    public function tearDown()
    {
        m::close();
    }

    public function testStoreInvalid()
    {
        $expected = [
            'stat' => 'error',
            'result' => [
                'password' => 'The password field is required.',
                'firstname' => 'The firstname field is required.',
                'lastname' => 'The lastname field is required.',
            ]
        ];

        $request = Request::create('/foo', 'POST', [
            'email' => 'foo@bar.com',
        ]);

        $result = $this->controller->store($request);
        $this->assertJson(json_encode($expected), $result);
    }

    /**
     * Test insert users
     */
    public function testStore()
    {
        $expected = [
            'stat' => 'ok',
            'result' => [
                'id' => 1,
                'email'     => 'foo@bar.com',
                'password'  => 'foobarfoo',
                'firstname' => 'Foo',
                'lastname'  => 'Bar',
            ]
        ];

        $repository = m::mock('Valyria\Models\Repositories\User');
        $repository->shouldReceive('create')->andReturn($expected['result']);

        App::instance('Valyria\Models\Repositories\User', $repository);

        $request = Request::create('/foo', 'POST', [
            'email'     => 'foo@bar.com',
            'password'  => 'foobarfoo',
            'firstname' => 'Foo',
            'lastname'  => 'Bar',
        ]);

        $controller = new UserController($repository);
        $result = $controller->store($request);
        $this->assertJson(json_encode($expected), $result);
    }

    /**
     * Delete user and denied request
     */
    public function testDestroyDenied()
    {
        $expected = '{"stat":"error","result":{"id":"Permission denied"}}';

        $this->be(Consumer::find(1));

        $repository = m::mock('Valyria\Models\Repositories\User');
        App::instance('Valyria\Models\Repositories\User', $repository);

        $request = Request::create('/foo', 'DELETE', [
            'id' => 1,
        ]);

        $controller = new UserController($repository);
        $result     = $controller->destroy($request, 1);

        $this->assertJson($expected, $result);
    }

    public function testDestroy()
    {
        $expected = '{"stat":"error","result":{"id":"Permission denied"}}';

        $this->be(Consumer::find(1));

        $model = m::mock('Valyria\Models\User');
        $model->shouldReceive('find')->andReturn(['id' => 1, 'email' => 'foo"bar.com']);

        $repository = m::mock('Valyria\Models\Repositories\User');
        App::instance('Valyria\Models\Repositories\User', $repository);

        $request = Request::create('/foo', 'DELETE', [
            'id' => 1,
        ]);

        $controller = new UserController($repository);
        $result = $controller->destroy($request, 1);

        $this->assertJson($expected, $result);
    }
}