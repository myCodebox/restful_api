<?php

	use \Firebase\JWT\JWT;

	// BACKEND 	rex_url::currentBackendPage([ 'rex-api-call'=>'restfull_api' ], false);
	// FRONTEND http://localhost/meine/Redaxo/01/?rex-api-call=restfull_api
	class rex_api_restfull_api extends rex_api_function {

		// open the frontend
		protected $published 		= true;

		// CONFIG Data
		protected $jwt_active 		= false;
		protected $jwt_secretKey 	= null;
		protected $jwt_algorithm 	= 'HS256';

		// USER Data
		protected $user_id 			= null;
		protected $user_type 		= null;


		public function execute()
		{
			$this->setConfig();

			$func = rex_get('func', 'string');

			$message = '';

			if( $func == 'ext' ) {
				$message = $this->isOK();
			} else {
				$message = $this->isNOK();
			}

			// if( $this->setUser() && rex_request::requestMethod() == 'post' && $this->jwt_active ) {
			// 	$message = $this->getJWT();
			// } else {
			// 	$message = $this->getJWTerror();
			// }

			$result = new rex_api_result(true, $message);
			return $result;
		}


		private function isOK()
		{
			$btoa = rex_request('hash', 'string');
			$hash = base64_decode($btoa);
			header('Content-Type: application/json');
			header('HTTP/1.1 200 OK');
			echo json_encode(['OK', $hash]);
			exit;
		}
		private function isNOK()
		{
			header('Content-Type: application/json');
			header('HTTP/1.1 401 Unauthorized');
			echo json_encode(['NOK']);
			exit;
		}


		private function setConfig() {
			$addon = rex_addon::get('restful_api');
			$this->jwt_active 		= $addon->getConfig('restful_api_active');
			$this->jwt_secretKey 	= $addon->getConfig('restful_api_secretKey');
			$this->jwt_algorithm 	= $addon->getConfig('restful_api_algorithm');
		}


		private function setUser()
		{
			$user = (rex::isBackend()) ? rex::getUser() : rex_ycom_auth::getUser();
			if( $user ) {
				$this->user_id = $user->getId();
				$this->user_type = (rex::isBackend()) ? 'backend' : 'frontend';
			}
			return $user;
		}


		public function getJWT()
		{
			$tokenId    = base64_encode(mcrypt_create_iv(32));
			$issuedAt   = time();
			$notBefore  = $issuedAt + 10;  		// Adding 10 seconds
			$expire     = $notBefore + 60; 		// Adding 60 seconds
			$serverName = rex::getServer(); 	// Adding the server URL.

			$data = [
				'iat'  => $issuedAt,         			// Issued at: time when the token was generated
				'jti'  => $tokenId,          			// Json Token Id: an unique identifier for the token
				'iss'  => $serverName,       			// Issuer
				'nbf'  => $notBefore,        			// Not before
				'exp'  => $expire,           			// Expire
				'data' => [                  			// Data related to the signer user
					'user_id' 	=> $this->user_id, 		// userid from the users table
					'user_type' => $this->user_type, 	// User name
				]
			];

			$secretKey = base64_decode($this->jwt_secretKey);
            $algorithm = $this->jwt_algorithm;
            $jwt = JWT::encode(
                $data,      //Data to be encoded in the JWT
                $secretKey, // The signing key
                $algorithm  // Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
            );

            $unencodedArray = [
				'code' 		=> 200,
				'status' 	=> 'success',
				'message' 	=> 'The request has succeeded.',
				'jwt' 		=> $jwt
			];

			header('Content-Type: application/json');
			header('HTTP/1.1 200 OK');
			echo json_encode($unencodedArray);
			exit;
		}


		public function getJWTerror()
		{
			$data = [
				'code' => 401,
				'status' => 'fail',
				'message' => 'The request has not been applied because it lacks valid authentication credentials for the target resource.',
			];

			header('Content-Type: application/json');
			header('HTTP/1.1 401 Unauthorized');
			echo json_encode($data);
			exit;
		}


	}
