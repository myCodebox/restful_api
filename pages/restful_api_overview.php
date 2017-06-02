<?php

	$url = rex_url::currentBackendPage([
		'rex-api-call'=>'restfull_api'
	], false);

	echo <<<EOD
		<pre class="result"></pre>
		<script type="text/javascript">
			$.post( '$url', function( data ) {
				var json = JSON.stringify(data);
				$( ".result" ).html( json );
			});

			// $.post( '$url', function( data ) {
			// 	var json = JSON.stringify(data);
			// 	var decode = decodeToken(data);
			// 	$( ".result" ).html( decode );
			//
			// });
			//
			// function decodeToken(jwt){
		    //     var a = jwt.split(".");
		    //     return  b64utos(a[1]);
		    // }

		</script>
EOD;
