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

function create_post_type() {
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
add_action( 'init', 'create_post_type' );

function build_taxonomies() {  
    register_taxonomy(
        'operating_system',
        'acme_product',
        array(
            'hierarchical' => true,
            'label' => 'Operating System',
            'query_var' => true,
            'rewrite' => true,
        )
    );
}
add_action( 'init', 'build_taxonomies', 0 );  

function my_get_posts( $query ) {
    if ( is_home() )
        $query->set( 'post_type', array( 'post', 'acme_product' ) );
    return $query;
}
add_filter( 'pre_get_posts', 'my_get_posts' );

function my_msls_blog_collection_get( $objects ) {
    $objects = array();
    $blogs   = array( 1 => 'Override English', 2 => 'Override Deutsch', );
    foreach ( $blogs as $id => $description ) {
        $details = get_blog_details( $id );
        if ( is_object( $details ) ) {
            $objects[$id] = new MslsBlog( $details, $description );
        }
    }
    return $objects;
}
add_filter( 'msls_blog_collection_construct', 'my_msls_blog_collection_get' );

?>
