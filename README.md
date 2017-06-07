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
Update the 'ROOT' in the 'this.url = 'ROOT\/?rex-api-call=restfull_api'
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


		<fieldset>
			<legend>Login</legend>
			<form action="api_test.html" id="getlogin" method="post">
				<label for="id">ID:</label><input type="text" id="id" name="id" value="4">
				<label for="key">KEY:</label><input type="text" id="key" name="key" value="77a01054c185818606aa077cb7ac1b58">
				<button type="submit" name="submit">Get Token</button>
			</form>
		</fieldset>
		<br />
		<fieldset>
			<legend>Token</legend>
			<form action="api_test.html" id="getdata" method="post">
				<label for="jwt">JWT:</label> [ Status <span class="token_code">0</span> ]<br />
				<textarea class="jwt" id="token" name ="token" rows="6" cols="52"></textarea>
				<button type="submit" name="submit">Get Data</button>
			</form>
		</fieldset>
		<br />
		<fieldset>
			<legend>Get Data with the Token</legend>
			<label for="data">Data:</label> [ Status <span class="data_code">0</span> ]<br />
			<span class="img"></span>
			<!-- <textarea class="data" id="data" name ="data" rows="6" cols="52"></textarea> -->
		</fieldset>


		<script type="text/javascript">
			(function () {
				"use strict";

				var App = App || {};


				App.init = function () {
					this.url = 'ROOT\/?rex-api-call=restfull_api';
					this.hash = null;
					this.iat = null;
					this.nbf = null;
					this.exp = null;
				}


				var getlogin = document.getElementById('getlogin');
				getlogin.addEventListener('submit', function(e){
					e.preventDefault();
					App.getlogin();
				}, false);

				var getlogin = document.getElementById('getdata');
				getdata.addEventListener('submit', function(e){
					e.preventDefault();
					App.getdata();
				}, false);


				App.setHash = function() {
					let arr = $('#getlogin').serializeArray(), hash = {};
					for (var i = 0; i < arr.length; i++) {
						hash[arr[i].name] = arr[i].value;
					}
					this.hash = btoa(JSON.stringify(hash));
				};

				App.decodeToken = function(jwt){
					var a = jwt.split(".");
					return  b64utos(a[1]);
				}

				App.setJwt = function(data){
					this.jwt = data;
					this.claim = this.decodeToken(data);
				}

				App.getlogin = function () {
					this.setHash();
					reqwest({
						url: App.url,
						method: 'post',
						data: { hash: App.hash, func: 'getauth' },
						success: function (res) {
							App.setJwt(res.data);
							$('.token_code').text(res.code);
							$('.jwt').text(res.data);

						},
						error: function (err) {
							$('.token_code').text(err.status+' '+err.statusText);
							$('.jwt').text('');
						}
					});
				};
				App.getdata = function () {
					var token = $('.jwt').val()
					reqwest({
						url: App.url,
						method: 'post',
						data: { hash: App.hash, func: 'getdata' },
						headers: {'Authorization':'Bearer '+App.jwt},
						success: function (res) {
							$('.data_code').text(res.code);
							// $('.data').text(res.data.img);
							$('.img').html('<img src="data:image/jpeg;base64,' + res.data.img + '" />');
						},
						error: function (err) {
							$('.data_code').text(err.status+' '+err.statusText);
							$('.data').text('');
						}
					});
				};

				App.init();
			})();
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

#### More Links
* https://github.com/myCodebox/sp-simple-jwt/blob/master/public/js/main.js
* https://github.com/myCodebox/sp-simple-jwt/blob/master/public/resource.php
* https://github.com/myCodebox/php-jwt


### Todo
Create an `api` folder with a `.htaccess` file on install
```
Folder:
	root/api
htaccess:
	^/v2/users$ /?rex-api-call=restfull_api&func=users [NC,L]
```
