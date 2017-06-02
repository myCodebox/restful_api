<?php
	$url = rex_url::currentBackendPage([
		'rex-api-call'=>'restfull_api'
	], false);
?>

<pre class="req"></pre>
<pre class="jwt"></pre>
<pre class="claim"></pre>
<pre class="data"></pre>


<script type="text/javascript">
	$(function(){
		var store = store || {};

		store.decodeToken = function(jwt){
			var a = jwt.split(".");
			return  b64utos(a[1]);
		}

		store.setJwt = function(data){
			this.jwt = data;
			this.claim = this.decodeToken(data);
		}

		$.post('<?php echo $url; ?>', function(req){
			store.setJwt(req.jwt);

			var req_str = JSON.stringify(req);
			$('.req').html( req_str );
			$('.jwt').html( store.jwt );
			$('.claim').html( store.claim );

			var parsedJSON = JSON.parse(store.claim);
			var parsedJSON_str = JSON.stringify(parsedJSON.data);
			$('.data').html( parsedJSON_str );
		});
	});
</script>
