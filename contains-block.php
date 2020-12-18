<?php
function bub_contains_block($template, $post_id, $depth = -1) {
    $blocks = bub_get_blocks_by_post_id($post_id);
    foreach($blocks as $block) {
        if (bub_block_is_a_match($block, $template, $depth)) {
            return true;
        }
    }
    return false;
}