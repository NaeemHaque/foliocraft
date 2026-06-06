<?php
/**
 * Block editor integration: block styles + block patterns.
 *
 * @package FolioCraft
 */

namespace FolioCraft;

defined( 'ABSPATH' ) || exit;

class Blocks {

	public static function init() {
		add_action( 'init', array( __CLASS__, 'register' ) );
	}

	/** Register the theme's block styles and patterns. */
	public static function register() {
		// ---- Block styles (variations on core blocks) ----
		register_block_style(
			'core/button',
			array(
				'name'  => 'foliocraft-outline',
				'label' => __( 'Outline', 'foliocraft' ),
			)
		);
		register_block_style(
			'core/quote',
			array(
				'name'  => 'foliocraft-accent',
				'label' => __( 'Accent', 'foliocraft' ),
			)
		);
		register_block_style(
			'core/image',
			array(
				'name'  => 'foliocraft-frame',
				'label' => __( 'Framed', 'foliocraft' ),
			)
		);

		// ---- Block patterns ----
		register_block_pattern_category(
			'foliocraft',
			array( 'label' => __( 'FolioCraft', 'foliocraft' ) )
		);

		register_block_pattern(
			'foliocraft/section-intro',
			array(
				'title'       => __( 'Section intro', 'foliocraft' ),
				'description' => __( 'A heading with a short lead paragraph to open a section.', 'foliocraft' ),
				'categories'  => array( 'foliocraft', 'text' ),
				'content'     => "<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">" . esc_html__( 'A short, punchy heading', 'foliocraft' ) . "</h2>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph -->\n<p>" . esc_html__( 'Open the section with a sentence or two of plain, confident copy that sets up what follows.', 'foliocraft' ) . "</p>\n<!-- /wp:paragraph -->",
			)
		);

		register_block_pattern(
			'foliocraft/accent-quote',
			array(
				'title'       => __( 'Accent quote', 'foliocraft' ),
				'description' => __( 'A quote styled with the theme accent border.', 'foliocraft' ),
				'categories'  => array( 'foliocraft', 'text' ),
				'content'     => "<!-- wp:quote {\"className\":\"is-style-foliocraft-accent\"} -->\n<blockquote class=\"wp-block-quote is-style-foliocraft-accent\"><!-- wp:paragraph -->\n<p>" . esc_html__( 'Drop in a line worth remembering — a testimonial, a guiding principle, or the one takeaway you want to land.', 'foliocraft' ) . "</p>\n<!-- /wp:paragraph --><cite>" . esc_html__( 'Attribution', 'foliocraft' ) . "</cite></blockquote>\n<!-- /wp:quote -->",
			)
		);
	}
}
