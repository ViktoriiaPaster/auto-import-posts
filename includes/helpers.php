<?php

// Set post author
function aip_author_id()
{
    $args = array(
        'role' => 'administrator',
        'number' => 1
    );

    $authors = get_users($args);

    return !empty($authors) && is_array($authors) ? $authors[0]->ID : 1;
}

// Set post date
function aip_random_date()
{
    $date = rand(strtotime('-1month'), time());
    $random_date = date('Y-m-d H:i:s', $date);

    return $random_date;
}

// Set post category
function aip_category_id($category)
{
    $term = term_exists($category, 'category');

    if (!$term) {
        $category_id = wp_create_category($category);
    } else {
        $category_id = is_array($term) ? $term['term_id'] : $term;
    }

    return $category_id;
}

// Upload image and set thumbnail
function aip_upload_image($image_url, $post_id)
{
    $attachment_id = media_sideload_image($image_url, $post_id, null, 'id');

    if (is_wp_error($attachment_id)) {
        error_log('Image upload failed:' . $attachment_id->get_error_message());
    }

    set_post_thumbnail($post_id, $attachment_id);

    return $attachment_id;
}
