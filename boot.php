<?php

	if(rex::isBackend()) {
		// BACKEND
		rex_view::addJsFile($this->getAssetsUrl('js/jsrsasign.min.js'));
	} else {
		// FRONTEND
		rex_extension::register('OUTPUT_FILTER', function(rex_extension_point $ep) {
			$content = $ep->getSubject();
			$insert .= '<script type="text/javascript" src="'.$this->getAssetsUrl('js/jsrsasign.min.js').'"></script>';
			$search = '</head>';
			$content = str_replace($search, $insert.$search, $content);
			$ep->setSubject($content);
		}, 'NORMAL');
	}


	// echo base64_encode(openssl_random_pseudo_bytes(64));
	// echo '<br />';
	// echo openssl_random_pseudo_bytes(64);
