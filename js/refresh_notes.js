$(document).ready( function() {
	fetch();
});
 
function fetch() {
 
		setTimeout( function() {
	 	notesData();
		fetch();
	}, 10000);
 
}
 
function notesData() {
 
	$.ajax({ 
 
	 url: "get_notes.php",
	 dataType: "xml",
	 success: function(data) {
 
	  $("#notes").children().remove();
 
 	  $(data).find("note").each( function() {
 	  	var info = "<div class=\"box\"><p style=\"display:inline\">"+$(this).find('textarea').text()+"</p><br><br><p  class='info' style=\"display:inline\";>From: "+$(this).find('departure').text()+" </p><p  class='info' style=\"display:inline\";>To: "+$(this).find('arrive').text()+" </p><p  class='info' style=\"display:inline\";>In: "+$(this).find('season').text()+" </p><p class='info' style=\"display:inline\";>posted at: "+$(this).find('date').text()+"</p><br><a style=\"display:inline\" href=\"deletenote.php?id=\""+$(this).find('id').text()+"\" onclick=\"return confirm('Are you sure?');\">Delete</a><a style=\"display:inline\" href=\"editnote.php?id=\""+$(this).find('id').text()+"\"> Edit</a></div>";
	   $("#notes").append(info);
 
	  });
 
	 },
	 error: function() { $("#notes").children().remove(); 
                             $("#notes").append("<p>There was an error!</p>"); }
	});	
}
	 
	 
