<?php


	$content = '';


	if (rex_post('config-submit', 'boolean')) {
		$this->setConfig(
			rex_post('config', [
				['restful_api_frontend', 'string'],
			]
		));

		$content .= rex_view::info('Änderung gespeichert');
	}


	$jwt_secretKey	= $this->getConfig('restful_api_secretKey');
	$jwt_algorithm 	= $this->getConfig('restful_api_algorithm');
	$jwt_published 	= ($this->getConfig('restful_api_frontend') == '1') ? 'checked="checked"' : '';


	$content .= '<form action="'.rex_url::currentBackendPage().'" method="post" id="restful_api_settings">
			<div class="container-fluid">

				<div class="col-xs-12">
					<input class="rex-form-chk" type="checkbox" id="rex-form-restful_api_frontend" name="config[restful_api_frontend]" value="1" '.$jwt_published.' />
					<label for="rex-form-restful_api_frontend">'.$this->i18n("restful_api_frontend").'</label>
				</div>

				<div class="col-xs-12">
					<hr />
					<button class="btn btn-save" type="submit" name="config-submit" value="1" title="'.$this->i18n('restful_api_save_btn').'">'.$this->i18n('restful_api_save_btn').'</button>
				</div>

			</div>
		</form>';


	$fragment = new rex_fragment();
	$fragment->setVar('class', 'edit');
	$fragment->setVar('title', $this->i18n('restful_api_settings'));
	$fragment->setVar('body', $content, false);
	echo $fragment->parse('core/page/section.php');
