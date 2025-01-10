<?php
/**
 * Plugin Name: PPV Obfuscate Uploads
 * Plugin URI:  https://sparkwebstudio.com/
 * Description: Renames uploaded video files to a random hash and routes them into /ppv/ for security.
 * Version:     1.0
 * Author:      SPARKWEB Studio
 * Author URI:  https://sparkwebstudio.com/
 * License:     GPL2 or later
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * 1) On every file upload, if it's a recognized video MIME,
 *    rename it to a random hash: random12chars.mp4
 */
add_filter( 'wp_handle_upload_prefilter', 'ppv_obfuscate_video_filename' );
function ppv_obfuscate_video_filename( $file ) {
    $video_mimes = [
        'video/mp4',
        'video/x-m4v',
        'video/quicktime',
        'video/mpeg',
        'video/webm',
        'video/ogg',
        'video/x-ms-wmv',
        'video/3gpp',
    ];

    if ( isset( $file['type'] ) && in_array( $file['type'], $video_mimes, true ) ) {
        $ext = pathinfo( $file['name'], PATHINFO_EXTENSION );
        // Generate a random 12-char string (letters+numbers)
        $random_name = wp_generate_password( 12, false, false );
        $file['name'] = $random_name . '.' . $ext;
    }

    return $file;
}

/**
 * 2) Optionally, route the actual file to /ppv/ instead of /uploads/.
 *    We attach a one-time 'upload_dir' filter for video uploads only.
 */
add_filter( 'wp_handle_upload_prefilter', 'ppv_route_videos_to_ppv' );
function ppv_route_videos_to_ppv( $file ) {
    $video_mimes = [
        'video/mp4',
        'video/x-m4v',
        'video/quicktime',
        'video/mpeg',
        'video/webm',
        'video/ogg',
        'video/x-ms-wmv',
        'video/3gpp',
    ];

    if ( isset( $file['type'] ) && in_array( $file['type'], $video_mimes, true ) ) {
        // Attach a one-time filter to override upload_dir
        add_filter( 'upload_dir', 'ppv_custom_upload_dir' );
    }

    return $file;
}

function ppv_custom_upload_dir( $uploads ) {
    // Physical path to your /ppv/ folder:
    $custom_dir = '/home/runcloud/webapps/contactcustody/ppv';
    // Public URL to that folder:
    $custom_url = 'https://contactcustody.kcdev.site/ppv';

    // Remove date-based subfolders, everything goes into /ppv/
    $uploads['subdir']  = '';
    $uploads['path']    = $custom_dir;
    $uploads['url']     = $custom_url;
    $uploads['basedir'] = $custom_dir;
    $uploads['baseurl'] = $custom_url;

    // Very important: remove the filter right after usage
    remove_filter( 'upload_dir', 'ppv_custom_upload_dir' );

    return $uploads;
}