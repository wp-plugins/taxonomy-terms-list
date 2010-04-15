<?php
/*
Plugin Name: Taxonomy Terms List
Plugin URI: http://wordpress.org/extend/plugins/taxonomy-terms-list/
Description: Adds simple lists for custom taxonomies to the bottom of single post views if terms have been associated to the post in question.
Version: 0.1
Author: Michael Fields
Author URI: http://mfields.org/
License: GPLv2

Copyright 2010  Michael Fields  michael@mfields.org

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License version 2 as published by
the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

Compatible withWordPress Versions:
	- 2.9.2
	- 3.0 BETA

*/

if( !function_exists( 'pr' ) ) {
	function pr( $var ) {
		print '<pre>' . print_r( $var, true ) . '</pre>';
	}
}

if( !function_exists( 'mfields_taxonomy_terms_list' ) ) {
	add_filter( 'the_content', 'mfields_taxonomy_terms_list' );
	function mfields_taxonomy_terms_list( $c ) {
		global $post;
		$o = '';
		$terms = array();
		$lists = array();
		$custom_taxonomy_names = array();
		$custom_taxonomies = mfields_get_custom_taxonomies();
		if( !empty( $custom_taxonomies ) )
			foreach( $custom_taxonomies as $name => $config )
				$custom_taxonomy_names[] = $config->name;
		if( !empty( $custom_taxonomy_names ) )
			$terms = get_terms( $custom_taxonomy_names );
		foreach( $custom_taxonomies as $name => $config )
			$o.= get_the_term_list( $post->ID, $name, $before = '<p><span class="mfields-taxonomy-term-list-name">' . $config->label . ':</span> ', $sep = ', ', $after = '</p>' );
		if( is_single() )
			return $c . $o;
		return $c;
	}
}

if( !function_exists( 'mfields_get_custom_taxonomies' ) ) {
	function mfields_get_custom_taxonomies( ) {
		global $wp_taxonomies;
		$custom_taxonomies = array();
		$default_taxonomies = array( 'post_tag', 'category', 'link_category' );
		foreach( $wp_taxonomies as $slug => $config ) 
			if( !in_array( $slug, $default_taxonomies ) )
				$custom_taxonomies[$slug] = $config;
		return $custom_taxonomies;
	}
}

?>