<?php

// Define API_KEY constant if not defined
if (!defined('API_KEY')) {
    define('API_KEY', '');
}

// Fetch posts
function aip_fetch_posts()
{
    $api_key = defined('API_KEY') ? API_KEY : '';

    if (empty($api_key)) {
        error_log('API Error: API key is not defined');
        return [];
    }

    $url = 'https://my.api.mockaroo.com/posts.json';

    $response = wp_remote_get($url, array(
        'headers' => array(
            'X-API-Key' => $api_key,
        )
    ));

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        error_log('API Error:' . $error_message);
        return [];
    }

    $body = wp_remote_retrieve_body($response);

    $posts = json_decode($body);

    return $posts;
}

// Set post author
function aip_set_author()
{
    $args = array(
        'role' => 'administrator',
        'number' => 1
    );

    $authors = get_users($args);

    return !empty($authors) && is_array($authors) ? $authors[0]->ID : 1;
}

// Set post date
function aip_set_date()
{
    $date = rand(strtotime('-1month'), time());
    $random_date = date('Y-m-d H:i:s', $date);

    return $random_date;
}

// Set post category
function aip_set_category($category)
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

// Insert post
function aip_insert_post($array)
{
    $title = $array['title'];
    $content = $array['content'];
    $category = $array['category'];
    $rating = $array['rating'];
    $site_link = $array['site_link'];
    $image = $array['image'];

    $author_id = aip_set_author();
    $date = aip_set_date();
    $category = aip_set_category($category);

    $post_data = array(
        'post_title' => $title,
        'post_content' => $content,
        'post_status' => 'publish',
        'post_author' => $author_id,
        'post_category' => array($category),
        'post_date' => $date,
    );

    $post_id = wp_insert_post($post_data);

    if (!$post_id) {
        error_log('Insert post failed:' . $title);
        return;
    }

    if (!empty($rating)) {
        update_post_meta($post_id, 'rating', $rating);
    }

    if (isset($site_link) && $site_link !== '') {
        update_post_meta($post_id, 'site_link', $site_link);
    }

    if (isset($image) && $image !== '') {
        aip_upload_image($array['image'], $post_id);
    }
}
