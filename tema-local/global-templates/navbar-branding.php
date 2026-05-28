<?php
/**
 * Navbar branding.
 *
 * @package    Understrap
 * @subpackage Understrap_Child
 * @author     Valerii Vasyliev
 * @since      1.2.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<?php if (function_exists('smn_brand_logos_dropdow')) : ?>
<div class="navbar-brand d-none d-md-block">
	<?php smn_brand_logos_dropdown(); ?>
</div>
<?php endif; ?>

<?php if (function_exists('the_custom_logo')) : ?>
<div class="d-md-none">
	<?php the_custom_logo(); ?>
</div>
<?php endif; ?>