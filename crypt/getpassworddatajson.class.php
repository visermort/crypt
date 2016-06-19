<?php
namespace visermort;

include_once "getpassworddata.class.php";

class GetPasswordDataJson extends GetPasswordData
{
    private $jsonData;
    private $fileName;

    private function getData()
    {
        $this->jsonData =  json_decode(file_get_contents($this->fileName), true);
    }
    private function setData()
    {
        file_put_contents($this->fileName,json_encode($this->jsonData));
    }

    public function __construct($fileName)
    {
        $this->fileName = $fileName;
        if (file_exists($fileName)) {
            $this->getData();
        } else $this->jsonData = array();
    }

    public function writeUser($firstName,$lastName,$email,$password,$group){
        //вначале ищем по массиву такую же запись - ключ - email
        if (count($this->jsonData)) {
            foreach ($this->jsonData as $key => $rec){
                if ($rec['email'] == $email) {
                    $this->jsonData[$key] = array (
                        'firstName' => $firstName,
                        'lastName' => $lastName,
                        'password' => $password,
                        'group' => $group,
                        'email' => $email

                    );
                    $this->setData();
                    return;//если нашли, то записали  и выход
                }
            }
        } else {
            $this->jsonData = array();
        }
        //если не нашли, то новая запись, и записали
        array_push($this->jsonData, array(
            'firstName' => $firstName,
            'lastName' => $lastName,
            'password' => $password,
            'group' => $group,
            'email' => $email
        ));
        $this->setData();
    }


    public function getUser($key, $data)
    {
        foreach ($this->jsonData as $rec){
            if ($rec[$key]==$data) {
                return array (
                    'password' => $rec['password'],
                    'email' => $rec['email'],
                    'firstName' => $rec['firstName'],
                    'lastName' => $rec['lastName'],
                    'group' => $rec['group']
                );
            }
        }
        return null;
    }

}