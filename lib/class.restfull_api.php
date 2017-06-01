<?php

	use \Firebase\JWT\JWT;

	// BACKEND 	rex_url::currentBackendPage([ 'rex-api-call'=>'restfull_api' ], false);
	// FRONTEND http://localhost/meine/Redaxo/01/?rex-api-call=restfull_api
	class rex_api_restfull_api extends rex_api_function {


		protected $published 	= true;
		protected $user 		= null;
		protected $user_id 		= null;
		protected $user_type 	= null;


		public function execute()
		{
			if( $this->setUser() && rex_request::requestMethod() == 'post' ) {
				$message = $this->getJWT();
			} else {
				$message = $this->getJWTerror();
			}

			$result = new rex_api_result(true, $message);
			return $result;
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
			$data = [
				'code' => 200,
				'status' => 'success',
				'message' => 'The request has succeeded.',
				'data' => [
					'user_id' => $this->user_id,
					'user_type' => $this->user_type,
				]
			];

			header('Content-Type: application/json');
			header('HTTP/1.1 200 OK');
			echo json_encode($data);
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
