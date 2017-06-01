<?php

	use \Firebase\JWT\JWT;

	// http://localhost/meine/Redaxo/01/redaxo/index.php?page=structure&rex-api-call=restfull_api
	class rex_api_restfull_api extends rex_api_function {

		protected $published = true;

		public function execute() {
			$message = $this->getTest();

			$result = new rex_api_result(true, $message);
			return $result;
		}

		public function getTest() {
			// $data = [
			// 	'time' => time(),
			// 	'data' => [
			// 		1 => 'Hallo',
			// 		2 => 'Servus',
			// 		3 => 'Test',
			// 	]
			// ];
			// header('Content-Type: application/json');
			// echo json_encode($data);
			// exit;


			// $key = "example_key";
			// $token = array(
			//     "iss" => "http://example.org",
			//     "aud" => "http://example.com",
			//     "iat" => 1356999524,
			//     "nbf" => 1357000000
			// );
			//

			$key 		= "example_key";
			$issuedAt   = date('U', time());
		    $notBefore  = date('U', $issuedAt + 10);
		    $expire  	= date('U', $issuedAt + 60);

			$userId 	= rex_ycom_auth::getUser()->getValue('id');
			$username 	= rex_ycom_auth::getUser()->getValue('name');

			$token = array(
			    "iss" => "http://example.org",
			    "aud" => "http://example.com",
			    "iat" => $issuedAt,
			    "nbf" => $issuedAt,
				'exp' => $expire,
				'data' => [
					'userId' => $userId,
					'userName' => $username
				]
			);

			$jwt = JWT::encode($token, $key);
			$decoded = JWT::decode($jwt, $key, array('HS256'));

			header('Content-Type: application/json');
			echo json_encode($jwt);
			// echo json_encode($decoded);
			exit;
		}

	}
