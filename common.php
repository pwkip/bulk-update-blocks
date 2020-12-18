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

    $serialized_blocks = serialize_blocks($blocks);

    if ($post->post_content == $serialized_blocks) {
        return false; // no need to update
    }
    
    $post->post_content = $serialized_blocks;
   
    // Update the post into the database
    $success = wp_update_post( $post );

    wp_reset_postdata();

    return $success;
}

function bub_block_is_a_match($block, $template, $depth = 0) {

    $name = $template[0];

    // first check if the current block is a match

    if ( $block['blockName'] === $name) {

        $attrs = isset($template[1]) ? $template[1] : [];

        $all_atts_match = true;

        foreach ($attrs as $attr_name => $attr_val) {
            if (
                !in_array($attr_name, array_keys($block['attrs'])) ||
                $block['attrs'][$attr_name] != $attr_val
            ) {
                $all_atts_match = false;
                break;
            }
        }

        if ($all_atts_match) {
            return true;
        }

    }

    // then check all innerBlocks

    if ($depth !== 0) {
        foreach ($block['innerBlocks'] as $innerBlock) {
            $is_match = bub_block_is_a_match($innerBlock, $template, $depth-1);
            if ($is_match) {
                return true;
            }
        }
    }

    // if we reach this point, there's no match

    return false;
}

function bub_block_is_a_match_by_template($block, $template) {
    return bub_block_is_a_match($block, $template[0],$template[1]);
}