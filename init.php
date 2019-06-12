<?php
$deps = array(
	'inc/settings',
	'inc/controller',
);

if( defined( 'PSP_VER' ) ) {
	foreach( $deps as $dep ) require_once( $dep . '.php' );
}
