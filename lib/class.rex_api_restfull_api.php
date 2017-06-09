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
		protected $addNotBefore 	= 5;
		protected $addExpire 		= 5;

		// USER Data
		protected $user_id 			= null;
		protected $user_type 		= null;



		public function execute()
		{
			$message = '';
			$this->setConfig(); // set the config from redaxo
			$message = $this->isRequest(); // test request
			$result = new rex_api_result(true, $message); // result
			return $result;
		}



		private function isRequest() {
			$path = rex_request('path', 'string');
			$arr = ['path' => $path];

			switch ($path) {
				case 'auth': 		$this->makeRequest(200, $arr); break;
				case 'getuser/4': 	$this->makeRequest(200, $arr); break;
				default:
					$this->makeRequest(404, $arr);
					break;
			}


			exit;

			// $this->makeRequest(200, $arr);
		}



		private function setConfig() {
			$addon = rex_addon::get('restful_api');
			$this->jwt_active 		= $addon->getConfig('restful_api_active');
			$this->jwt_secretKey 	= $addon->getConfig('restful_api_secretKey');
			$this->jwt_algorithm 	= $addon->getConfig('restful_api_algorithm');
		}



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



	}
