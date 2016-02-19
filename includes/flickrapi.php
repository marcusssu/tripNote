<?php 

class Flickr { 
	private $apiKey = '864dcbc35c8d2bd200b7ee469372458a';
	private $auth_token = '72157650937186249-6831f2f38709df09';
	private $per_page = 10;
	
	
	public function __construct() {
	} 
	//test 
	public function search($query = null) { 
		$search = 'http://flickr.com/services/rest/?method=flickr.photos.search&api_key=' . $this->apiKey . '&text=' . urlencode($query) . '&per_page=50&format=php_serial'; 
		$result = file_get_contents($search); 
		$result = unserialize($result); 
		return $result; 
	}

	public function get_id($screenName = null) {
    // get user id from screen name
		$user_id_url = "http://flickr.com/services/rest/?method=flickr.people.findByUsername"."&username=".urlencode($screenName)."&api_key=".$this->apiKey;
		
		//$user_id_url ='https://api.flickr.com/services/rest/?method=flickr.people.findByUsername&api_key=3744c474e0f580a88addfc691ea7ac79&username=Jane+Fu&format=rest&auth_token=72157650937186249-6831f2f38709df09&api_sig=c876d7d4626e6a76444d2bb186d92fee';
  	$user_id_xml = simplexml_load_file($user_id_url) or die("Unable to contact Flickr");
  	// if the user id can be retreived
		if ($user_id_xml->user->Count() > 0) {
			$user_id = $user_id_xml->user->attributes()->id;
			return $user_id;
		} else {
			return NULL;
		}
	}

	public function get_Photos($id=null){
		$flickr_public_photos_url ="http://flickr.com/services/rest/?method=flickr.people.getPublicPhotos"."&user_id=".$id."&api_key=".$this->apiKey."&per_page=".$this->per_page;
		$flickr_public_photos_xml = simplexml_load_file($flickr_public_photos_url) or die("Unable to contact Flickr");
		return $flickr_public_photos_xml;
}


}


?>