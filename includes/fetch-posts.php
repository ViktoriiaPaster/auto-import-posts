<?php

// Fetch posts
function aip_fetch_posts()
{
    $api_key = get_option('aip_api_key');

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

    if (wp_remote_retrieve_response_code($response) !== 200) {
        error_log('API Error: ' . wp_remote_retrieve_response_code($response));
        return [];
    }

    $body = wp_remote_retrieve_body($response);

    $posts = json_decode($body, true);

    return $posts;
}
