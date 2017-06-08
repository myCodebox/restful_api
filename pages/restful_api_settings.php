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


	if(rex_addon::get('yrewrite')) {}


	$content .= '<div class="container-fluid">
			<div class="col-xs-12">
				<h3>Addon Einstellungen</h3>
			</div>
			<form action="'.rex_url::currentBackendPage().'" method="post" id="restful_api_settings">
				<div class="col-xs-8">
					<input class="rex-form-chk" type="checkbox" id="rex-form-restful_api_active" name="config[restful_api_active]" value="1" '.$jwt_active.' />
					<label for="rex-form-restful_api_active">'.$this->i18n("restful_api_active").'</label>
				</div>
				<div class="col-xs-4">
					<button class="btn btn-save pull-right" type="submit" name="config-submit" value="1" title="'.$this->i18n('restful_api_save_btn').'">'.$this->i18n('restful_api_save_btn').'</button>
				</div>
			</form>
			<div class="col-xs-12">
				<hr />
			</div>
			<div class="col-xs-12">
				<h3>.htaccess Datei setzen</h3>
				<p>
					Um RESTFUL_API lauffähig zu bekommen, muss eine .htaccess Datei im Root Ordner
					gesetzt werden. Sollte eine andere .htaccess bereits vorhanden sein, wird diese
					ersetzt und ist nicht wiederherstellbar.
				</p>
				'.restfull_api::setHtaccessButton().'
			</div>
		</div>';



	$fragment = new rex_fragment();
	// $fragment->setVar('class', 'edit');
	$fragment->setVar('title', $this->i18n('restful_api_settings'));
	$fragment->setVar('body', $content, false);
	echo $fragment->parse('core/page/section.php');
