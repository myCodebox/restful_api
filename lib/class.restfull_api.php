<?php

	class restfull_api {

		protected static $published = true;



		public static function setHtaccessButton()
		{
			return self::issetHtaccess();
			// $button = sprintf(
			// 	'<p><a class="btn btn-primary" href="%s">%s</a></p>',
			// 	rex_url::currentBackendPage(['func' => 'htaccess']),
			// 	rex_addon::get('yrewrite') ? 'yrewrite_htaccess_set' : 'htaccess_set'
			// );
			// // return self::testHtaccess() ? $button : false;
		}

		private static function issetHtaccess()
		{
			$path = rex_path::frontend('.htaccess');
			$file = rex_file::get($path);

			$found = 0;
			if($file) {
				if(rex_addon::get('yrewrite')) {
					preg_match_all("/rex_yrewrite_func/", $file, $output_arr);
					if(is_array($output_arr) && !empty($output_arr[0])) $found = 2;
				} else {
					$found = 1;
				}
			}

			$template = '<div class="panel panel-%s">
					<div class="panel-heading">%s</div>
					<div class="panel-body">%s</div>
				</div>';

			$button = '<a class="btn btn-primary" href="%s">%s</a>';

			$msg = '';
			$found = 2;
			switch ($found) {
				// not found
				case 0: $msg = sprintf( $template, 'primary',
					'Es ist derzeit kein <strong>".htaccess"</strong> File vorhanden',
					sprintf($button, rex_url::currentBackendPage(['func' => 'htaccess']), 'Neu ".htaccess" erstellen')
				); break;
				// found
				case 1: $msg = sprintf( $template, 'danger',
					'Es wurde eine <strong>".htaccess"</strong> Datei gefunden',
					sprintf($button, rex_url::currentBackendPage(['func' => 'htaccess']), '".htaccess" überschreiben')
				); break;
				// YRewrite found
				case 2: $msg = sprintf( $template, 'danger',
					'Es wurde eine <strong>".htaccess"</strong> Datei vom <strong>"YRewrite"</strong> Addon gefunden',
					sprintf($button, rex_url::currentBackendPage(['func' => 'htaccess']), 'In die ".htaccess" schreiben')
				); break;
			}

			return $msg;
		}


		public static function setHtaccess()
		{
			$button = sprintf(
				'<p><a class="btn btn-primary" href="%s">%s</a></p>',
				rex_url::currentBackendPage(['func' => 'htaccess']),
				'yrewrite_htaccess_set'
			);
			return self::testHtaccess() ? $button : false;
		}


		private static function testHtaccess()
		{
			$path = rex_path::frontend('.htaccess');
			$file = rex_file::get($path);
			if($file) {
				preg_match_all("/(#start:restful_api)\s*(.*)\s*(#end:restful_api)/", $file, $output_arr);
				return (is_array($output_arr) && empty($output_arr[0])) ? true : false;
			}
		}


		// public static function setHtaccess()
		// {
		// 	$path = rex_path::frontend('.htaccess');
		// 	$file = rex_file::get($path);
		//
		// 	if($file) {
		// 		preg_match_all("/(#start:restful_api)\s*(.*)\s*(#end:restful_api)/", $file, $output_arr);
		// 		if(is_array($output_arr) && empty($output_arr[0])) {
		// 			$search = '# REWRITE RULE FOR SEO FRIENDLY IMAGE MANAGER URLS';
		// 			$include = "\n";
		// 			$include .= '#start:restful_api'."\n";
		// 			$include .= 'RewriteRule ^api/ %{ENV:BASE}/index.php?rex-api-call=restfull_api [NC,L]'."\n";
		// 			$include .= 'RewriteRule ^api/auth/ %{ENV:BASE}/index.php?rex-api-call=restfull_api [NC,L]'."\n";
		// 			$include .= 'RewriteRule ^api/.* %{ENV:BASE}/index.php?rex-api-call=restfull_api&path=$1 [NC,L]'."\n";
		// 			$include .= '#end:restful_api'."\n";
		// 			$newfile = str_replace($search, $search.$include, $file);
		// 			return true;
		// 		} else {
		// 			return false;
		// 		}
		// 	}
		// }

	}
