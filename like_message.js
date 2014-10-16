
$( document ).ready(function() {
	$("#liked_msg").hide();
	$("#unliked_msg").hide();

	$(".like_button").on("click", function() {

	var id = $(".like_button").attr("id");
	var url = $(".like_button").attr("href");

		$.post(url, 
		{
			like_id:id
		}
		, function(data) {
			$(".like_box").append("<strong>Liked!</strong>");	
			$("#liked_msg").show().delay(5000).fadeOut();
				
		} );

	});

});


$( document ).ready(function() {

	$(".unlike_button").on("click", function() {

	var unid = $(".unlike_button").attr("id");
	var url = $(".unlike_button").attr("href");

		$.post(url, 
		{
			unlike_id:unid
		}
		, function(data) {
			$(".unlike_box").append("<strong>Disliked!</strong>");	
			$("#unliked_msg").show().delay(5000).fadeOut();

		} );
	});

});
