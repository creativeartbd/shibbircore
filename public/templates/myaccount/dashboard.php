<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$allowed_html = array(
	'a' => array(
		'href' => array(),
	),
);


?>

<h3>
	<?php
	printf(
		/* translators: 1: user display name 2: logout url */
		wp_kses( __( 'Welcome %1$s (not %1$s? <a href="%2$s">Log out</a>)', 'woocommerce' ), $allowed_html ),
		'<strong>' . esc_html( $current_user->display_name ) . '</strong>', '' );
	?>
</h3>

<?php 
$user = wp_get_current_user();
if ( in_array( 'trainer', (array) $user->roles ) ) {

	$user_meta = get_user_meta( get_current_user_id() );
	$membership_level = $user_meta['membership_level'];
	$membership_level = unserialize( $membership_level[0] );
	$acf_show_trainer = get_field( 'show_trainer_in_trainer_online_coaching', get_current_user_id(  ) );


	// echo '<pre>';
	// print_r( $acf_show_trainer );
	// print_r( $user_meta );
	// echo '</pre>';
	?>

	<div class="table-wrapper">
		<table class="table">
			<tr>
				<th><b>Membership Level</b><br/><small>Membership level that you are currently taking</small></th>
				<td>
				<?php
				foreach( $membership_level as $key => $level ) {
					$product = wc_get_product( $level );
					echo $product->get_name();
					echo '<br/>';
				}
				?></td>
			</tr>
			<tr>
				<th><b>Membership Type</b></th>
				<td><?php echo $user_meta['membership_type'][0]; ?></td>
			</tr>
			<tr>
				<th><b>Personalised Link</b></th>
				<td><?php echo $user_meta['set_up_personalise_link'][0]; ?></td>
			</tr>
			<tr>
				<th><b>Hide for Online Coaching</b></th>
				<td><?php echo $user_meta['hide_for_online_coaching'][0] == 0 ? 'No' : 'Yes'; ?></td>
			</tr>
			<tr>
				<th><b>All Privilieges</b></th>
				<td><?php echo $user_meta['all_privileges'][0] == 0 ? 'No' : 'Yes'; ?></td>
			</tr>
			<tr>
				<th><b>Show Trainer in Trainer Online Coaching</b></th>
				<td>
					<?php
					$show_trainer_in_trainer_online_coaching = $user_meta['show_trainer_in_trainer_online_coaching'][0];
					if( 'trainer_3_4_other' == $show_trainer_in_trainer_online_coaching ) {
						echo 'Trainer of first and 3-4 other.';
					} elseif ( 'only_trainer' == $show_trainer_in_trainer_online_coaching ) {
						echo 'Only Trainer';
					} elseif ( 'trainer_all_other' == show_trainer_in_trainer_online_coaching ) {
						echo 'Trainer of First and all other';
					}
					?>
				</td>
			</tr>
			<tr>
				<th><b>Membership Commission</b></th>
				<td>
				<?php  
				$args = array(
					'category' => array( 'membership-level' ),
					'orderby'  => 'name',
				);
				
				$products = wc_get_products( $args );
				$all_products = $products;
				foreach( $products as $key => $product ) {
					$product_id 	= $product->get_id();
					$product_name 	= $product->get_name();
					if( isset( $user_meta["membership_commission_{$key}_membership_level"][0] ) ) {
						$get_product = wc_get_product( $user_meta["membership_commission_{$key}_membership_level"][0] );
						echo $get_product->get_name() . ' (' . $get_product->get_price_html() . ')';
						echo ' - ';
						echo $user_meta["membership_commission_{$key}_commission"][0] . '%';
						echo '<hr/>';
					}	
				}
				?>
				</td>
			</tr>
			<tr>
				<th><b>Customer Discount</b></th>
				<td>
					<?php
					foreach( $all_products as $key => $product ) {
						$product_id 	= $product->get_id();
						$product_name 	= $product->get_name();

						if( isset( $user_meta["customer_discount_{$key}_membership_level"][0] ) ) {
							$get_product = wc_get_product( $user_meta["customer_discount_{$key}_membership_level"][0] );
							$coupon = new WC_Coupon( $user_meta["customer_discount_{$key}_coupon"][0] );
							$coupon_code = $coupon->get_code() . ' (' . wc_format_decimal( $coupon->get_amount(), 2 ) . ')';
							echo $get_product->get_name() . ' (' . $get_product->get_price_html() . ')';
							echo ' - ';
							echo $coupon_code;
							echo '<hr/>';
						}	
					}
					?>
				</td>
			</tr>
			<tr>
				<th>Trainer Specialization</th>
				<td>
					<?php
					echo $user_meta['trainer_specialization'][0]; 
					?>
				</td>
			</tr>
		</table>
	</div>
<?php } ?>
<?php
	/**
	 * My Account dashboard.
	 *
	 * @since 2.6.0
	 */
	do_action( 'woocommerce_account_dashboard' );

	/**
	 * Deprecated woocommerce_before_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_before_my_account' );

	/**
	 * Deprecated woocommerce_after_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_after_my_account' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */