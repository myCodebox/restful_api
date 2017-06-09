<?php

	class restfull_api_htaccess {


		protected static $htaccess_found = null;


		public static function getOutput()
		{
			// init
			self::isHtaccess();

			// write
			self::writefile();

			// output
			return self::setButton();
		}



		private static function writefile()
		{
			$func = rex_request('func', 'string');
			$type = rex_request('type', 'int');

		    if ($func == 'htaccess' && $type != '' ) {
				if($type == 1) {
					rex_file::copy(rex_path::addon('restful_api', 'setup/.htaccess'), rex_path::frontend('.htaccess'));
		        	echo rex_view::success('.htaccess wurde neu angelegt!');
				}
				if($type == 2) {
					rex_file::copy(rex_path::addon('restful_api', 'setup/.htaccess'), rex_path::frontend('.htaccess'));
		        	echo rex_view::success('.htaccess wurde überschrieben!!');
				}
				if($type == 3) {
					$path = rex_path::frontend('.htaccess');
					$file = rex_file::get($path);

					if($file) {
						preg_match_all("/(#start:restful_api)\s*(.*)\s*(#end:restful_api)/", $file, $output_arr);
						if(is_array($output_arr) && empty($output_arr[0])) {
							$search = '# REWRITE RULE FOR SEO FRIENDLY IMAGE MANAGER URLS';
							$content = "\n";
							$content .= '#start:restful_api'."\n";
							$content .= 'RewriteRule ^api/([a-z0-9/]*) %{ENV:BASE}/index.php?rex-api-call=restfull_api&path=$1 [B]'."\n";
							$content .= '#end:restful_api'."\n";
							$newfile = str_replace($search, $search.$content, $file);

							rex_file::put( $path, $newfile );

							echo rex_view::success('Einige neue Zeilen wurden in die bestehende .htaccess eingetragen!');
						} else {
							echo rex_view::error('Die Zeile ist anscheinend schon eingetragen! Eventuell muss die Zeile händisch eingetragen werden.');
						}
					}
				}
		    }
		}



		private static function isHtaccess()
		{
			# $found 1 => .htaccess not found
			#        2 => .htaccess found
			#        3 => .htaccess found with yrewrite

			$path = rex_path::frontend('.htaccess');
			$file = rex_file::get($path);

			$found = 1; // not found

			if($file) {
				$found = 2; // found
				if(rex_addon::get('yrewrite') && preg_match_all("/rex_yrewrite_func/", $file) ) {
					$found = 3; // found with yrewrite
				}
			}

			self::$htaccess_found = $found;
		}



		private static function setButton()
		{
			$out    = '';
			$button = '<a class="btn btn-primary" href="%s">%s</a>';

			switch (self::$htaccess_found) {
				// not found ------------------------------
				case 1:
					$out = sprintf($button, rex_url::currentBackendPage(['func'=>'htaccess','type'=>1]), '".htaccess" erstellen');
						break;
				// found ----------------------------------
				case 2:
					$out .= sprintf($button, rex_url::currentBackendPage(['func'=>'htaccess','type'=>2]), '".htaccess" überschreiben');
						break;
				// found with yrewrite --------------------
				case 3:
					$out .= sprintf($button, rex_url::currentBackendPage(['func'=>'htaccess','type'=>3]), '".htaccess" erweitern');
						break;
			}

			return $out;
		}



	}
