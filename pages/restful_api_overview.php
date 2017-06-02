<?php

	$url = rex_url::currentBackendPage([
		'rex-api-call'=>'restfull_api'
	], false);

	echo <<<EOD
		<pre class="result"></pre>
		<script type="text/javascript">
			$.post( '$url', function( data ) {
				$( ".result" ).html( JSON.stringify(data) );
			});
		</script>
EOD;
