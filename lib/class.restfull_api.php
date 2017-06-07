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
			$message = '';

			// set the config from redaxo
			$this->setConfig();

			// test request
			$message = $this->isRequest();

			// result
			$result = new rex_api_result(true, $message);
			return $result;
		}


		private function isRequest() {
			$func = rex_request('func', 'string');

			if( $func == 'getauth') {
				$hash_b64	= rex_request('hash', 'string');
				$json_str 	= base64_decode($hash_b64);
				$json_arr 	= json_decode($json_str, true);
				if( is_array($json_arr) && count($json_arr) > 0 ) {
					$this->testUser($json_arr);
				} else {
					$this->makeRequest(400);
				}
			}

			if( $func == 'getdata') {

				$jwt = $this->getBearerToken();
				if ($jwt) {
					try {
						$secretKey = base64_decode($this->jwt_secretKey);
						$algorithm = $this->jwt_algorithm;
						JWT::$leeway = 60; // $leeway in seconds
						$decoded = JWT::decode(
							$jwt,
							$secretKey,
							array($algorithm)
						);

						$asset = base64_encode(file_get_contents('http://lorempixel.com/200/300/cats/'));

						$this->makeRequest(200, ['img' => $asset]);						
					} catch (Exception $e) {
						$this->makeRequest(401);
					}
				} else {
					$this->makeRequest(400);
				}

				// list($jwt) = sscanf( $authHeader->toString(), 'Authorization: Bearer %s');
        		// if ($jwt) {
				// 	try {
				// 		$secretKey = base64_decode($this->jwt_secretKey);
				// 		$algorithm = $this->jwt_algorithm;
				// 		JWT::$leeway = 60; // $leeway in seconds
				// 		$decoded = JWT::decode(
				// 			$jwt,
				// 			$secretKey,
				// 			array($algorithm)
				// 		);
				//
				// 		$this->makeRequest(200, $decoded);
				// 	} catch (Exception $e) {
				// 		$this->makeRequest(401);
				// 	}
				// } else {
				// 	$this->makeRequest(400);
				// }


				// $jwt = $this->getBearerToken();
				// if(isset($jwt) && !is_null($jwt)) {
				// 	$secretKey = base64_decode($this->jwt_secretKey);
				//     $algorithm = $this->jwt_algorithm;
				// 	JWT::$leeway = 60; // $leeway in seconds
				// 	$decoded = JWT::decode(
				// 		$jwt,
				// 		$secretKey,
				// 		array($algorithm)
				// 	);
				//
				// 	$this->makeRequest(200, $decoded);
				// } else {
				// 	$this->makeRequest(401);
				// }
			}

			$this->makeRequest(400);
		}


		/**
		 * Get hearder Authorization
		 * */
		private function getAuthorizationHeader(){
		    $headers = null;
		    if (isset($_SERVER['Authorization'])) {
		        $headers = trim($_SERVER["Authorization"]);
		    }
		    else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
		        $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
		    } elseif (function_exists('apache_request_headers')) {
		        $requestHeaders = apache_request_headers();
		        // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
		        $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
		        //print_r($requestHeaders);
		        if (isset($requestHeaders['Authorization'])) {
		            $headers = trim($requestHeaders['Authorization']);
		        }
		    }
		    return $headers;
		}
		/**
		 * get access token from header
		 * */
		private function getBearerToken() {
		    $headers = $this->getAuthorizationHeader();
		    // HEADER: Get the access token from the header
		    if (!empty($headers)) {
		        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
		            return $matches[1];
		        }
		    }
		    return null;
		}


		private function testUser($json_arr)
		{
			$sql = rex_sql::factory();
			$sql->setDebug(false);
			$sql->setTable(rex::getTablePrefix().'ycom_user');
			$sql->setWhere( ['id' => $json_arr['id'], 'activation_key' => $json_arr['key'], 'status' => 1]);
			$sql->select();

			if($sql->getRows()) {
				$this->getJWT($json_arr);
			} else {
				$this->makeRequest(401);
			}
		}


		private function getJWT($json_arr)
		{
			$jwt = null;
			$tokenId    = base64_encode(mcrypt_create_iv(32));
			$issuedAt   = time();
			$notBefore  = $issuedAt + 10;  		// Adding 10 seconds
			$expire     = $notBefore + 60; 		// Adding 60 seconds
			$serverName = rex::getServer(); 	// Adding the server URL.

			$data = [
				'iat'  => $issuedAt,   // Issued at: time when the token was generated
				'jti'  => $tokenId,    // Json Token Id: an unique identifier for the token
				'iss'  => $serverName, // Issuer
				'nbf'  => $notBefore,  // Not before
				'exp'  => $expire,     // Expire
				'data' => $json_arr    // Data related to the signer user
			];

			$secretKey = base64_decode($this->jwt_secretKey);
		    $algorithm = $this->jwt_algorithm;
		    $jwt = JWT::encode(
		        $data,      //Data to be encoded in the JWT
		        $secretKey, // The signing key
		        $algorithm  // Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
		    );

			self::makeRequest(200, $jwt);
		}


		private function setConfig() {
			$addon = rex_addon::get('restful_api');
			$this->jwt_active 		= $addon->getConfig('restful_api_active');
			$this->jwt_secretKey 	= $addon->getConfig('restful_api_secretKey');
			$this->jwt_algorithm 	= $addon->getConfig('restful_api_algorithm');
		}


		// REQUEST BODY = hash: eyJpZCI6MSwia2V5IjoiNzdhMDEwNTRjMTg1ODE4NjA2YWEwNzdjYjdhYzFiNTgiLCJmdW5jIjoiYXV0aCJ9
		private function makeRequest($code, $data = null)
		{
			$header = '';
			switch ($code) {
			    case 200: $header = 'OK'; break;
			    case 201: $header = 'Created Successfully'; break;
			    case 202: $header = 'Accepted'; break;
			    case 204: $header = 'No Content'; break;
			    case 301: $header = 'Moved Permanently'; break;
			    case 400: $header = 'Bad Request'; break;
			    case 401: $header = 'Unauthorized'; break;
			    case 404: $header = 'Not Found'; break;
			    case 500: $header = 'Internal Server Error'; break;
			}

			$status = '';
			switch ((int)substr($code, 0, 1)) {
				case 2: $status = 'success'; break;
			    case 3: $status = 'move'; break;
			    case 4: $status = 'fail'; break;
			    case 5: $status = 'error'; break;
			}

			$json = [
				'code' => $code,
				'status' => $status,
				'data' => $data
			];

			if(isset($header)) {
				header('Content-Type: application/json');
				header('HTTP/1.1 '.$code.' '.$header);
				echo json_encode($json);
				exit;
			}
		}





		// private function setUser()
		// {
		// 	$user = (rex::isBackend()) ? rex::getUser() : rex_ycom_auth::getUser();
		// 	if( $user ) {
		// 		$this->user_id = $user->getId();
		// 		$this->user_type = (rex::isBackend()) ? 'backend' : 'frontend';
		// 	}
		// 	return $user;
		// }
		//
		//
		// public function getJWT()
		// {
		// 	$tokenId    = base64_encode(mcrypt_create_iv(32));
		// 	$issuedAt   = time();
		// 	$notBefore  = $issuedAt + 10;  		// Adding 10 seconds
		// 	$expire     = $notBefore + 60; 		// Adding 60 seconds
		// 	$serverName = rex::getServer(); 	// Adding the server URL.
		//
		// 	$data = [
		// 		'iat'  => $issuedAt,         			// Issued at: time when the token was generated
		// 		'jti'  => $tokenId,          			// Json Token Id: an unique identifier for the token
		// 		'iss'  => $serverName,       			// Issuer
		// 		'nbf'  => $notBefore,        			// Not before
		// 		'exp'  => $expire,           			// Expire
		// 		'data' => [                  			// Data related to the signer user
		// 			'user_id' 	=> $this->user_id, 		// userid from the users table
		// 			'user_type' => $this->user_type, 	// User name
		// 		]
		// 	];
		//
		// 	$secretKey = base64_decode($this->jwt_secretKey);
        //     $algorithm = $this->jwt_algorithm;
        //     $jwt = JWT::encode(
        //         $data,      //Data to be encoded in the JWT
        //         $secretKey, // The signing key
        //         $algorithm  // Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
        //     );
		//
        //     $unencodedArray = [
		// 		'code' 		=> 200,
		// 		'status' 	=> 'success',
		// 		'message' 	=> 'The request has succeeded.',
		// 		'jwt' 		=> $jwt
		// 	];
		//
		// 	header('Content-Type: application/json');
		// 	header('HTTP/1.1 200 OK');
		// 	echo json_encode($unencodedArray);
		// 	exit;
		// }
		//
		//
		// public function getJWTerror()
		// {
		// 	$data = [
		// 		'code' => 401,
		// 		'status' => 'fail',
		// 		'message' => 'The request has not been applied because it lacks valid authentication credentials for the target resource.',
		// 	];
		//
		// 	header('Content-Type: application/json');
		// 	header('HTTP/1.1 401 Unauthorized');
		// 	echo json_encode($data);
		// 	exit;
		// }


	}
