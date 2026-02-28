<?php
/**
 * Membership User Verifications Module
 * 
 * Handles user ID verification functionality including:
 * - Retrieving verified users
 * - Displaying verification UI elements
 * - Processing verification statuses
 * - Managing verification badges
 *
 * @package WPRentals
 * @subpackage Membership
 */


/**
 * Get users with verification meta
 * 
 * @function wpestate_get_verification_users
 * @return array Array of user objects that have verification metadata
 */


if ( ! function_exists('wpestate_get_verification_users')) {
	/**
	 * Get all users with verification meta
	 *
	 * @return array
	 */
	function wpestate_get_verification_users() {
		$verification_users = get_users(array(
			'meta_key' => 'user_id_verified',
			'fields' => 'all_with_meta'
		));
		return $verification_users;
	}
}


/**
 * Generate HTML for single user verification display
 * 
 * @function wpestate_render_single_userid
 * @param object $user_o WP_User object
 * @return string HTML markup for user verification display
 */

if ( ! function_exists( 'wpestate_render_single_userid' ) ) {
	/**
	 * Constructs and returns verification
	 * widget part for a single user
	 *
	 * @param $user_o
	 *
	 * @return string
	 */
	function wpestate_render_single_userid( $user_o ) {
		$verification_users = '';
		if ( ! empty( $user_o ) ) {
			$useridimageid      = get_user_meta( $user_o->ID, 'user_id_image', TRUE );
			$user_id_verified   = get_user_meta( $user_o->ID, 'user_id_verified', TRUE );
			$verify_label       = ( $user_id_verified == 0 ) ? esc_html__( 'Validate user ID', 'wprentals-core' ) : esc_html__( 'Remove user ID validation', 'wprentals-core' );
			$verification_class = ( $user_id_verified == 1 ) ? ' verified' : '';
			$verification_users .= sprintf( '<div class="verify-user%s">', esc_attr( $verification_class ) ) . PHP_EOL;
			$verification_users .= sprintf( '<div class="user-ID"><img src="%1$s" alt="%2$s"></div>', esc_url( $useridimageid ), esc_html( $user_o->display_name ) );
                   
			if ( 'user-edit' !== get_current_screen()->id ) {
				$verification_users .= sprintf( '<div class="eit-profile"><span class="user-display-name">%3$s</span> <a href="%1$s">%2$s</a></div>', esc_url( get_edit_user_link( $user_o->ID ) ), esc_html__( 'Edit/view user profile', 'wprentals-core' ), esc_html( $user_o->display_name ) );
			}
			$verification_users .= sprintf( '<div class="verification-checkbox"><label>%3$s <input type="checkbox" %1$s value="1" name="verified-users[]" class="user_verification_check" data-userid="%2$d"></label></div>', checked( 1, $user_id_verified, FALSE ), esc_attr( $user_o->ID ), $verify_label );
			$verification_users .=''. esc_html__('Please move or delete the ID Scan image - some SEO plugins may help search engines index these images and they can become public. ','wprentals-core');
                        $verification_users .= '<br>'.esc_html__(' Image path:','wprentals-core').' '.$useridimageid;
                        $verification_users .= '</div> <!-- end .verify-user -->' . PHP_EOL;
		}

		return $verification_users;
	}
}

if ( ! function_exists( 'wpestate_display_userID' ) ) {
	/**
	 * Displays verification widget for a user
	 *
	 * @param $profileuser
	 */
	function wpestate_display_userID( $profileuser ) {
		$verifications      = '';
		$verification_users = wpestate_render_single_userid( $profileuser );
		// add div to trigger javascript ajax calls
		if ( $verification_users ) {
			$verifications .= sprintf( '<h2>%s</h2>', esc_html__( 'User ID', 'wprentals-core' ) ) . PHP_EOL;
			$verifications .= '<div class="user-verifications">' . PHP_EOL;
			$verifications .= $verification_users;
			$verifications .= '</div> <!-- end .user-verifications -->' . PHP_EOL;
                        $ajax_nonce = wp_create_nonce( "wprentals_user_verfication_nonce" );
                        $verifications .= '<input type="hidden" id="wprentals_user_verfication" value="'.esc_html($ajax_nonce).'" />    ';

		}

		print $verifications;
	}
}
add_action( 'edit_user_profile', 'wpestate_display_userID' );





/**
 * Display verification badge HTML
 * 
 * @function wpestate_display_verification_badge
 * @param int $userID User ID
 * @param int $type Badge type
 * @return string Verification badge HTML
 */

if ( ! function_exists( 'wpestate_display_verification_badge' ) ) {
	/**
	 * Display simeple "verified" HTML structure
	 *
	 * @param $userID
	 *
	 * @return string
	 */
	function wpestate_display_verification_badge( $userID,$type='' ) {
		$verified = '';
		$user_verified = wpestate_userid_verified( $userID );
		if ( $user_verified ) {
                    if($type==2){
                        $verified='<div class="verified_userid">
                        <svg width="61" height="76" viewBox="0 0 61 76" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M32.2695 75.496C37.8048 73.1439 42.9157 69.7927 47.4164 65.8137C49.8392 63.6738 51.9879 61.2952 53.9509 58.731C55.7017 56.4408 57.2137 53.965 58.3455 51.3123C59.0087 49.7649 59.61 48.2175 60.0256 46.5905C60.45 44.9635 60.7064 43.2923 60.8479 41.6211C60.9894 39.9853 60.9363 38.3317 60.9363 36.6871C60.9363 34.9451 60.9363 33.1944 60.9363 31.4524C60.9363 26.8456 60.9363 22.2387 60.9363 17.6319C60.9363 16.058 60.9805 14.484 60.9363 12.9189C60.9363 12.9012 60.9363 12.8836 60.9363 12.8659C60.9363 11.2301 59.8222 9.9656 58.3367 9.45274C55.2772 8.39167 52.209 7.33059 49.1495 6.26951C44.3304 4.58947 39.5025 2.91828 34.6746 1.24708C33.6047 0.875701 32.5436 0.468955 31.456 0.132947C30.1032 -0.291485 28.7326 0.398216 27.4858 0.831489C22.8613 2.43195 18.2456 4.03241 13.6211 5.63287C10.0046 6.88847 6.39692 8.13524 2.78042 9.39085C2.71852 9.40853 2.66547 9.43506 2.60357 9.45274C1.11806 9.9656 0.00392992 11.2389 0.00392992 12.8659C0.00392992 14.1126 0.00392992 15.3594 0.00392992 16.6062C0.00392992 20.824 0.00392992 25.0417 0.00392992 29.2595C0.00392992 32.708 -0.0049124 36.1565 0.00392992 39.605C0.0127722 42.6822 0.543312 45.8035 1.56018 48.7126C3.64696 54.69 7.39611 59.96 11.9234 64.337C16.2738 68.5459 21.3758 72.1094 26.858 74.6913C27.4593 74.9743 28.0783 75.2484 28.6884 75.5137C29.0951 75.7701 29.5284 75.9116 30.0059 75.9293C30.4745 76.0354 30.9432 76.0177 31.4207 75.8674C32.243 75.6375 33.1272 75.0097 33.534 74.2404C33.9673 73.418 34.1883 72.4277 33.8877 71.5169C33.6224 70.7035 33.083 69.7485 32.2607 69.4036C28.0606 67.6175 24.1081 65.2654 20.4916 62.4801C20.7303 62.6658 20.9691 62.8515 21.2078 63.0372C18.0246 60.5702 15.1066 57.7583 12.6308 54.5662C12.8164 54.805 13.0021 55.0437 13.1878 55.2825C11.3751 52.9304 9.8189 50.3838 8.66055 47.6427C8.7755 47.9257 8.8993 48.2086 9.01425 48.4916C8.12117 46.3606 7.48453 44.1235 7.1662 41.8333C7.21041 42.1428 7.25463 42.4611 7.29 42.7706C7.07778 41.1436 7.07778 39.5255 7.07778 37.8808C7.07778 36.0151 7.07778 34.1405 7.07778 32.2748C7.07778 29.3568 7.07778 26.4388 7.07778 23.5297C7.07778 20.3995 7.07778 17.2694 7.07778 14.1392C7.07778 13.7236 7.07778 13.308 7.07778 12.8924C6.21123 14.0331 5.34469 15.1649 4.47814 16.3055C7.5199 15.2533 10.5528 14.2011 13.5946 13.1488C18.4313 11.4688 23.2681 9.7976 28.1048 8.11756C29.2101 7.73734 30.3154 7.35712 31.4118 6.96806C30.784 6.96806 30.1562 6.96806 29.5284 6.96806C32.5613 8.02029 35.6031 9.07253 38.636 10.1248C43.4727 11.8048 48.3006 13.476 53.1374 15.156C54.2427 15.5363 55.3391 15.9165 56.4444 16.3055C55.5779 15.1649 54.7113 14.0331 53.8448 12.8924C53.8448 14.2718 53.8448 15.6424 53.8448 17.0218C53.8448 21.6994 53.8448 26.3858 53.8448 31.0634C53.8448 33.0617 53.8448 35.0601 53.8448 37.0496C53.8448 38.9684 53.8801 40.8695 53.6326 42.7794C53.6768 42.4699 53.721 42.1516 53.7564 41.8421C53.438 44.1323 52.8014 46.3694 51.9083 48.5004C52.0233 48.2175 52.1471 47.9345 52.262 47.6515C51.1037 50.3927 49.5474 52.9392 47.7347 55.2913C47.9204 55.0526 48.1061 54.8138 48.2918 54.5751C45.8248 57.7583 42.9068 60.579 39.7236 63.046C39.9623 62.8603 40.2011 62.6746 40.4398 62.4889C36.8322 65.2743 32.8797 67.6352 28.6796 69.4125C27.928 69.7308 27.2648 70.7742 27.0526 71.5258C26.8227 72.3747 26.9288 73.4976 27.4063 74.2492C27.8926 75.0008 28.6177 75.6728 29.5196 75.8762C29.8291 75.9204 30.1474 75.9646 30.4569 76C31.12 75.9735 31.7213 75.8143 32.2695 75.496Z" fill="black"/>
<path d="M14.2489 37.1204C17.0961 39.9676 19.9434 42.8148 22.7994 45.6709C23.2062 46.0777 23.6129 46.4844 24.0197 46.8912C25.3725 48.244 27.6715 48.244 29.0244 46.8912C31.0316 44.8839 33.0477 42.8679 35.0549 40.8607C38.2381 37.6775 41.4125 34.5031 44.5957 31.3198C45.3296 30.5859 46.0635 29.852 46.7975 29.1181C48.1061 27.8094 48.2034 25.4043 46.7975 24.1134C45.3827 22.8135 43.1898 22.7163 41.7927 24.1134C39.7855 26.1206 37.7695 28.1366 35.7622 30.1438C32.579 33.327 29.4046 36.5014 26.2214 39.6847C25.4875 40.4186 24.7536 41.1525 24.0197 41.8864C25.6909 41.8864 27.3532 41.8864 29.0244 41.8864C26.1772 39.0392 23.33 36.192 20.4739 33.3359C20.0671 32.9291 19.6604 32.5224 19.2537 32.1156C17.945 30.807 15.5399 30.7097 14.2489 32.1156C12.9491 33.5304 12.843 35.7233 14.2489 37.1204Z" fill="black"/>
</svg>
                    </div>';
                    }else{
			$verified = sprintf('<span class="verified_userid"><i class="fas fa-check-circle" aria-hidden="true"></i> %s</span>', esc_html__('Verified','wprentals-core'));

                    }
                }
		return $verified;
	}
}



/**
 * Check if user ID is verified
 *
 * @function wpestate_userid_verified
 * @param int $userID User ID to check
 * @return bool If user is verified
 */
if ( ! function_exists( 'wpestate_userid_verified' ) ) {
	/**
	 * Checks if the users ID has been verified
	 *
	 * @param $userID
	 *
	 * @return bool
	 */
	function wpestate_userid_verified( $userID ) {
		$verified = FALSE;
		$verified_meta = get_user_meta( $userID, 'user_id_verified', TRUE);
		if ($verified_meta == '1') {
			$verified = TRUE;
		}
		return $verified;
	}
}
