<?php

	// http://localhost/meine/Redaxo/01/redaxo/index.php?page=structure&rex-api-call=restfull_api
	class rex_api_restfull_api extends rex_api_function {

		protected $published = true;

		public function execute() {
			$message = $this->getTest();

			$result = new rex_api_result(true, $message);
			return $result;
		}

		public function getTest() {
			$data = [
				'time' => time(),
				'data' => [
					1 => 'Hallo',
					2 => 'Servus',
					3 => 'Test',
				]
			];
			header('Content-Type: application/json');
			echo json_encode($data);
			exit;
		}

	}
