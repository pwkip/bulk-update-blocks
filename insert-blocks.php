<?php

/**
 * Insert blocks in a post at a specified position
 *
 * @param [type] $serialized_blocks_to_insert
 * @param [type] $post_id
 * @param integer $position the position where the block should be insterted. 1 means it should be the first block. -1 means append at the end of the post.
 * @return void
 */
function insert_blocks($serialized_blocks_to_insert, $post_id, $position=-1) {

    $blocks_to_insert = parse_blocks($serialized_blocks_to_insert);

    // return if there were no blocks passed
    if ( 0 === count($blocks_to_insert) ) return false;

    $blocks = bub_get_blocks_by_post_id($post_id);

    // return if post does not exist, or if no blocks were found in the post
    if (!$blocks) return false;

    if ($position !== 0) {
        if ($position < 0) {
            $position = count($blocks)+$position+1;
        } else {
            $position = $position - 1;
        }
    }

    array_splice($blocks, $position, 0, $blocks_to_insert);

    return bub_update_blocks($post_id, $blocks);

}