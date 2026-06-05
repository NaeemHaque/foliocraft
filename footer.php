<?php
/**
 * Theme footer.
 *
 * @package FolioCraft
 */

defined( 'ABSPATH' ) || exit;
?>
</main>
<?php \FolioCraft\Core\View::render( 'layout/footer', array( 'profile' => \FolioCraft\Models\Profile::all() ) ); ?>
<?php wp_footer(); ?>
</body>
</html>
