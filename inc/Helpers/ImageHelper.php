<?php
namespace SILE\Helpers;

if (!defined('ABSPATH')) {
    exit;
}

class ImageHelper {
    /**
     * Get image dimensions by URL
     */
    public static function get_dimensions_by_url($url) {
        if (empty($url)) return false;

        // Try to find attachment ID by URL
        $attachment_id = self::get_attachment_id_by_url($url);

        if ($attachment_id) {
            $meta = wp_get_attachment_metadata($attachment_id);
            if ($meta && isset($meta['width'], $meta['height'])) {
                return [
                    'width' => $meta['width'],
                    'height' => $meta['height']
                ];
            }
        }

        // If not found in DB, try to get from local file
        return self::get_dimensions_from_file($url);
    }

    /**
     * Get dimensions by parsing the filename or reading the file
     */
    public static function get_dimensions_from_file($url) {
        // 1. Try to parse dimensions from URL (e.g. image-1024x768.jpg)
        if (preg_match('/-(\d+)x(\d+)\.(jpg|jpeg|png|gif|webp|avif)$/i', $url, $matches)) {
            return [
                'width' => intval($matches[1]),
                'height' => intval($matches[2])
            ];
        }

        // 2. Try to get physical path and read header (cached)
        $path = str_replace(content_url(), WP_CONTENT_DIR, $url);
        if (file_exists($path)) {
            $data = wp_cache_get('sile_file_dim_' . md5($path), 'sile');
            if (false !== $data) return $data;

            $size = @getimagesize($path);
            if ($size) {
                $data = ['width' => $size[0], 'height' => $size[1]];
                wp_cache_set('sile_file_dim_' . md5($path), $data, 'sile', DAY_IN_SECONDS);
                return $data;
            }
        }

        return false;
    }

    /**
     * Get Attachment ID from URL
     */
    public static function get_attachment_id_by_url($url) {
        global $wpdb;

        // Remove sizes like -1024x768 from URL
        $url_clean = preg_replace('/-\d+x\d+(?=\.(jpg|jpeg|png|gif|webp|avif)$)/i', '', $url);

        $attachment_id = wp_cache_get('sile_url_id_' . md5($url_clean), 'sile');
        if (false !== $attachment_id) return $attachment_id;

        $attachment_id = $wpdb->get_var($wpdb->prepare(
            "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_wp_attached_file' AND meta_value = %s",
            basename($url_clean)
        ));

        if ($attachment_id) {
            wp_cache_set('sile_url_id_' . md5($url_clean), $attachment_id, 'sile', HOUR_IN_SECONDS);
        }

        return $attachment_id;
    }
}
