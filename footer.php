<?php
/**
 * Theme footer.
 *
 * @package Naeem_Portfolio
 */

defined( 'ABSPATH' ) || exit;
?>
</main>
<?php \Naeem\Core\View::render( 'layout/footer', array( 'profile' => \Naeem\Models\Profile::all() ) ); ?>
<?php wp_footer(); ?>
</body>
</html>
