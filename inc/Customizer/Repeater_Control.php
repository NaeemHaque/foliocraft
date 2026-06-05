<?php
/**
 * Generic Customizer repeater control — renders an add/remove/reorder list of
 * rows whose fields are defined per-instance. The value is a JSON string stored
 * in a single theme_mod. Used for Projects (and Experience).
 *
 * @package FolioCraft
 */

namespace FolioCraft\Customizer;

defined( 'ABSPATH' ) || exit;

if ( class_exists( '\WP_Customize_Control' ) ) :

	class Repeater_Control extends \WP_Customize_Control {

		/** @var string */
		public $type = 'foliocraft-repeater';

		/** @var array<int,array<string,string>> Field definitions: key, label, type (text|textarea|url|media). */
		public $fields = array();

		/** @var string */
		public $button_label = '';

		public function render_content() {
			?>
			<?php if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif; ?>
			<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
			<?php endif; ?>
			<div class="fc-rep" data-fields="<?php echo esc_attr( wp_json_encode( $this->fields ) ); ?>">
				<div class="fc-rep-rows"></div>
				<button type="button" class="button fc-rep-add"><?php echo esc_html( $this->button_label ? $this->button_label : __( 'Add item', 'foliocraft' ) ); ?></button>
				<input type="hidden" class="fc-rep-value" <?php $this->link(); ?> value="<?php echo esc_attr( $this->value() ); ?>" />
			</div>
			<?php
		}
	}

endif;
