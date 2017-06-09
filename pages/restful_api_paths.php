<?php
	// VARs
	$content = '';



	// REQUESTs
	$func = rex_request('func', 'string');



	if ($func == '') {
		// REX_LIST
		$sql = 'SELECT * FROM '.rex::getTable('restful_api_paths');
	    $list = rex_list::factory($sql, 100);
	    $list->setColumnFormat('id', 'Id');
	    $list->addParam('page', 'restful_api/restful_api_paths');

		// REMOVE
		$list->removeColumn('createuser');
		$list->removeColumn('updateuser');
		$list->removeColumn('createdate');
		$list->removeColumn('updatedate');
		$list->removeColumn('revision');

		// SORTABLE
		$list->setColumnSortable('id');
		$list->setColumnSortable('paths');
		$list->setColumnSortable('function');
		$list->setColumnSortable('description');
		$list->setColumnSortable('status');

		// ADD BUTTON
		$tdIcon = '<i class="fa fa-sitemap"></i>';
	    $thIcon = '<a href="' . $list->getUrl(['func' => 'add']) . '"' . rex::getAccesskey($this->i18n('add_domain'), 'add') . '><i class="rex-icon rex-icon-add"></i></a>';
	    $list->addColumn($thIcon, $tdIcon, 0, ['<th class="rex-table-icon">###VALUE###</th>', '<td class="rex-table-icon">###VALUE###</td>']);
	    $list->setColumnParams($thIcon, ['func' => 'edit', 'data_id' => '###id###']);

		// GET LIST
		$content = $list->get();
	}
	elseif ($func == 'add') {
		$yform = new rex_yform();
	    // $yform->setDebug(TRUE);
	    $yform->setHiddenField('page', 'restful_api/restful_api_paths');
	    $yform->setHiddenField('func', $func);
	    $yform->setHiddenField('save', '1');

		$yform->setObjectparams('main_table', rex::getTable('restful_api_paths'));

		// PATH
		$yform->setValueField('text_extra', [
			'paths', $this->i18n('form_paths_title'),
			'placeholder' => 'neuer/pfad/42',
			'notice' => $this->i18n('form_paths_info'),
			'prefix' => '<span class="input-group-addon" id="basic-addon3">api/</span>',
		]);
	    $yform->setValidateField('empty', ['paths', $this->i18n('form_paths_empty_defined')]);
	    $yform->setValidateField('unique', ['paths', $this->i18n('form_paths_already_defined')]);

		// FUNCTION
		$yform->setValueField('textarea', [
			'function', $this->i18n('form_function_title'),
			'placeholder' => 'function name(val) { ... }',
			'attributes'=>'{"class":"form-control codemirror", "odemirror-theme":"paraiso-dark", "codemirror-mode":"php/htmlmixed"}',
			'short'
		]);
		$yform->setValidateField('empty', ['function', $this->i18n('form_paths_function_defined')]);


		$content = $yform->getForm();
	}
	elseif ( $func == 'edit') {
		// $debug = false;
		// $form = custom_rex_form::factory(self::$db, 'Datensatz', "id=".self::$id, 'post', $debug, 'restful_api_paths');
		// $form->get();
	}



	if($func == '') {
		// ADD LIST TO THE CONTENT FRAGMENT
		$fragment = new rex_fragment();
		$fragment->setVar('title', $this->i18n('restful_api_paths'));
		$fragment->setVar('content', $content, false);
		echo $fragment->parse('core/page/section.php');
	} else {
		// ADD FORM TO THE BODY FRAGMENT
		$fragment = new rex_fragment();
		$fragment->setVar('class', 'edit', false);
		$fragment->setVar('title', $this->i18n('restful_api_paths'));
		$fragment->setVar('body', $content, false);
		echo $fragment->parse('core/page/section.php');
	}
