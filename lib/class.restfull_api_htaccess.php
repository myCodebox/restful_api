<?php

	class restfull_api_htaccess {


		protected static $htaccess_found = null;


		public static function getOutput()
		{
			// init
			self::isHtaccess();
			self::writefile();

			// output
			return self::setButton();
		}



		private static function writefile()
		{
			$func = rex_request('func', 'string');
			$type = rex_request('type', 'int');

		    if ($func == 'htaccess' && $type != '' ) {
		        echo rex_view::success('Super!');
		    }
		}



		private static function isHtaccess()
		{
			# $found 0 => .htaccess not found
			#        1 => .htaccess found
			#        2 => .htaccess found with yrewrite

			$path = rex_path::frontend('.htaccess');
			$file = rex_file::get($path);

			$found = 0; // not found

			if($file) {
				$found = 1; // found
				if(rex_addon::get('yrewrite') && preg_match_all("/rex_yrewrite_func/", $file) ) {
					$found = 2; // found with yrewrite
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
				case 0:
					$out = sprintf($button, rex_url::currentBackendPage(['func'=>'htaccess','type'=>0]), '".htaccess" erstellen');
						break;
				// found ----------------------------------
				case 1:
					$out .= sprintf($button, rex_url::currentBackendPage(['func'=>'htaccess','type'=>1]), '".htaccess" überschreiben');
						break;
				// found with yrewrite --------------------
				case 2:
					$out .= sprintf($button, rex_url::currentBackendPage(['func'=>'htaccess','type'=>2]), '".htaccess" erweitern');
						break;
			}

			return $out;
		}



	}
