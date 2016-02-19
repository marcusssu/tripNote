$(document).ready( function() {
	fetch();
});
 
function fetch() {
 
		setTimeout( function() {
	 	flickrData();
		fetch();
	}, 10000);
 
}
 
function flickrData() {
 
	$.ajax({ 
 
	 url: "get_flickr_member.php",
	 dataType: "xml",
	 success: function(data) {
 
	  $("#flickr").children().remove();
 
 	  $(data).find("photo").each( function() {
	  var info = "<div class=\"item img-thumbnail2\"><img src=\"http://farm"+$(this).find('farm').text()+".static.flickr.com/"+$(this).find('server').text()+"/"+$(this).find('id').text()+"_"+$(this).find('secret').text()+".jpg\"></div>";
	   $("#flickr").append(info);
 
	  });
 
	 },
	 error: function() { $("#flickr").children().remove(); 
                             $("#flickr").append("<p>There was an error!</p>"); }
	});	
}
	 
	 
