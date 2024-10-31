<?php
function rocktoAutosharePost($post_ID, $cron = false) {
	if (!function_exists("curl_init")) {
		return;
	}
	$rocktoAutosharePath = __FILE__;
	$rocktoAutosharePath = substr($rocktoAutosharePath, 0,  strrpos($rocktoAutosharePath, DIRECTORY_SEPARATOR));
	$rocktoCookieFile = $rocktoAutosharePath.DIRECTORY_SEPARATOR."rocktoSessionData.txt";
	$fbLongUrl = true;
	
	$post = get_post($post_ID);
	if ($fbLongUrl) {
		$postUrl = get_permalink($post_ID);
	} else {
		$postUrl = get_bloginfo("wpurl")."/?p=".$post_ID;
	}
	$postTitle = $post->post_title;
	$postSummary = strip_tags($post->post_excerpt);
	if (trim($postSummary."") == "") {
		$postSummary = substr(strip_tags($post->post_content), 0, 500);
	}
	$postSummary = str_replace("\n", " ", $postSummary);
	$postSummary = trim(preg_replace('/\s+/', " ", $postSummary))."...";
	$postCategory = get_the_category( $post_ID );
	$cloudT = "";
	foreach($postCategory as $tag) { 
		$cloudT .= $tag->cat_name.";";
	}
	$postContent = $post->post_content;
	$postStatus = $post->post_status;
	unset($post);
	
	if ($postStatus == "future" || $postStatus == "draft" || $postStatus == "private") {
		if ($fbDebug) {
			sendLogEmail($fbLogEmail);
		}
		return;
	}
	
	if($postUrl && $postTitle && $postSummary && is_array($postCategory)){
		$handle = fopen($rocktoCookieFile, 'r');
		$contents = fread($handle, filesize($rocktoCookieFile));
		fclose($handle);
		if($contents){
			$spl = explode(";",$contents);
			$userID = $spl[1];
			$cate = explode("##",$spl[4]);
			$category = $cate[0];
			if($userID && $category){
				$userAgent = 'ROCKTO;wp_plugins;1';
				$postdata = NULL;
				$postdata = "key=post&posted=".$postUrl."&uname=".$spl[1]."&pass=".$spl[2]."&title=".$postTitle."&desc=".$postSummary."&cateID=".$category."&tag=".$cloudT;
				 $ch1 = curl_init("http://widget.rockto.com/wpPlugins");
				  curl_setopt ($ch1, CURLOPT_USERAGENT, $userAgent);
				  curl_setopt ($ch1, CURLOPT_POST, 1);
				  curl_setopt ($ch1, CURLOPT_POSTFIELDS, $postdata);
				  curl_setopt($ch1, CURLOPT_FOLLOWLOCATION, 1);
				  curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
				  $r=curl_exec ($ch1);$ch1_info=curl_getinfo($ch1);
				  if (curl_errno($ch1)) echo curl_error($ch1);
				  else curl_close($ch1);
				  #header("Content-type: text/html");
			}
		}
	}
}
?>