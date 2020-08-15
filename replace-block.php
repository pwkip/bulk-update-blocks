<?php
function bub_replace_block($name, $attrs, $post_id, $serialized_blocks_to_insert) {

    $blocks_to_insert = parse_blocks($serialized_blocks_to_insert);

    // return if there were no blocks passed.
    // If the intention was to replace a block with nothing,
    // use bub_delete_block instead.
    if ( 0 === count($blocks_to_insert) ) return false;

    $blocks = bub_get_blocks_by_post_id($post_id);

    foreach($blocks as $i => $block) {
        if (bub_block_is_a_match($block, $name, $attrs)) {
            array_splice($blocks, $i, 1, $blocks_to_insert);
        }
    }

    return bub_update_blocks($post_id, $blocks);
}