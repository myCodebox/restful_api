<?php

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
