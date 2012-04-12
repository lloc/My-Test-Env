<?php

/*
Plugin Name: My Test Env
Description: Prepares my test environment
Version: 0.2
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

define( 'MY_TEST_ENV_VERSION', 0.2 );

function my_create_post_type() {
    register_post_type(
        'acme_product',
        array(
            'labels' => array(
                'name' => __( 'Products' ),
                'singular_name' => __( 'Product' ),
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array( 'slug' => 'products' ),
        )
    );
}
add_action( 'init', 'my_create_post_type', 10, 0 );

function my_build_taxonomies() {  
    register_taxonomy(
        'operating_system',
        'acme_product',
        array(
            'hierarchical' => true,
            'label' => __( 'Operating System' ),
            'query_var' => true,
            'rewrite' => true,
        )
    );
}
add_action( 'init', 'my_build_taxonomies', 10, 0 );

function my_refresh_plugin() {
    if ( MY_TEST_ENV_VERSION != get_option( 'MY_TEST_ENV_VERSION' ) ) {
        update_option( 'MY_TEST_ENV_VERSION', MY_TEST_ENV_VERSION );
        flush_rewrite_rules( false );
    }
}
add_action( 'init', 'my_refresh_plugin', 11, 0 );  

function my_get_posts( $query ) {
    if ( is_home() )
        $query->set( 'post_type', array( 'post', 'acme_product' ) );
    return $query;
}
add_filter( 'pre_get_posts', 'my_get_posts' );

?>
