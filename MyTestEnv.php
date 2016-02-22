<?php

/*
Plugin Name: My Test Env
Description: Prepares my test environment
Version: 0.6
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

add_action( 'init', function () {
	load_plugin_textdomain( 'my_test_env', false, basename( dirname( __FILE__ ) ) );
	register_post_type(
		'acme_product',
		[
			'labels'      => [
				'name'          => __( 'Products', 'my_test_env' ),
				'singular_name' => __( 'Product', 'my_test_env' ),
			],
			'public'      => true,
			'has_archive' => true,
			'rewrite'     => [ 'slug' => __( 'products', 'my_test_env' ) ],
		]
	);
	register_taxonomy(
		'operating_system',
		'acme_product',
		[
			'hierarchical' => true,
			'label'        => __( 'Operating System', 'my_test_env' ),
			'query_var'    => true,
			'rewrite'      => true,
		]
	);
}, 10, 0 );

add_filter( 'pre_get_posts', function ( $query ) {
	if ( is_home() ) {
		$query->set( 'post_type', [ 'post', 'acme_product' ] );
	}

	return $query;
} );
