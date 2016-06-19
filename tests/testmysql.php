<?php

include "vendor/phpunit/phpunit/src/Framework/TestCase.php";
include "crypt/getpassworddatamysql.class.php";



class GetPasswordDataMysqlTest  extends PHPUnit_Framework_TestCase
{
    private $config = array (
        'host' => 'localhost',
        'base' => 'visermort',
        'user' => 'root',
        'password' => '',
        'charset' => 'utf8',
        'dbPrefix' => 'test_'
    );

    //тест запись пользователя
    public function testWriteUser()
    {
        $data = array();

        $getPasswordDataMysql = new visermort\GetPasswordDataMysql($this->config);

        $getPasswordDataMysql->deleteUsers();
        $getPasswordDataMysql->writeUser('someName','someLastName','some@email.com','1234567890','administrators');
        //считаем количество записей в базе
        $count1 = $getPasswordDataMysql->usersCount();
        $this->assertEquals($count1,1);
        $getPasswordDataMysql->writeUser('someName','someLastName','some@email.com','1234567890','administrators');
        //считаем количество записей в базе после записи такого же юсера
        $count1 = $getPasswordDataMysql->usersCount();
        $this->assertEquals($count1,1);

         $getPasswordDataMysql->writeUser('someName2','someLastName2','some_2@email.com','123456789033','administrators');
        //считаем количество записей в базе после записи такого другого юсера
        $count2= $getPasswordDataMysql->usersCount();
        $this->assertEquals($count2,2);

        $getPasswordDataJson = null;
    }

    //тест выдача пользователя по ключу
    public function testReadUser()
    {
        $getPasswordDataMysql = new visermort\GetPasswordDataMysql($this->config);
        //поиск по email
        $user = $getPasswordDataMysql->getUser('email','some@email.com');
        $this->assertEquals('someLastName',$user['lastName']);
        $this->assertEquals('someName',$user['firstName']);
        $this->assertEquals('some@email.com',$user['email']);
        $this->assertEquals('1234567890',$user['password']);
        $this->assertEquals('administrators',$user['group']);
        //поиск по password
        $user = $getPasswordDataMysql->getUser('password','123456789033');
        $this->assertEquals('someLastName2',$user['lastName']);
        $this->assertEquals('someName2',$user['firstName']);
        $this->assertEquals('some_2@email.com',$user['email']);
        $this->assertEquals('123456789033',$user['password']);
        $this->assertEquals('administrators',$user['group']);

        $getPasswordDataMysql = null;

    }


}