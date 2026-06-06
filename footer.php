<?php
/**
 * Theme footer.
 *
 * @package FolioCraft
 */

defined( 'ABSPATH' ) || exit;
?>
</main>
<?php if ( is_active_sidebar( 'foliocraft-footer' ) ) : ?>
<aside class="footer-widgets wrap" aria-label="<?php esc_attr_e( 'Footer widgets', 'foliocraft' ); ?>">
	<?php dynamic_sidebar( 'foliocraft-footer' ); ?>
</aside>
<?php endif; ?>
<?php \FolioCraft\Core\View::render( 'layout/footer', array( 'profile' => \FolioCraft\Models\Profile::all() ) ); ?>
<?php wp_footer(); ?>
</body>
</html>
