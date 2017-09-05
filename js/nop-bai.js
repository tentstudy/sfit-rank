'use strict';
$(document).ready(() => {
    $('#choose-file').on('change', (e) => {
        $('#choosed-file').text($(e.target).val().split("\\")[2]);
    });
});
function saveCode() {
	// console.log('ngu');
// 	var file = $("#choose-file").attr('files');
// 	// console.log(file);
// 	$.post('save-code.php', $('form#socola').serialize(), function(res) {
// 		console.log(res);
// 	});
	console.log($('#socola').serializeArray());
}
/*sự kiện*/
$(function() {
	$("#submit").click(saveCode);
});