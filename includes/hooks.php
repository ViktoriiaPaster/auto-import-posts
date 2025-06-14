<?php

// Load admin files
function aip_load_admin_files()
{
    require_once ABSPATH . 'wp-admin/includes/post.php';
    require_once ABSPATH . 'wp-admin/includes/taxonomy.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';
}

// Schedule init get posts
function aip_init_posts_schedule()
{
    if (!wp_next_scheduled('aip_cron_hook')) {
        wp_schedule_event(time(), 'daily', 'aip_cron_hook');
    }
}

// Schedule remove posts
function aip_remove_posts_schedule()
{
    wp_clear_scheduled_hook('aip_cron_hook');
}

// Insert posts
function aip_handle_insert_posts()
{
    $posts = aip_fetch_posts();

    if (empty($posts) || !is_array($posts)) {
        return;
    }

    foreach ($posts as $post) {
        $title = $post['title'];

        if (!post_exists($title)) {
            aip_insert_post($post);
        }
    }
}
