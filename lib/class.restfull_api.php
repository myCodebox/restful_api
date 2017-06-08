<?php

	class restfull_api {

		protected static $published = true;


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
		// 			$include .= '#end:restful_api'."\n";
		// 			$newfile = str_replace($search, $search.$include, $file);
		// 			return true;
		// 		} else {
		// 			return false;
		// 		}
		// 	}
		// }

	}
