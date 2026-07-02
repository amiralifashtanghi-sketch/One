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

        // If not found in DB, we could try to get it from file (cached)
        // But for performance, we'll stick to DB for now or return false
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
