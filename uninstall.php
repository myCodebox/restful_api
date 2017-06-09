<?php

	// DATABASE
	$sql = rex_sql::factory();
	$sql->setQuery(sprintf('DROP TABLE IF EXISTS `%s`;', rex::getTable('restful_api_paths')));

	// CACHE
	rex_delete_cache();
