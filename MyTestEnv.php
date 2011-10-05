<?php

/*
Plugin Name: My Test Env
Description: Prepares my test environment
Version: 0.1a
Author: Dennis Ploetner 
Author URI: http://lloc.de/
*/

/*
Copyright 2011  Dennis Ploetner  (email : re@lloc.de)

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

add_action( 'init', 'create_post_type' );
function create_post_type() {
	register_post_type( 'acme_product',
		array(
			'labels' => array(
				'name' => __( 'Products' ),
				'singular_name' => __( 'Product' )
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'products')
		)
	);
}

function mte_display_actions() {
    global $wp_actions;
    print_r( $wp_actions );
}

function mte_display_filter() {
    global $wp_filter;
    print_r( $wp_filter );
}

function mte_display_current_filter() {
    global $wp_current_filter;
    print_r( $wp_current_filter );
}

?>
