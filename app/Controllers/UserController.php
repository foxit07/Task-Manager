<?php
/**
 * Created by PhpStorm.
 * User: David Arakelyan
 * Email: rotokan2@gmail.com
 * Date: 23.11.2018
 * Time: 21:52
 */

namespace App\Controllers;
use Delight\Auth;


class UserController extends Controller
{

    public function login()
    {
        try {
             $this->auth->loginWithUsername($_POST['username'], $_POST['password']);
        }
        catch (\Delight\Auth\UnknownUsernameException $e) {

            flash()->error(['Неверное имя пользователя']);

        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            flash()->error(['Неверный пароль']);
        }

        catch (\Delight\Auth\TooManyRequestsException $e) {
            flash()->error(['Куда ломишься?!']);
        }

        return redirect('/');
    }

    public function logout()
    {
        $this->auth->logOut();
        return redirect('/');
    }
}