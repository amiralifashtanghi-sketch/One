<?php
/**
 * Layout Engine Helper Functions for Rendering Front-Page Sections & Custom Blocks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function kish_harmony_render_custom_blocks_for( $position ) {
	$general_options = get_option( 'kish_harmony_general_options', array() );
	$custom_blocks   = ! empty( $general_options['custom_blocks'] ) ? $general_options['custom_blocks'] : array();

	if ( empty( $custom_blocks ) || ! is_array( $custom_blocks ) ) {
		return;
	}

	foreach ( $custom_blocks as $block ) {
		if ( isset( $block['position'] ) && $block['position'] === $position ) {
			$type    = $block['type'] ?? 'html';
			$content = $block['content'] ?? '';

			if ( empty( $content ) ) {
				continue;
			}

			echo '<div class="custom-block-section ' . esc_attr( $position ) . '">';
			if ( $type === 'shortcode' ) {
				echo do_shortcode( $content );
			} elseif ( $type === 'php' ) {
				try {
					eval( '?>' . $content . '<?php ' );
				} catch ( Exception $e ) {
					echo esc_html( $e->getMessage() );
				}
			} else {
				echo wp_kses_post( $content );
			}
			echo '</div>';
		}
	}
}

function kish_harmony_is_section_enabled( $section_key ) {
	$general_options   = get_option( 'kish_harmony_general_options', array() );
	$disabled_sections = ! empty( $general_options['disabled_sections'] ) ? (array) $general_options['disabled_sections'] : array();
	return ! in_array( $section_key, $disabled_sections, true );
}
