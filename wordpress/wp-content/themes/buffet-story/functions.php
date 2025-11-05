<?php
/**
 * buffet-story functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package buffet-story
 */

if ( ! defined( '_S_VERSION' ) ) {
	define( '_S_VERSION', '1.0.0' );
}

function buffet_story_setup() {

	load_theme_textdomain( 'buffet-story', get_template_directory() . '/languages' );

	add_theme_support( 'automatic-feed-links' );

	add_theme_support( 'title-tag' );

	add_theme_support( 'post-thumbnails' );

	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'buffet-story' ),
		)
	);

	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	add_theme_support(
		'custom-background',
		apply_filters(
			'buffet_story_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	add_theme_support( 'customize-selective-refresh-widgets' );

	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'buffet_story_setup' );

function buffet_story_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'buffet_story_content_width', 640 );
}
add_action( 'after_setup_theme', 'buffet_story_content_width', 0 );

function buffet_story_scripts() {
    $ver = defined('_S_VERSION') ? _S_VERSION : wp_get_theme()->get('Version');

    wp_enqueue_style( 'buffet-story-style', get_stylesheet_uri(), [], $ver );
    wp_enqueue_style( 'main-css', get_stylesheet_directory_uri() . '/src/index.css', [], filemtime( get_stylesheet_directory() . '/src/index.css' ) );
    wp_enqueue_style( 'swiper-css', get_stylesheet_directory_uri() . '/src/css/vendor/swiper-bundle.min.css', [], null );
    wp_enqueue_style('air-datepicker-css', get_stylesheet_directory_uri() . '/src/css/vendor/air-datepicker.css', [], '3.4.0');
    wp_enqueue_style('choices-css', get_stylesheet_directory_uri() . '/src/css/vendor/choices.min.css', [], null);

    wp_enqueue_script('air-datepicker-js', get_stylesheet_directory_uri() . '/src/js/vendor/air-datepicker.js', [], '3.4.0', true);
    wp_enqueue_script('choices-js', get_stylesheet_directory_uri() . '/src/js/vendor/choices.min.js', [], null, true);
    wp_enqueue_script( 'swiper-js', get_stylesheet_directory_uri() . '/src/js/vendor/swiper-bundle.min.js', [], null, true );
    wp_enqueue_script( 'main-js',   get_stylesheet_directory_uri() . '/src/index.js', [ 'swiper-js' ], null, true );
    wp_enqueue_script( 'cart-js',   get_stylesheet_directory_uri() . '/src/cart.js', [], null, true );


    // Состояние корзины -> MYCART
    $cart_map = [];
    if ( function_exists('WC') && WC()->cart ) {
        foreach ( WC()->cart->get_cart() as $cart_item ) {
            $pid = (int) $cart_item['product_id'];
            $cart_map[ $pid ] = ($cart_map[ $pid ] ?? 0) + (int) $cart_item['quantity'];
        }
    }

    wp_localize_script( 'cart-js', 'MYCART', [
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'my_cart_nonce' ),
        'cart'     => $cart_map,
        'cart_unique_count'  => my_cart_unique_count(),
    ]);

    // Каталог — фильтр
    wp_enqueue_script(
        'catalog-filter',
        get_stylesheet_directory_uri() . '/src/js/ajax-products.js',
        [],
        null,
        true
    );

    wp_localize_script('catalog-filter', 'CATFILTER', [
        'ajax_url'  => admin_url('admin-ajax.php'),
        'nonce'     => wp_create_nonce('catalog_filter_nonce'),
        'base_url'  => trailingslashit( get_permalink(110) ),
    ]);

    if ( ! is_admin() ) {
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
        wp_dequeue_style('global-styles');
    }
}
add_action( 'wp_enqueue_scripts', 'buffet_story_scripts' );

add_filter('woocommerce_enqueue_styles', '__return_empty_array');

add_action('wp_enqueue_scripts', function () {
    wp_dequeue_style('wc-blocks-style');
    wp_dequeue_style('wc-blocks-vendors-style');
    wp_dequeue_style('wc-blocks-style-css');
}, 100);

require_once get_template_directory() . '/ajax-products.php';

add_action("admin_menu", "remove_menus");
function remove_menus() {
    remove_menu_page("edit.php");                 # Записи
    remove_menu_page("edit-comments.php");        # Комментарии
}

// Добавить товар
add_action('wp_ajax_my_cart_add', 'my_cart_add');
add_action('wp_ajax_nopriv_my_cart_add', 'my_cart_add');
function my_cart_add() {
    check_ajax_referer('my_cart_nonce', 'nonce');
    $product_id = absint($_POST['product_id'] ?? 0);
    $qty        = max(1, intval($_POST['qty'] ?? 1));
    if (!$product_id) wp_send_json_error();

    WC()->cart->add_to_cart($product_id, $qty);

    wp_send_json_success([
        'qty'        => $qty,
        'cart_count' => WC()->cart->get_cart_contents_count(),
    ]);
}

// Изменить количество (+/-)
add_action('wp_ajax_my_cart_change', 'my_cart_change');
add_action('wp_ajax_nopriv_my_cart_change', 'my_cart_change');
function my_cart_change() {
    check_ajax_referer('my_cart_nonce', 'nonce');
    $product_id = absint($_POST['product_id'] ?? 0);
    $delta      = intval($_POST['delta'] ?? 0);
    if (!$product_id || !$delta) wp_send_json_error();

    foreach (WC()->cart->get_cart() as $key => $item) {
        if ((int)$item['product_id'] === $product_id) {
            $new = (int)$item['quantity'] + $delta;

            if ($new <= 0) {
                WC()->cart->remove_cart_item($key);
                wp_send_json_success([
                    'qty'        => 0,
                    'cart_count' => WC()->cart->get_cart_contents_count(),
                ]);
            } else {
                WC()->cart->set_quantity($key, $new, true);
                wp_send_json_success([
                    'qty'        => $new,
                    'cart_count' => WC()->cart->get_cart_contents_count(),
                ]);
            }
        }
    }

    wp_send_json_success([
        'qty'        => 0,
        'cart_count' => WC()->cart->get_cart_contents_count(),
        'cart_unique_count' => my_cart_unique_count(),
    ]);
}

// Удалить товар целиком
add_action('wp_ajax_my_cart_remove', 'my_cart_remove');
add_action('wp_ajax_nopriv_my_cart_remove', 'my_cart_remove');
function my_cart_remove() {
    check_ajax_referer('my_cart_nonce', 'nonce');
    $product_id = absint($_POST['product_id'] ?? 0);
    if (!$product_id) wp_send_json_error();

    foreach (WC()->cart->get_cart() as $key => $item) {
        if ((int)$item['product_id'] === $product_id) {
            WC()->cart->remove_cart_item($key);
        }
    }

    wp_send_json_success([
        'qty'        => 0,
        'cart_count' => WC()->cart->get_cart_contents_count(),
        'cart_unique_count' => my_cart_unique_count(),
    ]);
}

add_filter('wpcf7_autop_or_not', '__return_false');

// Корзина
add_action('wp_ajax_my_cart_popup', 'my_cart_popup');
add_action('wp_ajax_nopriv_my_cart_popup', 'my_cart_popup');
function my_cart_popup() {
    check_ajax_referer('my_cart_nonce', 'nonce');

    ob_start();

    if ( function_exists('WC') && WC()->cart ) :
        foreach ( WC()->cart->get_cart() as $cart_item ):
            $prod       = $cart_item['data'];
            if ( ! $prod ) continue;
            $product_id = (int) $cart_item['product_id'];
            $qty        = (int) $cart_item['quantity'];
            $title      = $prod->get_name();
            $img_html   = $prod->get_image('thumbnail', ['class' => 'popup__content__products__item__image']);
            $price_html = wc_price( WC()->cart->get_product_subtotal( $prod, $qty ) );

            $minOrder_for_this_product = trim( (string) $prod->get_attribute('pa_minimum-order', $prod->id) );
            if ($minOrder_for_this_product === '') {
                $minOrder_for_this_product = '1';
            }

            preg_match('/\d+/', $minOrder_for_this_product, $matches);
            $minOrder_for_this_product = isset($matches[0]) ? (int) $matches[0] : 1;
            ?>
            <div class="popup__content__products__item" data-product-id="<?php echo esc_attr($product_id); ?>">
                <?php echo $img_html; ?>
                <div class="popup__content__products__item__content">
                    <div class="popup__content__products__item__content__inner">
                        <div class="popup__content__products__item__content__title"><?php echo esc_html($title); ?></div>
                        <button class="popup__content__products__item__content__delete" type="button" aria-label="<?php esc_attr_e('Удалить','woocommerce'); ?>">
                            <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="0.5" y="0.5" width="37" height="37" rx="18.5" stroke="black"/>
                                <path d="M12 26L26 12M12 12L26 26" stroke="black" stroke-linecap="round"/>
                            </svg>
                        </button>
                    </div>
                    <?php
                    $unit_display_price = wc_get_price_to_display( $prod );

                    $currency_code = get_woocommerce_currency();

                    $decimals = wc_get_price_decimals();
                    ?>
                    <div class="popup__content__products__item__content__control">
                        <div class="product-item__content__buttons__inner" data-product-id="<?php echo esc_attr($product_id); ?>" data-min-value="<?php echo esc_attr($minOrder_for_this_product); ?>">
                            <div class="product-item__content__buttons__wrapper">
                                <button class="product-item__content__buttons__count-value" type="button">-</button>
                                <div class="product-item__content__buttons__count-number"><?php echo $qty ?: 1; ?></div>
                                <button class="product-item__content__buttons__count-value" type="button">+</button>
                            </div>
                        </div>
                        <div class="popup__content__products__item__content__control__price" data-unit-price="<?php echo esc_attr( $unit_display_price ); ?>"  data-currency="<?php echo esc_attr( $currency_code ); ?>" data-decimals="<?php echo esc_attr( $decimals ); ?>"><?php echo $price_html; ?></div>
                    </div>
                </div>
            </div>
            <hr class="popup__content__products__hr">
        <?php
        endforeach;
    endif;

    $html = trim(ob_get_clean());

    if ($html === '') {
        $html = '<div class="popup__empty">Ваша корзина пуста</div>';
    }

    wp_send_json_success([
        'html'       => $html,
        'cart_count' => WC()->cart->get_cart_contents_count(),
        'cart_unique_count' => my_cart_unique_count(),
    ]);
}
// Подсчет уникальных товаров
function my_cart_unique_count() {
    if ( ! function_exists('WC') || ! WC()->cart ) return 0;
    return count( WC()->cart->get_cart() );
}

add_action('template_redirect', function () {
    if (is_404() && !is_admin() && !wp_doing_ajax()) {
        $target = home_url('/');

        if (trailingslashit($target) !== trailingslashit(home_url(add_query_arg([], $GLOBALS['wp']->request ?? '')))) {
            wp_safe_redirect($target, 301);
            exit;
        }
    }
});

add_action('wp_ajax_my_cart_get_state', 'my_cart_get_state');
add_action('wp_ajax_nopriv_my_cart_get_state', 'my_cart_get_state');

function my_cart_get_state() {
    if ( ! function_exists('WC') || ! WC()->cart ) {
        wp_send_json_error(['message' => 'Cart unavailable']);
    }

    $cart = [];
    foreach ( WC()->cart->get_cart() as $item ) {
        $cart[ $item['product_id'] ] = $item['quantity'];
    }

    wp_send_json_success([
        'cart' => $cart,
        'cart_unique_count' => count($cart),
    ]);
}

// Добавление товаров в письмо
add_filter('wpcf7_special_mail_tags', function($output, $name, $html_context){
    if ($name !== 'order_items') return $output;

    if ( ! function_exists('WC') ) return '';
    if ( function_exists('wc_load_cart') ) wc_load_cart();
    if ( ! WC()->cart ) return '';

    $items = WC()->cart->get_cart();
    if ( empty($items) ) return '';

    $sum_total = 0;

    if ($html_context) {
        ob_start(); ?>
        <table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse;width:100%;">
            <thead>
            <tr>
                <th align="left">Товар</th>
                <th align="right">Цена за 1 шт.</th>
                <th align="right">Кол-во</th>
                <th align="right">Сумма</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($items as $cart_item):
                $product = $cart_item['data'];
                if ( ! $product ) continue;
                $qty        = (int) $cart_item['quantity'];
                $unit_price = wc_get_price_to_display($product);
                $row_total  = $unit_price * $qty;
                $sum_total += $row_total; ?>
                <tr>
                    <td><?php echo esc_html($product->get_name()); ?></td>
                    <td align="right"><?php echo wc_price($unit_price); ?></td>
                    <td align="right"><?php echo $qty; ?></td>
                    <td align="right"><?php echo wc_price($row_total); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
            <tr>
                <th colspan="3" align="right">ИТОГ:</th>
                <th align="right"><?php echo wc_price($sum_total); ?></th>
            </tr>
            </tfoot>
        </table>
        <?php
        return ob_get_clean();
    }

    $lines = [];
    foreach ($items as $cart_item) {
        $product = $cart_item['data'];
        if (! $product) continue;

        $qty        = (int) $cart_item['quantity'];
        $unit_price = wc_get_price_to_display($product);
        $row_total  = $unit_price * $qty;
        $sum_total += $row_total;

        $lines[] = sprintf(
            '%s | цена: %s | кол-во: %d | сумма: %s',
            wp_strip_all_tags($product->get_name()),
            html_entity_decode(wp_strip_all_tags(wc_price($unit_price))),
            $qty,
            html_entity_decode(wp_strip_all_tags(wc_price($row_total)))
        );
    }
    $lines[] = 'ИТОГ: ' . html_entity_decode(wp_strip_all_tags(wc_price($sum_total)));
    return implode("\n", $lines);
}, 10, 3);

add_action('wpcf7_mail_sent', function($contact_form){
    $clear_for_form_ids = [1011];

    $form_id = (int) $contact_form->id();
    if ( ! in_array($form_id, $clear_for_form_ids, true) ) {
        return;
    }

    if ( function_exists('wc_load_cart') ) wc_load_cart();
    if ( function_exists('WC') ) {
        if ( method_exists(wc(), 'initialize_session') && ( ! wc()->session ) ) {
            wc()->initialize_session();
        }
        if ( WC()->cart ) {
            WC()->cart->empty_cart(true);
        }
    }
}, 10, 1);

// Очистка корзины после успешной отправки формы
add_action('wp_ajax_my_cart_clear', 'my_cart_clear_cb');
add_action('wp_ajax_nopriv_my_cart_clear', 'my_cart_clear_cb');
function my_cart_clear_cb() {
    check_ajax_referer('my_cart_nonce', 'nonce');

    if ( function_exists('wc_load_cart') ) wc_load_cart();

    if ( function_exists('WC') && WC()->cart ) {
        WC()->cart->empty_cart(true);
    }

    wp_send_json_success([
        'cart_unique_count' => 0,
    ]);
}
