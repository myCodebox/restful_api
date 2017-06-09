<?php


	$content = '';


	$path = rex_path::addon('restful_api', 'setup/.htaccess');
	$htaccess_str = rex_file::get($path);

	$content .= '<div class="container-fluid">
			<div class="col-xs-12">
				<h3>.htaccess Datei setzen</h3>
				<p>
					Das Addon "RESTFUL_API" benötigt eine .htaccess Datei im Root Ordner. Es wird
					automatisch geprüft ob eine .htaccess Datei vorhanden ist und derpassende
					Button eingebunden. Folgende möglichkeiten gibt es:
				</p>
				<ol>
					<li>Keine .htaccess gefunden: <strong>Neu schreiben</strong></li>
					<li>.htaccess gefunden: <strong>Überschreiben *</strong></li>
					<li>.htaccess von YRewrite gefunden: <strong>Zeile einfügen</strong></li>
				</ol>
				<p>'.restfull_api_htaccess::getOutput().'</p>
				<p>
					* Sollte eine andere .htaccess bereits vorhanden sein, wird diese ersetzt
					und ist nicht wiederherstellbar.
				</p>
				<hr />
				<h3>.htaccess Datei aus dem Setup Ordner</h3>
				<pre>'.htmlspecialchars($htaccess_str).'</pre>
			</div>
		</div>';


	$fragment = new rex_fragment();
	$fragment->setVar('title', $this->i18n('restful_api_settings'));
	$fragment->setVar('body', $content, false);
	echo $fragment->parse('core/page/section.php');
