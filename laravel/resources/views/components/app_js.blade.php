<script type="text/javascript">
	$(document).ready(function() {
		// $.ajaxSetup({
		// 	headers: {
		// 		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		// 	}
		// });

		
	    $.ajaxPrefilter(function(options, originalOptions, xhr) { // this will run before each request
	        var token = $('meta[name="csrf-token"]').attr('content'); // or _token, whichever you are using

	        if (token) {
	            return xhr.setRequestHeader('X-CSRF-TOKEN', token); // adds directly to the XmlHttpRequest Object
	        }
	    });
	});
</script>