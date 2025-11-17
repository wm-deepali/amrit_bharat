var SubLocation = function (){
	$('.location_master:last').after('<tr class="location_master"><td><input type="text" class="text-control" placeholder="Sub Location" name="SubLocation[]"></td><td><span class="locationmaster-remove remove"><i class="fa fa-minus-square"></i></span><span onClick="SubLocation()" class="locationmaster-add plus"><i class="fa fa-plus-square"></i></span></td></tr></div>');
	
	if($('.locationmaster-main tr').length==2){
		$('.locationmaster-main tr:first-child td:last-child span').hide();
	}
}
$(document).ready(function(){
	$('.locationmaster-add').on('click',SubLocation);
	$('.locationmaster-main').on('click','.locationmaster-remove',function() {
		$(this).parent().parent().remove();
		if($('.locationmaster-main tr').length==1){
			$('.locationmaster-main tr:first-child td:last-child span').show();
		}
	});	
});


$(document).ready( function () {
    $('#for_all').DataTable();
} );