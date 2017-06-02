<?php
	$url = rex_url::currentBackendPage([
		'rex-api-call'=>'restfull_api'
	], false);
?>

<pre class="data"></pre>
<pre class="jwt"></pre>
<pre class="claim"></pre>


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

		$.post('<?php echo $url; ?>', function(data){
			store.setJwt(data.jwt);

			var data_str = JSON.stringify(data);
			$( ".data" ).html( data_str );
		});
	});
</script>

<!-- <script type="text/javascript">
	$.post('<?php echo $url; ?>', function(data){

		var data = JSON.stringify(data);
		$( ".data" ).html( data );

		console.log(data.jwt);

        // var jwt = ecodeToken(data.jwt);
		// $( ".jwt" ).html( jwt );

		// // var claim 	= this.decodeToken(jwt);
		//
		// $( ".claim" ).html( claim );
    }).fail(function(){
        alert('error');
	});

	function ecodeToken(jwt){
		var a = jwt.split(".");
		return  b64utos(a[1]);
	}
</script> -->

<!--
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
 -->
