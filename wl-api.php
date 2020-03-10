<?php
/**
 * Plugin Name: Custom API For SG3
 * Plugin URI: http://fb.com/rrezwan7
 * Description: A Custom API to get neccessary data to build a android app
 * Version: 1.0
 * Author: Rezwan Hossain
 * Author URI: http://fb.com/rrezwan7
 */


function wl_posts() {
	$args = [
		'numberposts' => 999,
		'post_type' => 'post'
	];

	$posts = get_posts($args);

	$data = [];
	$i = 0;

	foreach($posts as $post) {
		$data[$i]['id'] = $post->ID;
		$data[$i]['title'] = $post->post_title;
		$data[$i]['content'] = $post->post_content;
		$data[$i]['slug'] = $post->post_name;
		$data[$i]['publish_date'] = $post->post_date;
		$data[$i]['exerpt'] = $post->post_exerpt;
		$data[$i]['featured_image']['thumbnail'] = get_the_post_thumbnail_url($post->ID, 'thumbnail');
		$post_cat = wp_get_post_categories($post->ID); 
		$data[$i]['category'] = get_the_category($post_cat)[0]->slug;		
		$i++;
	}

	return $data;
}

function wl_category(){
	$args = [
		'numberposts' => 999,
		'post_type' => 'post'
	];
	
	$categories = get_category($args);
	$i = 0;

	foreach($categories as $category){
		$data[$i]['category_name'] = $category->name;
		$data[$i]['slug'] = $category->slug;
		$data[$i]['ID'] = $category->cat_ID;
		$i++;

	}
	return $data;

}

function wl_post( $slug ) {
	$args = [
		'name' => $slug['slug'],
		'post_type' => 'post'
	];

	$post = get_posts($args);


	$data['id'] = $post[0]->ID;
	$data['title'] = $post[0]->post_title;
	$data['content'] = $post[0]->post_content;
	$data['slug'] = $post[0]->post_name;
	$data['featured_image']['thumbnail'] = get_the_post_thumbnail_url($post[0]->ID, 'thumbnail');
	$data['featured_image']['medium'] = get_the_post_thumbnail_url($post[0]->ID, 'medium');
	$data['featured_image']['large'] = get_the_post_thumbnail_url($post[0]->ID, 'large');

	return $data;
}

add_action('rest_api_init', function() {
	register_rest_route('wl/v1', 'posts', [
		'methods' => 'GET',
		'callback' => 'wl_posts',
	]);

	register_rest_route( 'wl/v1', 'posts/(?P<slug>[a-zA-Z0-9-]+)', array(
		'methods' => 'GET',
		'callback' => 'wl_post',
	) );
	register_rest_route ('wl/v1', 'cats', [
		'methods' => 'GET',
		'callback' => 'wl_category'
	]);
});
