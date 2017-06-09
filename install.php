<?php

	// CONFIG
	if (!$this->hasConfig('restful_api_jwt_secretKey')) {
		// SecretKey for signing the JWT's, I suggest generate it with base64_encode(openssl_random_pseudo_bytes(64))
		$secretKey = base64_encode(openssl_random_pseudo_bytes(64));
		$this->setConfig('restful_api_secretKey', $secretKey);
	}

	if (!$this->hasConfig('restful_api_algorithm')) {
		// Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
		$algorithm = 'HS256';
		$this->setConfig('restful_api_algorithm', $algorithm);
	}

	if (!$this->hasConfig('restful_api_active')) {
		// Frontend request allowed
		$active = true;
		$this->setConfig('restful_api_active', $active);
	}


	// DATABASE
	$sql = rex_sql::factory();
	$sql->setQuery('CREATE TABLE IF NOT EXISTS `'.rex::getTable('restful_api_paths').'` (
		    `id` int(11) NOT NULL AUTO_INCREMENT,
		    `paths` varchar(255) NOT NULL,
		    `function` TEXT NOT NULL,
		    `description` varchar(255) NOT NULL,
			`status` varchar(255) NOT NULL,
			`createuser` varchar(255) NOT NULL,
		    `updateuser` varchar(255) NOT NULL,
		    `createdate` datetime NOT NULL,
		    `updatedate` datetime NOT NULL,
		    `revision` int(11) unsigned NOT NULL,
		    PRIMARY KEY (`id`)
	    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');

	// CACHE
	rex_delete_cache();
