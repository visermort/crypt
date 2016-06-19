###Crypt

#Модуль авторизации.

Предоставляет авторизацию пользователя, с сохранением данных в Mysql или в файле формата JSON.
Возможно расширение функционала для храниния данных в других форматах.

##Использование с Mysql

#Добавление пользователя
        include "xxxx/crypt/password.class.php";
        include "xxxx/crypt/getpassworddatamysql.class.php";
        $dbConfig = array (
			'host' => 'hostName',
			'base' => 'baseName',
			'user' => 'user',
			'password' => '',
			'charset' => 'utf8',
			'dbPrefix' => 'dbprefix_',
		);

        $getPasswordDataMysql = new visermort\GetPasswordDataMysql($dbConfig);
        $getPasswordDataMysql->writeUser(
			'someName',
			'someLastName',
			'some@email.com',
			visermort\Password::hashPassword(somepassword);
			'administrators');



#Авторизация

		Разметите код в начале скрипта
		Код проверит сессию или наличие переменных  $_POST['email'] $_POST['password']  
		вернёт - авторизован ли пользователь ,если да, то его данные, данные авторизации будут храниться в сессии
		при наличии $_POST['logout'] сеанс авторизации будет завершён

        include "xxxx/crypt/password.class.php";
        include "xxxx/crypt/getpassworddatamysql.class.php";


        $dbConfig = array (
			'host' => 'hostName',
			'base' => 'baseName',
			'user' => 'user',
			'password' => '',
			'charset' => 'utf8',
			'dbPrefix' => 'dbprefix_',
		);


        $login = new visermort\Password(new visermort\GetPasswordDataMysql($dbConfig));

        $data = $login -> login();

		возвращаемый результат
		$data = array(
			'firstName' => someName,
			'lastName' => someLastName
			'email' => some@email.com,
			'password' => hash от somepassword,
			'group' => administrators,
		);
		или null
		
#Формат базы данных

		CREATE TABLE IF NOT EXISTS `test_group` (
		  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		  `title` varchar(100) DEFAULT NULL,
		) 

		CREATE TABLE IF NOT EXISTS `test_users` (
		  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		  `id_group` int(11) NOT NULL,
		  `name` varchar(50) DEFAULT NULL,
		  `lastname` varchar(50) DEFAULT NULL,
		  `email` varchar(50) DEFAULT NULL,
		  `password` varchar(100) DEFAULT NULL
		) 
		
		ALTER TABLE `test_users`
		  ADD CONSTRAINT `test_fk_users_id_group` FOREIGN KEY (`id_group`) REFERENCES `test_group` (`id`);

##Использование с JSON

#Добавление пользователя
        include "xxxx/crypt/password.class.php";
		include "crypt/getpassworddatajson.class.php";
        $fileName = 'data/jsonfile.json';

        $getPasswordDataJson = new visermort\GetPasswordDataJson($fileName);
        $getPasswordDataMysql->writeUser(
			'someName',
			'someLastName',
			'some@email.com',
			visermort\Password::hashPassword(somepassword);
			'administrators');



#Авторизация

		Разметите код в начале скрипта
		Код проверит сессию или наличие переменных  $_POST['email'] $_POST['password']  
		вернёт - авторизован ли пользователь ,если да, то его данные, данные авторизации будут храниться в сессии
		при наличии $_POST['logout'] сеанс авторизации будет завершён

        include "xxxx/crypt/password.class.php";
        include "xxxx/crypt/getpassworddatajson.class.php";


        $fileName = 'data/jsonfile.json';


        $login = new visermort\Password(new visermort\GetPasswordDataJson($fileName));

        $data = $login -> login();

		возвращаемый результат
		$data = array(
			'firstName' => someName,
			'lastName' => someLastName
			'email' => some@email.com,
			'password' => hash от somepassword,
			'group' => administrators,
		);
		или null

