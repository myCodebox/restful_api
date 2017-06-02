# restful_api
Restful API for Redaxo 5


## Installation

Use composer to manage your dependencies

```
composer install
```

### Use Frontend User
```
root/?rex-api-call=restfull_api&func=users
```
### Use Backend User
```
root/redaxo/index.php?page=restful_api/restful_api_overview&rex-api-call=restfull_api
```
JS Demo
```php
<?php

	$url = rex_url::currentBackendPage([
		'rex-api-call'=>'restfull_api'
	], false);

	echo <<<EOD
		<pre class="result"></pre>
		<script type="text/javascript">
			$(document).on('ready pjax:success',function(){
				$.post( '$url', function( data ) {
					$( ".result" ).html( JSON.stringify(data) );
				});
			});
		</script>
EOD;
?>
```


### Links
* https://www.sitepoint.com/php-authorization-jwt-json-web-tokens/
* https://blog.codecentric.de/2016/11/json-web-token-jwt-im-detail/
* https://github.com/redaxo/redaxo/issues/834
* https://github.com/firebase/php-jwt
* https://github.com/kjur/jsrsasign
* https://jwt.io
* https://developers.triathlon.org/docs/responses-and-status-codes
* https://httpstatuses.com/200


### Todo
Create an `api` folder with a `.htaccess` file on install
```
Folder:
	root/api
htaccess:
	^/v2/users$ /?rex-api-call=restfull_api&func=users [NC,L]
```
