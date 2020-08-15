<?php
function bub_contains_block($name, $attrs, $post_id) {
    $blocks = bub_get_blocks_by_post_id($post_id);
    foreach($blocks as $block) {
        if (bub_block_is_a_match($block, $name, $attrs)) {
            return true;
        }
    }
    return false;
}