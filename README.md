# restful_api
Restful API for Redaxo 5


## Installation

Use composer to manage your dependencies

```
composer install
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
