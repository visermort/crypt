<?php

include "vendor/phpunit/phpunit/src/Framework/TestCase.php";
include "crypt/getpassworddatamysql.class.php";
include "crypt/getpassworddatajson.class.php";
include "crypt/password.class.php";



class PasswordTest extends PHPUnit_Framework_TestCase
{
    private $fileName = 'data/jsonfile.json';
    private $config = array (
        'host' => 'localhost',
        'base' => 'visermort',
        'user' => 'root',
        'password' => '',
        'charset' => 'utf8',
        'dbPrefix' => 'test_'
    );
    public function setUp()
    {
        //перед проведением тестов обнуляем данные

    }

//    //тест запись пользователя
    public function testUserJson()
    {
        //очистка данных
        $data = array();
        file_put_contents($this->fileName,json_encode($data));

        //тест не проходит -  сессии session_start(): Cannot send session cookie - headers already sent by
        //проведение тестов далее невозможно
//        $password = new visermort\Password ( $getPasswordDataJson =  new visermort\GetPasswordDataJson($this->fileName)) ;
//        $password = null;
//        $getPasswordDataJson = null;
    }




    public function tearDown()
    {

    }

}