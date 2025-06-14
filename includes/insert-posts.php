<?php

// Insert post
function aip_insert_post($post)
{
    $author_id = aip_author_id();
    $date = aip_random_date();
    $category_id = aip_category_id($post['category']);

    $post_data = array(
        'post_title' => $post['title'],
        'post_content' => $post['content'],
        'post_status' => 'publish',
        'post_author' => $author_id,
        'post_category' => array($category_id),
        'post_date' => $date,
    );

    $post_id = wp_insert_post($post_data);

    if (!$post_id) {
        error_log('Insert post failed:' . $post['title']);
        return;
    }

    if (!empty($post['rating'])) {
        update_post_meta($post_id, 'rating', $post['rating']);
    }

    if (!empty($post['site_link'])) {
        update_post_meta($post_id, 'site_link', $post['site_link']);
    }

    if (!empty($post['image'])) {
        aip_upload_image($post['image'], $post_id);
    }
}
