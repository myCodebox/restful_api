<?php

	use \Firebase\JWT\JWT;

	// BACKEND 	rex_url::currentBackendPage([ 'rex-api-call'=>'restfull_api' ], false);
	// FRONTEND http://localhost/meine/Redaxo/01/?rex-api-call=restfull_api
	class rex_api_restfull_api extends rex_api_function {

		protected $published = true;

		public function execute() {

			$ycom_user = (rex::isBackend()) ? rex::getUser() : rex_ycom_auth::getUser();
			// $ycom_user = rex_ycom_auth::getUser();
			if ($ycom_user) {
				$message = $this->getTest($ycom_user);
			} else {
				$message = $this->getError();
			}

			$result = new rex_api_result(true, $message);
			return $result;
		}

		public function getTest($ycom_user) {
			$key 		= "example_key";
			$issuedAt   = date('U', time());
		    $notBefore  = date('U', $issuedAt + 10);
		    $expire  	= date('U', $issuedAt + 60);

			$userId 	= $ycom_user->getValue('id');
			$username 	= (rex::isBackend()) ? $ycom_user->getValue('login') : $ycom_user->getValue('name');

			$token = array(
			    "iss" => "http://example.org",
			    "aud" => "http://example.com",
			    "iat" => $issuedAt,
			    "nbf" => $issuedAt,
				'exp' => $expire,
				'data' => [
					'userId' 	=> $userId,
					'userName' 	=> $username
				]
			);

			$jwt 		= JWT::encode($token, $key);
			$decoded 	= JWT::decode($jwt, $key, array('HS256'));

			header('Content-Type: application/json');
			header('HTTP/1.1 200 OK');
			echo json_encode($jwt);
			// echo json_encode($decoded);
			exit;
		}

		public function getError() {
			header('Content-Type: application/json');
			header('HTTP/1.1 401 Unauthorized');
			exit;
		}

	}
