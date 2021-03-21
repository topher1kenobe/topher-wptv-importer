<?php
/**
 * Plugin Name: Topher's WPTV Importer
 * Description: Grabs Topher's RSS feed from WPTV and inserts as posts
 * Version:     1.0
 * Author:      Topher
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: topher-wptv-import
 * Domain Path: /languages
 */

function topher_get_wptv_data() {

	$feed = 'ENTER FEED URL HERE';

	// Get a SimplePie feed object from the specified feed source.
	$rss = fetch_feed( esc_url( $feed ) );
	 
	$maxitems = 0;
	 
	if ( ! is_wp_error( $rss ) ) {
	 
		// Figure out how many total items there are, but limit it to 5. 
		$maxitems = $rss->get_item_quantity( 500 ); 
	 
		// Build an array of all the items, starting with element 0 (first element).
		$rss_items = $rss->get_items( 0, $maxitems );
	 
	}

	$data = [];

	foreach( $rss_items as $key => $item ) {

		$enclosures = $item->get_enclosures();

		$data[ $key ]['publishedAt'] = $item->get_date();
		$data[ $key ]['title']       = $item->get_title();
		$data[ $key ]['description'] = $item->get_description();
		$data[ $key ]['link']        = $enclosures[0]->link;
		$data[ $key ]['image']       = $enclosures[0]->thumbnails[0];
		$data[ $key ]['videoId']     = $item->get_id();
	}

	return $data;
}
add_shortcode( 'topher_get_wptv_data', 'topher_get_wptv_data' );

function topher_get_wptv_videoids() {

	global $wpdb;

	$query   = "SELECT DISTINCT `meta_value`  FROM `baf_postmeta` WHERE `meta_key` = 'videoID'";

	$results = $wpdb->get_col( $query );

	return $results;

}

function topher_save_wptv_data() {

	if ( is_user_logged_in() ) {
		return;
	}

	if( empty( $_GET['wptvimport'] ) || $_GET['wptvimport'] != 'yes' ) {
		return;
	}

	$data = topher_get_wptv_data();

	$keys = topher_get_wptv_videoids();

	foreach( $data as $video_data ) {

		// check to see if we already have this one.
		if ( in_array( $video_data['videoId'], $keys ) ) {
			continue;
		}

		$body = '[video src="' . $video_data['link'] . '" width="1280"]' . "\n";
		$body .= $video_data['description'];

		$post = [
			'post_author'    => 'topher',
			'post_date_gmt'  => $video_data['publishedAt'],
			'post_content'   => $body,
			'post_title'     => $video_data['title'],
			'post_status'    => 'publish',
			'post_type'      => 'post',
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
			'post_category'  => [ 2, 3 ],
			'meta_input'     => [ 'videoId' => $video_data['videoId'] ],
		];

		$post_id = wp_insert_post( $post );

		require_once(ABSPATH . 'wp-admin/includes/media.php');
		require_once(ABSPATH . 'wp-admin/includes/file.php');
		require_once(ABSPATH . 'wp-admin/includes/image.php');

		$attachment_id = media_sideload_image( $video_data['image'], $post_id, 'Image for ' . $video_data['title'], 'id' );

		set_post_thumbnail($post_id, $attachment_id );

	}
}
add_action( 'init', 'topher_save_wptv_data' );
