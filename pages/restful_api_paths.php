<?php

	$content = '';


	$fragment = new rex_fragment();
	$fragment->setVar('title', $this->i18n('restful_api_paths'));
	$fragment->setVar('body', $content, false);
	echo $fragment->parse('core/page/section.php');
