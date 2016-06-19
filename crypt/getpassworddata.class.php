<?php
namespace visermort;

abstract class GetPasswordData
{
    protected $dataSetting;


    protected function __construct($dataSetting)
    {
        $this -> dataSetting = $dataSetting;
    }
    public function getUser($key, $data)
    {
    }

}