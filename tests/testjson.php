<?php
include "vendor/phpunit/phpunit/src/Framework/TestCase.php";
include "crypt/getpassworddatajson.class.php";



class GetPasswordDataJsonTest extends PHPUnit_Framework_TestCase
{
    private $fileName = 'data/jsonfile.json';
    public function setUp()
    {
        //перед проведением тестов обнуляем данные

    }

        //тест запись пользователя
    public function testWriteUser()
    {
        $data = array();
        file_put_contents($this->fileName,json_encode($data));

        $getPasswordDataJson = new visermort\GetPasswordDataJson($this->fileName);
        $getPasswordDataJson->writeUser('someName','someLastName','some@email.com','1234567890','administrators');
        //считаем количество записей в базе
        $data =json_decode(file_get_contents($this->fileName), true);
        $count1=count($data);
        $getPasswordDataJson->writeUser('someName','someLastName','some@email.com','1234567890','administrators');
        //считаем количество записей в базе после записи такого же юсера
        $data =json_decode(file_get_contents($this->fileName), true);
        $count2 = count($data);
        $this->assertEquals($count1,$count2);
        $getPasswordDataJson->writeUser('someName2','someLastName2','some_2@email.com','123456789033','administrators');
        //считаем количество записей в базе после записи такого другого юсера
        $data =json_decode(file_get_contents($this->fileName), true);
        $count2 = count($data);
        $this->assertEquals($count1+1,$count2);

        $getPasswordDataJson = null;
    }

    //тест выдача пользователя по ключу
    public function testReadUser()
    {
        $getPasswordDataJson = new visermort\GetPasswordDataJson($this->fileName);
        //поиск по email
        $user = $getPasswordDataJson->getUser('email','some@email.com');
        $this->assertEquals('someLastName',$user['lastName']);
        $this->assertEquals('someName',$user['firstName']);
        $this->assertEquals('some@email.com',$user['email']);
        $this->assertEquals('1234567890',$user['password']);
        $this->assertEquals('administrators',$user['group']);
        //поиск по password
        $user = $getPasswordDataJson->getUser('password','123456789033');
        $this->assertEquals('someLastName2',$user['lastName']);
        $this->assertEquals('someName2',$user['firstName']);
        $this->assertEquals('some_2@email.com',$user['email']);
        $this->assertEquals('123456789033',$user['password']);
        $this->assertEquals('administrators',$user['group']);

        $getPasswordDataJson = null;

    }


    public function tearDown()
    {

    }

}

