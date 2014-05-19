<?php
/**
 * Edit address form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $current_user;

get_currentuserinfo();
?>

<?php $woocommerce->show_messages(); ?>

<?php if (!$load_address) : ?>

	<?php woocommerce_get_template('myaccount/my-address.php'); ?>

<?php else : ?>
<div class="woo-address">
	<form action="<?php echo esc_url( add_query_arg( 'address', $load_address, get_permalink( woocommerce_get_page_id('edit_address') ) ) ); ?>" method="post">

		<h3><?php if ($load_address=='billing') _e( 'Billing Address', 'woocommerce' ); else _e( 'Shipping Address', 'woocommerce' ); ?></h3>

		<?php
		foreach ($address as $key => $field) :

			/*we want to get rid of class 'form-row-first' and 'form-row-last'*/
			if(isset($field['class']) && is_array($field['class'])){
				$index = 0;
				foreach ($field['class'] as  $val) { //deb::e($val);
					if( 'form-row-first' == $val || 'form-row-last' == $val ){
						unset($field['class'][$index]); 
					}

					$index ++;
				}
			}
			

			$value = (isset($_POST[$key])) ? $_POST[$key] : get_user_meta( get_current_user_id(), $key, true );

			// Default values
			if (!$value && ($key=='billing_email' || $key=='shipping_email')) $value = $current_user->user_email;
			if (!$value && ($key=='billing_country' || $key=='shipping_country')) $value = $woocommerce->countries->get_base_country();
			if (!$value && ($key=='billing_state' || $key=='shipping_state')) $value = $woocommerce->countries->get_base_state();

			woocommerce_form_field( $key, $field, $value );
		endforeach;
		?>

		<p class="form-row">
			<label>&nbsp;</label>
			<input type="submit" class="button blue" name="save_address" value="<?php _e( 'Save Address', 'woocommerce' ); ?>" />
			<?php $woocommerce->nonce_field('edit_address') ?>
			<input type="hidden" name="action" value="edit_address" />
		</p>

	</form>
</div>
<?php endif; ?>