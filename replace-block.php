<?php
function bub_replace_block($template, $post_id, $serialized_blocks_to_insert, $replacement_func = 'bub_simple_replace') {

    $serialized_blocks_to_insert = trim(preg_replace('/\s\s+/', ' ', $serialized_blocks_to_insert));

    $blocks = bub_get_blocks_by_post_id($post_id);

    $has_changes = false;

    foreach($blocks as $i => $block) {
        if (bub_block_is_a_match($block, $template)) {
            $html = call_user_func($replacement_func, $block, $serialized_blocks_to_insert);
            $blocks_to_insert = parse_blocks($html);
            if ($blocks_to_insert != [$block]) {
                $has_changes = true;
                array_splice($blocks, $i, 1, $blocks_to_insert);
            }
        }
    }

    if (!$has_changes) {
        return false;
    }

    return bub_update_blocks($post_id, $blocks);
}

/**
 * Simply return the target HTML
 *
 * @param Array $src_block source block as assoc array
 * @param string $target_html the new block HTML code
 * @return void
 */
function bub_simple_replace($src_block, $target_html) {
    return $target_html;
}