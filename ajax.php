<?php

add_action( 'wp_ajax_bub_insert_blocks', 'bub_ajax_insert_blocks' );

function bub_ajax_insert_blocks() {

	$blocks = stripslashes($_POST['bub-blocks']);
	$posts = sanitize_text_field($_POST['bub-posts']);
	$position = intval($_POST['bub-position']);

	$post_ids = bub_get_post_IDs($posts);	

	$updated = [];
	$failed = [];

	foreach($post_ids as $post_id) {
		$inserted = insert_blocks($blocks, $post_id, $position);

		if ($inserted) {
			$updated[] = $post_id;
		} else {
			$failed[] = $post_id;
		}
	}

	echo json_encode([
		'updatedPosts' => $updated,
		'failedPosts' => $failed,
	]);

	wp_die();
}

add_action( 'wp_ajax_bub_delete_block', 'bub_ajax_delete_block' );
function bub_ajax_delete_block() {

	$block_template = json_decode(stripslashes($_POST['bub-block']),true);

	$posts = sanitize_text_field($_POST['bub-posts']);

	$post_ids = bub_get_post_IDs($posts);	

	$updated = [];
	$failed = [];

	foreach($post_ids as $post_id) {
		$deleted = bub_delete_block($block_template, $post_id);

		if ($deleted) {
			$updated[] = $post_id;
		} else {
			$failed[] = $post_id;
		}
	}

	echo json_encode([
		'updatedPosts' => $updated,
		'failedPosts' => $failed,
	]);

	wp_die();
}

add_action( 'wp_ajax_bub_find_posts_containing_block', 'bub_ajax_find_posts_containing_block' );
function bub_ajax_find_posts_containing_block() {

    $block_template = json_decode(stripslashes($_POST['bub-block']),true);
	$posts = sanitize_text_field($_POST['bub-posts']);

	$depth = trim(sanitize_text_field($_POST['bub-depth']));
	$depth = $depth === '' ? -1 : intval($depth);

	$post_ids = bub_get_post_IDs($posts);	

	$found_posts = [];

	foreach($post_ids as $post_id) {
		$found = bub_contains_block($block_template, $post_id, $depth);

		if ($found) {
			$found_posts[] = $post_id;
		}
	}

	echo json_encode([
		'foundPosts' => $found_posts,
	]);

	wp_die();
}

add_action( 'wp_ajax_bub_replace_block', 'bub_ajax_replace_block' );
function bub_ajax_replace_block() {

    $block_template = json_decode(stripslashes($_POST['bub-block']),true);
	$blocks_to_replace = stripslashes($_POST['bub-blocks-to-replace']);
	$replace_func = sanitize_text_field($_POST['bub-replacement-function']);
	if (!function_exists($replace_func)) {
		wp_die('function '.$replace_func.' does not exist');
	}
	$posts = sanitize_text_field($_POST['bub-posts']);

	$post_ids = bub_get_post_IDs($posts);	

	$updated = [];
	$failed = [];

	foreach($post_ids as $post_id) {
		$replaced = bub_replace_block($block_template, $post_id, $blocks_to_replace, $replace_func);

		if ($replaced) {
			$updated[] = $post_id;
		} else {
			$failed[] = $post_id;
		}
	}

	echo json_encode([
		'updatedPosts' => $updated,
		'failedPosts' => $failed,
	]);

	wp_die();
}