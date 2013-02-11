<?php

/*
Plugin Name: My Test Env
Description: Prepares my test environment
Version: 0.4
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

define( 'MY_TEST_ENV_VERSION', 0.4 );

function my_create_post_type() {
	load_plugin_textdomain( 'my_test_env', false, basename( dirname( __FILE__ ) ) );
	register_post_type(
		'acme_product',
		array(
			'labels' => array(
				'name' => __( 'Products', 'my_test_env' ),
				'singular_name' => __( 'Product', 'my_test_env' ),
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
			'label' => __( 'Operating System', 'my_test_env' ),
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

function my_custom_menu_item( $items, $args ) {
	if ( class_exists( 'MslsOutput' ) && 'primary' == $args->theme_location ) {
		$obj = new MslsOutput;
		/**
		 * 2 because we want just a linked icon (look at MslsLink::get_types())
		 */
		$arr = $obj->get( 2 );
		if ( !empty( $arr ) ) {
			$items .= '<li>' . implode( '</li><li>', $arr ) . '</li>';
		}
	}
	return $items;
}
add_filter( 'wp_nav_menu_items', 'my_custom_menu_item', 10, 2 );

function my_print_something() {
	$blogs  = MslsBlogCollection::instance();
	$mydata = MslsOptions::create();
	foreach ( $blogs->get_objects() as $blog ) {
		$language = $blog->get_language();
		if ( $blog->userblog_id == $blogs->get_current_blog_id() ) {
			$url = $mydata->get_current_link();
		}
		else {
			switch_to_blog( $blog->userblog_id );
			if ( 'MslsOptions' != get_class( $mydata ) && !$mydata->has_value( $language ) ) {
				restore_current_blog();
				continue;
			}
			$url = $mydata->get_permalink( $language );
			restore_current_blog();
		}
		$language = substr( $language, 0, 2 );
		printf(
			'<link rel="alternate" hreflang="%s" href="%s" />',
			( 'us' == $language ? 'en' : $language ),
			$url
		);
	}
}
add_action( 'wp_head', 'my_print_something' );
