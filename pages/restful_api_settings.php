<?php


	$content = '';


	if (rex_post('config-submit', 'boolean')) {
		$this->setConfig(
			rex_post('config', [
				['restful_api_active', 'int'],
			]
		));

		$content .= rex_view::info('Änderung gespeichert');
	}


	$jwt_active 	= ($this->getConfig('restful_api_active') == 1) ? 'checked="checked"' : '';
	$jwt_secretKey	= $this->getConfig('restful_api_secretKey');
	$jwt_algorithm 	= $this->getConfig('restful_api_algorithm');

	$htaccess_btn = '';
	if(rex_addon::get('yrewrite')) {
		$htaccess_btn .= '<div class="col-xs-12">
				<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
				'.restfull_api::setHtaccess().'
				<hr />
			</div>';

		// if(restfull_api::setHtaccess()) {
		// 	// echo rex_view::success($this->i18n('htaccess_hasbeenset'));
		// 	echo rex_view::success('Wird eingetragen');
		// } else {
		// 	// echo rex_view::success($this->i18n('htaccess_cantbeenset'));
		// 	echo rex_view::error('Ist schon vorhanden');
		// }
	}


	$content .= '<div class="container-fluid">
			<div>
				<h3>.htaccess Datei setzen</h3>
				<p>
					Um RESTFUL_API lauffähig zu bekommen, muss eine .htaccess Datei im Root Ordner
					gesetzt werden. Sollte eine andere .htaccess bereits vorhanden sein, wird diese
					ersetzt und ist nicht wiederherstellbar.
				</p>
			</div>
			'.$htaccess_btn.'
			<form action="'.rex_url::currentBackendPage().'" method="post" id="restful_api_settings">
				<div class="col-xs-8">
					<input class="rex-form-chk" type="checkbox" id="rex-form-restful_api_active" name="config[restful_api_active]" value="1" '.$jwt_active.' />
					<label for="rex-form-restful_api_active">'.$this->i18n("restful_api_active").'</label>
				</div>
				<div class="col-xs-4">
					<button class="btn btn-save pull-right" type="submit" name="config-submit" value="1" title="'.$this->i18n('restful_api_save_btn').'">'.$this->i18n('restful_api_save_btn').'</button>
				</div>
			</form>
		</div>';



	$fragment = new rex_fragment();
	// $fragment->setVar('class', 'edit');
	$fragment->setVar('title', $this->i18n('restful_api_settings'));
	$fragment->setVar('body', $content, false);
	echo $fragment->parse('core/page/section.php');
