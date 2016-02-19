$(document).ready( function() {
	fetch();
});
 
function fetch() {
 
		setTimeout( function() {
	 	tweetsData();
		fetch();
	}, 10000);
 
}
 
function tweetsData() {
 
	$.ajax({ 
 
	 url: "get_tweets_member.php",
	 dataType: "xml",
	 success: function(data) {
 
	  $("#twitter").children().remove();
 
 	  $(data).find("item").each( function() {
 
 	   var info = "<div class=\"box row marginleft marginright\"><div class=\"col-xs-1\" id=\"editform\"><img src=\""+$(this).find('profile_image_url').text()+"\" alt=\"profile_image\" class=\"img-thumbnail\"></div><p class=\"inline screenname\">"+$(this).find('name').text()+"</p><a class=\"inline\" href=\"http://www.twitter.com/"+$(this).find('username').text()+"\"> @"+$(this).find('username').text()+"</a><p>"+$(this).find('twitter_text').text()+"</p><p>"+$(this).find('postedtime').text()+"               </p>";
	   $("#twitter").append(info);
 
	  });
 
	 },
	 error: function() { $("#twitter").children().remove(); 
                             $("#twitter").append("<p>There was an error!</p>"); }
	});	
}
	 
	 
