<?php
/**
 * Login Form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce; ?>

<?php $woocommerce->show_messages(); ?>

<?php do_action('woocommerce_before_customer_login_form'); ?>

<?php if (get_option('woocommerce_enable_myaccount_registration')=='yes') : ?>

<?php
	if (get_option('woocommerce_enable_myaccount_registration')=='yes') {
		$content_class = 'seven columns';
	} else{
		$content_class = 'twelve columns';
	}
?>
<div class="row">
	<div class="woo-myaccount <?php echo $content_class; ?>" id="customer_login">

		<div class="myaccount-login">

	<?php endif; ?>
			<div class="woo-myaccount">
			<h2><?php _e( "I'm a returning customer", 'woocommerce' ); ?></h2>
			
			<form method="post" class="login">
				<p class="form-row">
					<label for="username"><?php _e( 'Username or email', 'woocommerce' ); ?> <span class="required">*</span></label>
					<input type="text" class="input-text" name="username" id="username" />
				</p>
				<p class="form-row">
					<label for="password"><?php _e( 'Password', 'woocommerce' ); ?> <span class="required">*</span></label>
					<input class="input-text" type="password" name="password" id="password" />
				</p>
				<div class="clear"></div>

				<p class="form-row">
					<label>&nbsp;</label>
					<?php $woocommerce->nonce_field('login', 'login') ?>
					<input type="submit" class="button blue" name="login" value="<?php _e( 'Sign me in', 'woocommerce' ); ?>" />
					<a class="lost_password" href="<?php

					$lost_password_page_id = woocommerce_get_page_id( 'lost_password' );

					if ( $lost_password_page_id )
						echo esc_url( get_permalink( $lost_password_page_id ) );
					else
						echo esc_url( wp_lostpassword_url( home_url() ) );

					?>"><?php _e( 'Lost Password?', 'woocommerce' ); ?></a>
				</p>
			</form>
			</div>
	<?php if (get_option('woocommerce_enable_myaccount_registration')=='yes') : ?>

		</div>

		<div class="myaccount-register">

			<h2><?php _e( "I'm a new customer", 'woocommerce' ); ?></h2>
			<form method="post" class="register">

				<?php if ( get_option( 'woocommerce_registration_email_for_username' ) == 'no' ) : ?>

					<p class="form-row">
						<label for="reg_username"><?php _e( 'Username', 'woocommerce' ); ?> <span class="required">*</span></label>
						<input type="text" class="input-text" name="username" id="reg_username" value="<?php if (isset($_POST['username'])) echo esc_attr($_POST['username']); ?>" />
					</p>

					<p class="form-row">

				<?php else : ?>

					<p class="form-row form-row-wide">

				<?php endif; ?>

					<label for="reg_email"><?php _e( 'Email', 'woocommerce' ); ?> <span class="required">*</span></label>
					<input type="email" class="input-text" name="email" id="reg_email" value="<?php if (isset($_POST['email'])) echo esc_attr($_POST['email']); ?>" />
				</p>

				<div class="clear"></div>

				<p class="form-row">
					<label for="reg_password"><?php _e( 'Password', 'woocommerce' ); ?> <span class="required">*</span></label>
					<input type="password" class="input-text" name="password" id="reg_password" value="<?php if (isset($_POST['password'])) echo esc_attr($_POST['password']); ?>" />
				</p>
				<p class="form-row">
					<label for="reg_password2"><?php _e( 'Re-enter password', 'woocommerce' ); ?> <span class="required">*</span></label>
					<input type="password" class="input-text" name="password2" id="reg_password2" value="<?php if (isset($_POST['password2'])) echo esc_attr($_POST['password2']); ?>" />
				</p>
				<div class="clear"></div>

				<!-- Spam Trap -->
				<div style="left:-999em; position:absolute;"><label for="trap">Anti-spam</label><input type="text" name="email_2" id="trap" tabindex="-1" /></div>

				<?php do_action( 'register_form' ); ?>

				<p class="form-row">
					<label>&nbsp;</label>
					<?php $woocommerce->nonce_field('register', 'register') ?>
					<input type="submit" class="button blue" name="register" value="<?php _e( 'Create new account', 'woocommerce' ); ?>" />
				</p>

			</form>

		</div>

	</div>
	<div class="five columns woo-myaccount">
		<div class="myaccount-separator">
			<div class="login-line"></div>
			<div class="login-or">OR</div>
		</div>
		<ul class="account-toggler">
			<li class="account-toggle-register">
				<div class="account-toggler-wrapper">
					<h3><?php _e( "I'm a new customer", 'woocommerce' ); ?></h3>
						<?php if(strlen(options::get_value( 'general' , 'new_customer' ))) { echo '<p>'. options::get_value( 'general' , 'new_customer' ) .'</p>'; } ?>
					<a href="#" class="customer-login button">Register me</a>
				</div>
			</li>
			<li class="account-toggle-login">
				<div class="account-toggler-wrapper">
					<h3><?php _e( "I'm a returning customer", 'woocommerce' ); ?></h3>
						<?php if(strlen(options::get_value( 'general' , 'returning_customer' ))) { echo '<p>'. options::get_value( 'general' , 'returning_customer' ) .'</p>'; } ?>
					<a href="#" class="customer-register button">Sign me in</a>
				</div>
			</li>
		</ul>
	</div>
<?php endif; ?>
</div>
<?php do_action('woocommerce_after_customer_login_form'); ?>