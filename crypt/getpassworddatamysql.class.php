<?php
namespace visermort;

include_once "getpassworddata.class.php";

class GetPasswordDataMysql extends GetPasswordData
{
    private $connectionString;
    private $attributes;


    //два метода только для тестирования - очицение таблицы
    public function deleteUsers()
    {
        $pdo = $this->getConnection();
        if (!$pdo) {
            return null;
        }
        try {
            $sql ="delete from `" . $this->dataSetting['dbPrefix'] . "users`";
            $pdo->query($sql);
            $pdo = null;
        } catch (\PDOException $e) {
            $pdo = null;
            return null;
            echo $e->getMessage();
        }

    }
    //количество записей в таблице
    public function usersCount()
    {
        $pdo = $this->getConnection();
        if (!$pdo) {
            return null;
        }
        try {
            $sql ="select count(*)as count from `" . $this->dataSetting['dbPrefix'] . "users`";
            $smtp=$pdo->query($sql);
            return $smtp -> fetch()['count'];
        } catch (\PDOException $e) {
            $pdo = null;
            return null;
            echo $e->getMessage();
        }

    }

    private function recExists($pdo,$table,$key,$value){
        $sql = "select id from `" . $this->dataSetting['dbPrefix'] .$table. "` where  ".$key."='".$value."' ";
        // echo $sql;
        $smtp = $pdo->query($sql);
        if ($rec=$smtp->fetch()) {
            // echo 'id_group='.$rec['id'];
            return $rec['id'];
        } else {
            return null;
        }

    }


    public function __construct($dataSetting)
    {
        ini_set('error_reporting', E_ALL);

        $this -> dataSetting = $dataSetting;
        // Задаём атрибуты подключения
        $this -> connectionString = "mysql:host=".$this->dataSetting['host'].";dbname=".
            $this->dataSetting['base'].";charset=".$this->dataSetting['charset'];
        $this -> attributes = array(
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION, // вывод ошибок
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC // PDO::FETCH_NUM // тип вывода данных
        );
    }

    private function getConnection()
    {

        try {
            return new \PDO(
                $this -> connectionString,
                $this->dataSetting['user'],
                $this->dataSetting['password'],
                $this->attributes
            );

        } catch (\PDOException $e) {
            return null;
        }
    }

    public function getUser($key, $data)
    {
        $pdo = $this->getConnection();
        if (!$pdo) {
            return null;
        }
        try {
            //если нет таблицы, то всё - авторизация прекращается
            $sql = "SELECT table_name FROM information_schema.tables where table_schema= '".
                $this->dataSetting['base']."' and table_name = '".$this->dataSetting['dbPrefix']."users'";
            $smtp = $pdo->query($sql);
            if ($smtp -> fetch()) {
                $sql = "select `name` , `lastname`, `email`, `title`, `password`  from `" . $this->dataSetting['dbPrefix'] .
                    "users` u join `" .  $this->dataSetting['dbPrefix'] .
                    "group` g on u.`id_group`=g.`id` where ".$key." =:data ";
                //  echo $sql.' '.$data.'<br>';
                $smtp = $pdo->prepare($sql);
                $smtp->bindValue(':data', $data, \PDO::PARAM_STR);
                $smtp -> execute();
                if ($arr = $smtp->fetch()) {
                    //      echo 'arr'.print_r($arr,true);
                    return array(
                        'firstName' => $arr['name'],
                        'email' => $arr['email'],
                        'group' => $arr['title'],
                        'password' => $arr['password'],
                        'lastName' => $arr['lastname']
                    );
                } else {
                    $pdo = null;
                    return null;
                }
            } else {
                $pdo = null;
                return null;
            }
        } catch (\PDOException $e) {
            $pdo = null;
            return null;
        }
        $pdo = null;
    }

    public function writeUser($firstName,$lastName,$email,$password,$group)
    {
        $pdo = $this->getConnection();
        if (!$pdo) {
            return null;
        }
        try {
            //если нет таблицы, то всё - авторизация прекращается
            $sql = "SELECT table_name FROM information_schema.tables where table_schema= '" .
                $this->dataSetting['base'] . "' and table_name = '" . $this->dataSetting['dbPrefix'] . "users'";
            //echo $sql;
            $smtp = $pdo->query($sql);
            if ($smtp->fetch()) {
                if (!$id_group=$this->recExists($pdo,'group','title',$group)){
                    $sql = "insert into `" . $this->dataSetting['dbPrefix'] . "group` set `title`=:title ";
                    //  echo $sql,$group;
                    $smtp = $pdo->prepare($sql);
                    $smtp->bindValue(':title', $group, \PDO::PARAM_STR);
                    $smtp->execute();
                    $id_group = $pdo->lastInsertId();
                }
                if ($this->recExists($pdo,'users','email',$email)) {
                    $sql = "update `" . $this->dataSetting['dbPrefix'] . "users` set " .
                        " `name`=:name, `lastname`=:lastname,`password`=:password where `email`=:email `id_group`=:id_group ";
                } else {
                    $sql = "insert into `" . $this->dataSetting['dbPrefix'] . "users` set " .
                        " `email`=:email, `name`=:name, `lastname`=:lastname,`password`=:password, `id_group`=:id_group ";
                }
                // echo $sql;
                $smtp = $pdo->prepare($sql);
                $smtp->bindValue(':email', $email, \PDO::PARAM_STR);
                $smtp->bindValue(':name', $firstName, \PDO::PARAM_STR);
                $smtp->bindValue(':lastname', $lastName, \PDO::PARAM_STR);
                $smtp->bindValue(':password', $password, \PDO::PARAM_STR);
                $smtp->bindValue(':id_group', $id_group, \PDO::PARAM_INT);
                //  echo $firstName,$lastName,$email,$password,$id_group;
                $smtp->execute();
            } else {
                $pdo = null;
                return null;
            }

        } catch (\PDOException $e) {
            $pdo = null;
            return null;
            echo $e->getMessage();
        }
        $pdo = null;

    }
}