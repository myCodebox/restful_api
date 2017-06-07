# restful_api
Restful API for Redaxo 5


## Installation

Use composer to manage your dependencies

```
composer install
```

### Use Frontend User
```
root/?rex-api-call=restfull_api
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
	$.post( '$url', function( data ) {
		$( ".result" ).html( JSON.stringify(data) );
	});
</script>
EOD;

?>
```

### HTML Test page
```html
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>API Test</title>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jsrsasign/7.2.1/jsrsasign-all-min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/reqwest/2.0.5/reqwest.min.js"></script>
	</head>
	<body>


		<script type="text/javascript">
			$(function(){
				var App = App || {};

				App.init = {
					api_obj: {
						id: 4,
						key: '77a01054c185818606aa077cb7ac1b58'
					},
					api_url: 'ROOT\/?rex-api-call=restfull_api',
					api_hash: 'eyJpZCI6MSwia2V5IjoiNzdhMDEwNTRjMTg1ODE4NjA2YWEwNzdjYjdhYzFiNTgifQ==',
					/*
					 * -----------------------------------------------------------------------------
					 * Set Data with 'btoa' or in PHP with 'base64_encode()'
					 * Get Data with 'atob' or in PHP with 'base64_decode()'
					 * api_obj: {
	 				 *		id: 4,
	 				 *		key: '77a01054c185818606aa077cb7ac1b58'
	 				 *	},
					 * api_hash = btoa(api_obj);
					 * -----------------------------------------------------------------------------
					 * get this hash 'eyJpZCI6MSwia2V5IjoiNzdhMDEwNTRjMTg1ODE4NjA2YWEwNzdjYjdhYzFiNTgifQ=='
					 * -----------------------------------------------------------------------------
					 */

					login: function () {
						// let hash_btoa = btoa('id='+this.api_user_id+'&key='+this.api_user_key);
						// let hash_atob = atob(hash_btoa);

						let hash_btoa = btoa(JSON.stringify(this.api_obj));

						reqwest({
							url: this.api_url,
							method: 'post',
							data: { hash: hash_btoa, func: 'auth' },
							success: function (resp) {
								// qwery('#content').html(resp)
							}
						})
					},
				}

				App.init.login();
			});

		</script>
	</body>
</html>
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
* https://github.com/ded/Reqwest


### Todo
Create an `api` folder with a `.htaccess` file on install
```
Folder:
	root/api
htaccess:
	^/v2/users$ /?rex-api-call=restfull_api&func=users [NC,L]
```
