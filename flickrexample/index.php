<?php
require_once('flickrapi.php');
echo "<script src='../js/jquery.grid-a-licious.min.js'></script>";
echo "<script src='../js/jquery.js'></script>";
$Flickr = new Flickr; 
$data = $Flickr->search('design inspiration'); 

echo $Flickr->get_id("Elizabeth Gadd");
$userid=$Flickr->get_id("Elizabeth Gadd");
$userphotos_xml=$Flickr->get_Photos($userid);

echo "count=".$userphotos_xml->photos->photo->Count();
echo "<div id='twitter_container' style='width:500px'>";
foreach($userphotos_xml->photos->photo as $photo){
	echo "<div class=\"item\">";
	echo '<img src="http://farm' . $photo["farm"] . '.static.flickr.com/' . $photo["server"] . '/' . $photo["id"] . '_' . $photo["secret"] . '.jpg">';
	echo "</div>";
}

echo "</div>";
echo "<script>
$('#twitter_container').gridalicious({
  gutter: 1,
  width: 100
  
});
</script>";
?>