<?php
/**
 * Created by PhpStorm.
 * User: David Arakelyan
 * Email: rotokan2@gmail.com
 * Date: 22.11.2018
 * Time: 16:05
 */

namespace App\Controllers;
use App\Services\Database;
use League\Plates\Engine;
use Tuupola\Middleware\HttpBasicAuthentication;
use App\Services\Roles;
use Delight\Auth\Auth;
use PDO;

class Controller
{
    protected $auth;
    protected $view;
    protected $database;



    public function __construct()
    {
        $this->auth = components(Auth::class);
        $this->view = components(Engine::class);
        $this->database = components(Database::class);
    }


}