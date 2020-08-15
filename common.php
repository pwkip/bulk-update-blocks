<?php

function bub_serialize_block_template( $block ) {
	$block_content = '';

    if (isset($block[2])) {
        $index = 0;
        foreach ( $block[2] as $chunk ) {
            $block_content .= is_string( $chunk ) ? $chunk : bub_serialize_block_template( $block[2][ $index++ ] );
        }
    }

	return get_comment_delimited_block_content(
		$block[0],
		$block[1],
		$block_content
	);
}

function bub_get_blocks_by_post_id($post_id) {

    // return if post does not exist
    $post = get_post($post_id);
    if (!$post) return false;

    return parse_blocks( $post->post_content );
    
}

function bub_update_blocks($post_id, $blocks) {

    global $post;

    // return if post does not exist
    $p = get_post($post_id);
    if (!$p) return false;

    $post = $p;

    setup_postdata($post);

    $post->post_content = serialize_blocks($blocks);
   
    // Update the post into the database
    $success = wp_update_post( $post );

    wp_reset_postdata();

    return $success;
}

function bub_block_is_a_match($block, $name, $attrs) {
    if ($block['blockName'] !== $name) {
        return false;
    }
    foreach ($attrs as $attr_name => $attr_val) {
        if (
            !in_array($attr_name, array_keys($block['attrs'])) ||
            $block['attrs'][$attr_name] != $attr_val
        ) {
            return false;
        }
    }
    return true;
}