<?php
function bub_contains_block($template, $post_id) {
    $blocks = bub_get_blocks_by_post_id($post_id);
    foreach($blocks as $block) {
        if (bub_block_is_a_match($block, $template)) {
            return true;
        }
    }
    return false;
}