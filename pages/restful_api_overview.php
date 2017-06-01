<?php

	$url = rex_url::currentBackendPage([
		'rex-api-call'=>'restfull_api'
	], false);

echo <<<EOD
	<div class="result"></div>
	<script type="text/javascript">
		$.post( '$url', function( data ) {
			// $( ".result" ).html( JSON.stringify(data) );
			$( ".result" ).html( data );
		});
	</script>
EOD;
