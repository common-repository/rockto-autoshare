<?php
/*
Plugin Name: Rockto Autoshare
Plugin URI: http://www.rockto.com/download/WP_Plugins/rockto-auto-share.zip
Description: Shares wordpress posts to your ROCKTO Accounts
Author: Errol Widhavian
Version: 1.0
Author URI: http://www.rockto.com/delpierrol
*/

/*  Copyright 2011  Errol Widhavian  (email : errol.widhavian@rockto.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


include_once('inc-rockto-auto-share-option.php');
include_once('inc-rockto-auto-share-updater.php');

function addRocktoAutoshareOptionPage() {
	add_options_page('Rockto Autoshare', 'Rockto Autoshare', 9, basename(__FILE__), "rocktoAutoshareOptionPage");
}

add_action('admin_menu', 'addRocktoAutoshareOptionPage');

add_action("publish_post", "rocktoAutosharePost", 15);
?>