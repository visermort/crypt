<?php
namespace visermort;


class Password
{
    private $user;
    private $passwordData; //GetPasswordData - класс


    //проверяем состояние глобальных переменных, и от этого все действия
    private function checkGlobals()
    {
        if (isset($_SESSION['key']) && $_SESSION['key']) {
            //в сессии есть ключ - проверяем его в базе - если он есть, имеем логин пользователя
            $this -> user = $this-> passwordData -> getUser('password', $_SESSION['key']);
        }
     //   echo 'checkGlobalsKey '.print_r($this -> user,true).'<br>';
        if ((!$this-> user) && isset($_POST['email']) && isset($_POST['password']) && $_POST['email'] && $_POST['password']) {
            //если не прошло и если есть данные email password в ПОСТ то логинимся, если получилось, то хеш пароля пишем в сессию
            //т.е вначале запрос, получили данные пользователя  - hash
            //затем проверка соответсвия пароля и hash
            $this -> user =  $this -> passwordData -> getUser('email', $_POST['email']);
       //     echo 'checkGlobalsLogin '.print_r($this -> user,true).'<br>';

            if ($this -> user['password'] && $this -> confirmPassword($this -> user['password'], $_POST['password'])) {
                $_SESSION['key'] = $this -> user['password'];
            } else {
                $this -> user = null;
                unset($_SESSION['key']);
            }
        }

        if (isset($_POST['logout']) && $_POST['logout']) {
            //в любом случае если в ПОСТ есть logout закрыаваем сессию
            $this -> user = 0;
            unset($_SESSION['key']);
            session_destroy();
        }
    }

    //вызываем  для создания нового пароля
    public static function hashPassword($password)
    {
        $salt = md5(uniqid('myPrefixForPassword', true));
        $salt = substr(strtr(base64_encode($salt), '+', '.'), 0, 22);
        return crypt($password, '$2a$08$' . $salt);
    }

    //проверка логина и хеша
    private function confirmPassword($hash, $password)
    {
        return crypt($password, $hash) === $hash;
    }

    public function login()
    {
        // print_r($this -> user);
        return $this -> user;
    }

    public function __construct( GetPasswordData $passwordData)
    {
        session_start();
        $this -> passwordData = $passwordData;


        $this -> checkGlobals();
    }

}
