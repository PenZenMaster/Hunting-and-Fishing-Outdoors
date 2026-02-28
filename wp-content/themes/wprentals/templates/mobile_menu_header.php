<?php
/**
 * Mobile Header Template
 * 
 * Displays the mobile version of the header including:
 * - Mobile menu trigger
 * - Mobile logo
 * - Mobile user menu trigger
 *
 * @package WPRentals
 * @subpackage Templates
 * @since 4.0
 */

// Get all required options at once for efficiency
$mobile_header_options = array(
    'logo'          => wprentals_get_option('wp_estate_logo_image', 'url'),
    'mobile_logo'   => wprentals_get_option('wp_estate_mobile_logo_image', 'url'),
    'sticky_header' => wprentals_get_option('wp_estate_mobile_sticky_header'),
    'show_user'     => wprentals_get_option('wp_estate_show_top_bar_user_login', '')
);

// Ensure $wpestate_is_top_bar_class is defined
$wpestate_is_top_bar_class = isset($wpestate_is_top_bar_class) ? $wpestate_is_top_bar_class : '';
?>

<div class="mobile_header <?php echo esc_attr($wpestate_is_top_bar_class); ?> mobile_header_sticky_<?php echo esc_attr($mobile_header_options['sticky_header']); ?>">
    <!-- Mobile Menu Trigger -->
    <div class="mobile-trigger"><i class="fas fa-bars"></i></div>
    
    <!-- Mobile Logo -->
    <div class="mobile-logo">
        <a href="<?php echo esc_url(home_url('', 'login')); ?>">
            <?php
            if (!empty($mobile_header_options['mobile_logo'])) {
                echo '<img src="'.esc_url($mobile_header_options['mobile_logo']).'" class="img-responsive retina_ready" alt="'.esc_attr__('logo', 'wprentals').'"/>';
            } else {
                echo '<img class="img-responsive retina_ready" src="'.esc_url(get_template_directory_uri().'/img/logo.png').'" alt="'.esc_attr__('logo', 'wprentals').'"/>';
            }
            ?>
        </a>
    </div>
    
    <?php
    // Show WooCommerce cart icon on mobile if WooCommerce is active
    if ( class_exists( 'WooCommerce' ) ) {
        $cart_url   = wc_get_cart_url();
        $cart_count = WC()->cart->get_cart_contents_count();
        ?>
        <a class="mobile-cart-link" href="<?php echo esc_url( $cart_url ); ?>">
            <div id="shopping-cart-mobile" class="wpestate_header_shoping_cart_icon">
                <svg id="shopping-cart_icon" width="23" height="21" viewBox="0 0 23 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18.5444 21H4.56587C4.11918 21.0009 3.68506 20.8508 3.33278 20.5738C2.98049 20.2968 2.73033 19.9087 2.62221 19.4715L0.0518138 9.06855C-0.0161936 8.77314 -0.017268 8.46605 0.0486706 8.17016C0.114609 7.87427 0.245863 7.59719 0.43266 7.35954C0.619457 7.1219 0.856988 6.92982 1.12757 6.79759C1.39815 6.66537 1.69481 6.59641 1.99547 6.59584H21.1148C21.4188 6.59524 21.719 6.66461 21.9925 6.79866C22.266 6.93272 22.5055 7.12793 22.6929 7.36945C22.8804 7.61096 23.0107 7.89242 23.074 8.1924C23.1374 8.49238 23.132 8.80298 23.0584 9.10056L20.488 19.5035C20.3739 19.9348 20.1212 20.3156 19.7694 20.5864C19.4177 20.8572 18.9868 21.0027 18.5444 21V21ZM1.99547 8.1963C1.93208 8.1955 1.86936 8.20945 1.81217 8.23706C1.75499 8.26467 1.70488 8.30521 1.66575 8.35552C1.62661 8.40584 1.5995 8.46457 1.58651 8.52717C1.57353 8.58976 1.57502 8.65453 1.59088 8.71645L4.16127 19.1194C4.18282 19.2111 4.23458 19.2927 4.30808 19.3508C4.38158 19.409 4.47246 19.4403 4.56587 19.4395H18.5444C18.6353 19.4389 18.7236 19.408 18.7953 19.3515C18.8671 19.2951 18.9183 19.2163 18.941 19.1274L21.5114 8.72445C21.5273 8.66254 21.5288 8.59776 21.5158 8.53517C21.5028 8.47257 21.4757 8.41384 21.4365 8.36353C21.3974 8.31321 21.3473 8.27268 21.2901 8.24506C21.2329 8.21745 21.1702 8.2035 21.1068 8.20431L1.99547 8.1963Z" fill="black"/>
                    <path d="M7.34949 10.9391C7.2432 10.5104 6.81245 10.2497 6.3874 10.3569C5.96234 10.4642 5.70394 10.8986 5.81023 11.3274L7.12859 16.6452C7.23488 17.074 7.66563 17.3346 8.09068 17.2274C8.51574 17.1202 8.77415 16.6857 8.66785 16.2569L7.34949 10.9391Z" fill="black"/>
                    <path d="M15.7647 10.9418L14.4454 16.2594C14.3391 16.6881 14.5974 17.1226 15.0225 17.2299C15.4475 17.3372 15.8783 17.0766 15.9846 16.6479L17.3039 11.3303C17.4103 10.9016 17.152 10.4671 16.7269 10.3598C16.3019 10.2525 15.8711 10.5131 15.7647 10.9418Z" fill="black"/>
                    <path d="M5.29573 7.88422L3.93913 7.08399L7.90579 0.442086C8.00504 0.255689 8.17344 0.116528 8.37415 0.0550426C8.57485 -0.00644271 8.79153 0.0147524 8.97679 0.113992C9.15637 0.22357 9.2856 0.400468 9.33615 0.605949C9.38671 0.81143 9.35448 1.02875 9.24652 1.21031L5.29573 7.88422Z" fill="black"/>
                    <path d="M17.8145 7.88421L13.8478 1.2423C13.79 1.15019 13.7514 1.04711 13.7344 0.939408C13.7175 0.831702 13.7225 0.721639 13.7493 0.615975C13.776 0.51031 13.8239 0.411275 13.89 0.32495C13.956 0.238624 14.0389 0.166831 14.1334 0.113976C14.3134 0.00507863 14.5289 -0.0274323 14.7326 0.0235629C14.9363 0.0745582 15.1117 0.204903 15.2203 0.386054L19.187 7.02796L17.8145 7.88421Z" fill="black"/>
                </svg>
                <span class="wpestream_cart_counter_header"><?php echo esc_html( $cart_count ); ?></span>
            </div>
        </a>
        <?php
    }

    // Show user menu trigger if enabled
    if ( $mobile_header_options['show_user'] === "yes" ) {
        echo '<div class="mobile-trigger-user"><i class="fas fa-user-circle"></i></div>';
    }
    ?>
</div>
