<?php
/*
 Plugin Name: etivite Action & Filter API Viewer
 Plugin URI: 
 Description: bbPress and BuddyPress API Hook Viewer
 Version: 0.0.5
 Author: rich @ etivite
 Author URI: http://buddypress.org/developers/etivite/
*/

define('HOOK_BBP_VERSION', '2.0');
define('HOOK_BP_VERSION', '1.5');

require( dirname( __FILE__ ) . '/etivite-hookviewer.php' );

if ( is_admin() ) {
	require( dirname( __FILE__ ) . '/etivite-hookviewer-import.php' );	
}



?>
