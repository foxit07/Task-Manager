<?php
/**
 * Created by PhpStorm.
 * User: David Arakelyan
 * Email: rotokan2@gmail.com
 * Date: 22.11.2018
 * Time: 16:28
 */

namespace App\Controllers;

use App\Services\Database;
use App\Services\ImageManager;
use Delight\Auth\Auth;
use League\Plates\Engine;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator as v;
use JasonGrimes\Paginator;


class TaskController extends Controller
{
    const SHOW = 1;
    const USER = 0;
    const ADMIN = 1;

    private $imageManager;

    public function __construct(ImageManager $imageManager)
    {
        parent::__construct();
        $this->imageManager = $imageManager;
    }

    /**
     *
     */
    public function index()
    {

        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $perPage = 3;
        $tasks = $this->database->getPaginatedFrom('tasks', 'status', TaskController::SHOW, $page, $perPage);

        foreach ($tasks as $key => $task){
            $tasks[$key]['user'] = $this->database->find('users', $task['user_id']);
        }


        $paginator = paginate(
            $this->database->getCount('tasks', 'status', 1),
            $page,
            $perPage,
            '/?page=(:num)'
        );

        echo $this->view->render('task/index', [
            'tasks'   =>  $tasks,
            'paginator'    =>  $paginator,
            'showCount' => $paginator->getCurrentPageLastItem(),
            'totalCount' => $paginator->getTotalItems()
        ]);
    }

    public function create()
    {
        echo $this->view->render('task/create');
    }

    public function store()
    {

        $validator = v::key('username', v::stringType()->notEmpty())
            ->key('email', v::email()->notEmpty())
            ->key('text', v::stringType()->notEmpty())
            ->keyNested('image.tmp_name', v::image());

        $this->validate($validator);

        $image = $this->imageManager->uploadImage($_FILES['image']);
        $data = [
            "username" => $_POST['username'],
            "email" => $_POST['email'],
        ];

       $user = $this->isUser($data);

       if($user === false){
           flash()->warning('Пользователь с таким email существует');
           return back();
       };

        $data = [
            "text" =>  $_POST['text'],
            "status" => TaskController::SHOW,
            "user_id" => $user,
            "done" => 0,
            "img_path" =>  $image
        ];

       $this->database->create('tasks', $data);
       flash()->success(['Задача добавлена']);
       return back();
    }

    /**
     * @param $id
     */
    public function edit($id)
    {

        $task = $this->database->find('tasks', $id);
        $task['user'] = $this->database->find('users', $task['user_id']);

        echo $this->view->render('task/edit', ['task' =>  $task]);
    }

    public function update($id)
    {
        $data = null;
        $validator = v::key('username', v::stringType()->notEmpty())
            ->key('email', v::email()->notEmpty())
            ->key('text', v::stringType()->notEmpty());
        $this->validate($validator);
        $data = [
            "username" => $_POST['username'],
            "email" => $_POST['email'],
        ];

        $user = $this->isUser($data);

        if($user === false){
            flash()->warning('Пользователь с таким email существует');
            return back();
        };



        $data = [
            "text" =>  $_POST['text'],
            "user_id" => $user,
            "status" => TaskController::SHOW,
            "done" => isset($_POST['done']) ? 1 : 0
        ];

       $this->database->update('tasks', $id, $data);

        return redirect('/');
    }

    public function destroy($id)
    {
        $task = $this->database->find('tasks', $id);
        $this->imageManager->deleteImage($task['img_path']);
        $this->database->delete('tasks', $id);

        return back();
    }

    private function validate($validator)
    {
        try {
            $validator->assert(array_merge($_POST, $_FILES));

        } catch (ValidationException $exception) {
            $exception->findMessages($this->getMessages());
            flash()->error($exception->getMessages());

            return back();
        }
    }

    private function getMessages()
    {
        return [
            'title' => 'Введите название',
            'description'   =>  'Введите описание',
            'category_id'   =>  'Выберите категорию',
            'image' =>  'Неверный формат картинки'
        ];
    }

    private function isUser($data)
    {
        $user = $this->database->whereAll('users', 'email', $data['email']);
        if ($user && $data['username'] != $user[0]['username']){
            return false;
        }elseif (!$user){
           $user = $this->database->create('users', $data);
           return $user;
        }
        return $user[0]['id'];
    }

}