<?php

function rocktoAutoshareOptionPage(){
	#init this and that
	$rocktoAutosharePath = __FILE__;
	$rocktoAutosharePath = substr($rocktoAutosharePath, 0,  strrpos($rocktoAutosharePath, DIRECTORY_SEPARATOR));
	$rocktoCookieFile = $rocktoAutosharePath.DIRECTORY_SEPARATOR."rocktoSessionData.txt";
	$rocktoAutoshareBaseUrl = trailingslashit(get_bloginfo('wpurl')).PLUGINDIR.'/'.dirname(plugin_basename(__FILE__));
	$error = "";
	
	if(isset($_POST['uname'])){
		$data['uname'] = $_POST['uname'];
		$data['pass'] = md5($_POST['pwd']);
		$userAgent = 'ROCKTO;wp_plugins;1';
		$postdata = NULL;
   		$postdata = "uname=".$data['uname']."&pass=".$data['pass']."&key=account";
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
		  if($r!="0"){
			 $r = ";".$data['uname'].";".$data['pass'].$r;
			//echo $r;
			if (is_writable($rocktoCookieFile)) {

				// In our example we're opening $filename in append mode.
				// The file pointer is at the bottom of the file hence
				// that's where $somecontent will go when we fwrite() it.
				if (!$handle = fopen($rocktoCookieFile, 'w')) {
					$error = "Cannot open file ($rocktoCookieFile)";
					exit;
				}
			
				// Write $somecontent to our opened file.
				if (fwrite($handle, $r) === FALSE) {
					$error = "Cannot write to file ($rocktoCookieFile)";
					exit;
				}
			
				fclose($handle);
		 		//header("Location: " . $_SERVER['PHP_SELF']);
			
			} else {
				$error = "The file $rocktoCookieFile is not writable";
			}
			
		  }else{
			  $error = "Invalid username or password. Please try again.";
		  }
	}
	
	if(isset($_POST['action'])){
		switch($_POST['action']){
			case 'save_cate':
				$cate_id = $_POST['cate'];
				if($cate_id){
					$handle = fopen($rocktoCookieFile, 'r');
					$contents = fread($handle, filesize($rocktoCookieFile));
					fclose($handle);
					if($contents){
						$sp = explode(";",$contents);
						$sp[4] = $cate_id;
						$contents = implode(";",$sp).";";
						if (is_writable($rocktoCookieFile)) {
							if (!$handle = fopen($rocktoCookieFile, 'w')) {
								$error = "Cannot open file ($rocktoCookieFile)";
								 exit;
							}
						
							// Write $somecontent to our opened file.
							if (fwrite($handle,$contents) === FALSE) {
								$error = "Cannot write to file ($rocktoCookieFile)";
								exit;
							}
						
							fclose($handle);
							//header("Location: " . $_SERVER['PHP_SELF']);
						} else {
							$error = "The file $rocktoCookieFile is not writable";
						}
					}
				}
				break;
			case 'logout':
				if (is_writable($rocktoCookieFile)) {
		
					// In our example we're opening $filename in append mode.
					// The file pointer is at the bottom of the file hence
					// that's where $somecontent will go when we fwrite() it.
					if (!$handle = fopen($rocktoCookieFile, 'w')) {
						$error = "Cannot open file ($rocktoCookieFile)";
						 exit;
					}
				
					// Write $somecontent to our opened file.
					if (fwrite($handle,"") === FALSE) {
						$error = "Cannot write to file ($rocktoCookieFile)";
						exit;
					}
				
					fclose($handle);
					//header("Location: " . $_SERVER['PHP_SELF']);
				
				} else {
					$error = "The file $rocktoCookieFile is not writable";
				}
				break;
		}
	}
	
	if(!$contents){
		$handle = fopen($rocktoCookieFile, 'r');
		$contents = fread($handle, filesize($rocktoCookieFile));
		fclose($handle);
	}
	
	
	if($contents){
		$userAgent = 'ROCKTO;wp_plugins;1';
		$postdata = NULL;
   		$postdata = "key=category";
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
		  if($r!="0"){	
		  	$cate = explode(";",$r);
		  }
	}
?>	
	
	<form method="post" autocomplete="off">
	<div class="wrap">
		<h2>Rockto Autoshare</h2>
        <div id="poststuff" class="metabox-holder">
        	<div class="postbox">
				<h3 class="hndle"><span>Info</span></h3>
				<div class="inside">
					<p>The "Rockto Autoshare" plugin shares this blog posts to your Rockto account.</p>
                    <p><strong>The plugin requires Php4 or higher, Curl and Json libraries.</strong><br/> Ensure the <strong>rocktoSessionData.txt</strong> file, in <strong>the /wp-content/plugins/rockto-auto-share/</strong> directory is writable from PHP</p>
					<!--
					<p>
						In case you don't trust the Wordpress Cron Job emulation, you may set up the cron job on your server (or use an external, cheap and excelent service like <a href="http://www.setcronjob.com/" target="_blank">SetCronJob</a>) with the folowing url:<br />
						<input type="text" id="cron-job-url" name="cron-job-url" value="<echo($fbStatusBaseUrl); ?>/fb-status-updater.php?cron=true" onclick="this.select()" style="width:300px;" />					
					</p>
					-->
                    <div style="clear:both"></div>
				</div>
			</div>
            
            <div class="postbox">
				<h3 class="hndle"><span>Rockto Account</span></h3>
				<div class="inside">
                	<table class="form-table">
                   <!-- < if (!function_exists("curl_init") && !function_exists("curl_setopt") && !function_exists("curl_exec") && !function_exists("curl_close")) {
                    	 <tr valign="top">
                                <td colspan="2">
                                    <p style="color:red;">This plugin requires <a href="http://www.php.net/curl">Curl library</a> in order to run properly. Install curl or disable this plugin</p>
                                </td>
                            </tr>
                    else { >-->
						<?php if ($error){ ?>
                            <tr valign="top">
                                <td colspan="2">
                                    <p style="color:red;"><?php echo $error?></p>
                                </td>
                            </tr>
                        <?php } ?>
                        <?php if($contents){ 
                            $sp = explode(";",$contents);
                            $username = $sp[3];
                            $cat = (isset($sp[4])) ? $sp[4] : "";
                        ?>
                            
                            <tr valign="top">
                                <td colspan="2">
                                    <p>Congratulations <strong><?php echo $username?></strong>, You've successfully connected with your rockto account. <input type="hidden" name="action" value="logout" id="action"/><input type="submit" value="logout"/></p>
                                    <?php if($cat){
                                        $c = explode("##",$cat);
                                        $catename = $c[1];	
                                    ?>
                                        
                                        <p>You have chosen <strong><?php echo $catename?></strong> as your default Category (Your Wordpress Categories will be set as tags). <br/>To change please choose of Category below:</p>
                                    <?php } else { ?>
                                        <p>Please choose one Category below as your default Posted Category (Your Wordpress Categories will be set as tags):</p>
                                    
                                    <?php } ?>
                                        <select name="cate">
                                            <option value="">Select Category</option>
                                            <?php foreach($cate as $row){ 
                                                if($row){
                                                    $sp = explode("##",$row);
                                                    $id = $sp[0];
                                                    $name = $sp[1];
                                            ?>
                                                <option value="<?php echo $row?>"><?php echo $name?></option>
                                            <?php	} 
                                            } ?>
                                        </select>&nbsp;<input type="submit" value="saved" name="save_cate" onclick="jQuery('#action').val('save_cate');"/>
                                </td>
                            </tr>
                        <?php } else { ?>
                            <tr valign="top">
                                <td colspan="2">
                                    <p>In order to connect your blog to your Rockto Account, you have to login with your registered Rockto Account below:</p>
                                </td>
                            </tr>
                            <tr valign="top">
                                <td colspan="2"><p>Username: <input type="text" name="uname"/></p></td>
                            </tr>
                            <tr valign="top">
                                <td colspan="2"><p>Password: <input type="password" name="pwd"/></p></td>
                            </tr>                        
                            <tr valign="top">
                                <td colspan="2"><input type="submit" value="Connect"/></td>
                            </tr>             
                            <tr valign="top">
                                <td colspan="2"><p>Don't have Rockto Account? <a href="http://www.rockto.com/register" target="_blank">Register here</a></p></td>
                            </tr>
                            <!--<tr valign="top">
                                <td colspan="2">
                                    <p>Or use your Facebook/Twitter account that already connected to Rockto</p>
                                </td>
                            </tr>
                            <tr valign="top">
                                <td colspan="2">
                                    <img src="<echo($rocktoAutoshareBaseUrl); ?>/fb-connect.jpg" alt="Sign in with Facebook"/>
                                    <img src="<echo($rocktoAutoshareBaseUrl); ?>/lighter.png" alt="Sign in with Twitter"/>
                                </td>
                            </tr>-->
                        <?php }?>
					<!--} -->
                    </table>
               	</div>
            </div>
        </div>
    </div>
    </form>
<?php
}
?>