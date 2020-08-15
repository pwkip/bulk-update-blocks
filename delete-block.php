<?php
function bub_delete_block($name, $attrs, $post_id) {
    $blocks = bub_get_blocks_by_post_id($post_id);
    $updated_blocks = [];
    foreach($blocks as $block) {
        if (!bub_block_is_a_match($block, $name, $attrs)) {
            $updated_blocks[] = $block;
        }
    }

    return bub_update_blocks($post_id, $updated_blocks);
}