<?php

	use \Psr\Http\Message\ServerRequestInterface as Request;
	use \Psr\Http\Message\ResponseInterface as Response;

	require '/redaxo/src/addons/restful_api/vendor/autoload.php';

	class rex_api_slim extends rex_api_function {

		// open the frontend
		protected $published = true;

		public function execute()
		{
			$message = '';
			$message = $this->isRequest(); // test request
			$result = new rex_api_result(true, $message); // result
			return $result;
		}


		public function isRequest()
		{
			$app = new \Slim\App;

			$app->group('/slim', function () {
				$this->map(['GET', 'POST'], '/link1', function (Request $request, Response $response) {
				    $response->getBody()->write("Link 1");
				    return $response;
				});
				$this->map(['GET', 'POST', 'PUT'], '/link2', function (Request $request, Response $response) {
					if($request->isPut()) {
				    	$data = ['text'=>'LINK 2 PUT'];
					} else {
						$data = ['text'=>'LINK 2'];
					}
				    $response = $response->withJson($data);
				    return $response;
				});
			});
			$app->run();

			// $app = new \Slim\App;
			// // $app->group('/slim/', function () {
			// // 	$this->map(['GET', 'POST'], '[{name}]', function (Request $request, Response $response) {
			// // 		$name = $request->getAttribute('name');
			// // 	    $response->getBody()->write("Hello $name");
			// // 	    return $response;
			// // 	});
			// // });
			//
			// $app->post('/slim/media', function (Request $request, Response $response) {
			// 	$sql = rex_sql::factory();
			// 	$sql->setTable(rex::getTablePrefix().'media'); // rex_foo_bar
			// 	$sql->setWhere(['revision'=>'0', 'filetype' => 'image/png']);
			// 	$sql->select();
			// 	$data = $sql->getArray();
			//
			// 	$response = $response->withJson($data);
			// 	return $response;
			// });
			// $app->run();

			exit;
		}

	}
