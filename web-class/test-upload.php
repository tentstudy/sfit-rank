<!-- <!DOCTYPE html>
<html>
		<body>
				<form action="save-code.php" method="post" enctype="multipart/form-data">
						Select image to upload:
						<input type="file" name="fileToUpload" id="fileToUpload">
						<input type="submit" value="Upload Image" name="submit">
				</form>
		</body>
</html> -->
<!-- <form>
	<input type="radio" name="foo" value="1" checked="checked" />
	<input type="radio" name="foo" value="0" />
	<input name="bar" value="xxx" />
	<input type="file" name="fileToUpload" id="choose-file" accept=".html,.php,.zip,.rar" />
						<span id="choosed-file">Không có file nào được chọn</span>
	<select name="this">
		<option value="hi" selected="selected">Hi</option>
		<option value="ho">Ho</option>
	</select>
	<button id="zz" type="button" class="btn btn-primary">button</button>
	<div id="result"></div>
</form>

<script>
	'use strict';
	$(function() {
		$("#zz").click(function() {
			$('#result').html("<br />$('form').serialize():<br />"+ $('form').serialize()+"<br /><br />$('form').serializeArray():<br />" + JSON.stringify($('form').serializeArray()));
		});
	});
</script> -->
<script src="../vendor/jquery/jquery.min.js"></script>
<input type="file" name="" value="" placeholder="" id="#sortpicture">
<button id="upload">upload</button>
<script>
'use strict';
$(function() {
	$('#upload').on('click', function() {
    var file_data = $('#sortpicture').prop('files');   
    var form_data = new FormData();                  
    form_data.append('file', file_data);
    alert(form_data);                             
    $.ajax({
                url: 'save-code.php', // point to server-side PHP script 
                dataType: 'file',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,                         
                type: 'post',
                success: function(php_script_response){
                    // alert(php_script_response); // display response from the PHP script, if any
                    console.log(php_script_response);
                }
     });
});
});
</script>





















