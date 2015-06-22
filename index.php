<?php 

//API REST TEST PHALCON
	$loader = new \Phalcon\Loader();
	$loader->registerDirs(array(
	    'models',
	    'controllers'
	))->register();

	include("db/db.php");
	
	$app = new \Phalcon\Mvc\Micro($di);
	
	$app->get('/', function() {
		echo "Users API REST";
	});

	$app->get("/usuarios",  function() use ($app) {
		$phql = "SELECT * FROM Users";
	    $users = $app->modelsManager->executeQuery($phql);
	    $usuarios=array();
		
		foreach($users as $user){
			$usuarios[]=array(
				"id" => $user->id,
				"username" => $user->username,
				"email" => $user->email
				);
		}

		echo json_encode($usuarios,JSON_PRETTY_PRINT); 
	});


	$app->get("/usuarios/filtrar/{email}",  function($email) use ($app) {
		$phql = "SELECT * FROM Users WHERE email LIKE '%".$email."%'";
	    $users = $app->modelsManager->executeQuery($phql);
	    $usuarios=array();
		
		foreach($users as $user){
			$usuarios[]=array(
				"id" => $user->id,
				"username" => $user->username,
				"email" => $user->email
				);
		}

		echo json_encode($usuarios,JSON_PRETTY_PRINT); 

	});


	$app->post('/usuarios/nuevo', function() use ($app) {

	    $user = json_decode($app->request->getRawBody());
	    $phql = "INSERT INTO Users (username, type, email,password) VALUES (:username:, :type:, :email:,:password:)";

	    $status = $app->modelsManager->executeQuery($phql, array(
	        'username' => $user->username,
	        'type' => $user->type,
	        'email' => $user->email,
	        'password' => sha1($user->password)
	    ));
	    if ($status->success()) {

	        $app->response->setStatusCode(201, "Created")->sendHeaders();

	        $response = array('status' => 'OK', 'data' => $user);

	    } else {

	        $app->response->setStatusCode(409, "Conflict")->sendHeaders();

	        $errors = array();
	        foreach ($status->getMessages() as $message) {
	            $errors[] = $message->getMessage();
	        }

	        $response = array('status' => 'ERROR', 'messages' => $errors);

	    }

	    echo json_encode($response,JSON_PRETTY_PRINT);

	});

	$app->put('/usuarios/modificar/{id}', function($id) use ($app) {

    	$user = json_decode($app->request->getRawBody());
 	    $phql = "UPDATE Users SET username=:username:, type=:type:, email= :email:, password=:password: WHERE id=:id:";

	    $status = $app->modelsManager->executeQuery($phql, array(
	    	'id' => $id,
	        'username' => $user->username,
	        'type' => $user->type,
	        'email' => $user->email,
	        'password' => sha1($user->password)
	    ));
	    if ($status->success()) {

	        $app->response->setStatusCode(201, "Created")->sendHeaders();

	        $response = array('status' => 'OK', 'data' => $user);

	    } else {

	        $app->response->setStatusCode(409, "Conflict")->sendHeaders();

	        $errors = array();
	        foreach ($status->getMessages() as $message) {
	            $errors[] = $message->getMessage();
	        }

	        $response = array('status' => 'ERROR', 'messages' => $errors);

	    }

	    echo json_encode($response,JSON_PRETTY_PRINT);
	});

	$app->delete('/usuarios/eliminar/{id:[0-9]+}', function($id) use ($app) {

	    $phql = "DELETE FROM Users WHERE id = :id:";
	    $status = $app->modelsManager->executeQuery($phql, array(
	        'id' => $id
	    ));
	    if ($status->success() == true) {

	        $response = array('status' => 'OK');

	    } else {

	        //Change the HTTP status
	        $this->response->setStatusCode(409, "Conflict")->sendHeaders();

	        $errors = array();
	        foreach ($status->getMessages() as $message) {
	            $errors[] = $message->getMessage();
	        }

	        $response = array('status' => 'ERROR', 'messages' => $errors);

	    }

	    echo json_encode($response);
	});
	
	$app->handle();

?>