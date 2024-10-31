<?php
	if(isset($_POST)){
		$this->doForm($_POST);	
		echo '<br/>POST<br/>';
		echo '<pre>';
		print_r($_POST);
		echo '</pre>';
	}
	
	if(isset($_REQUEST)){
		echo '<br/>REQUEST<br/>';
		echo '<pre>';
		print_r($_REQUEST);
		echo '</pre>';		
	}
	
	function doForm($data=''){
		echo '<pre>';
		print_r($data);
		echo '</pre>';	
	}
?>