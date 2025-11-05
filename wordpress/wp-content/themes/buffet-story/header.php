<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package buffet-story
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="header" id="header">
    <a href="<?= home_url(); ?>" class="header__logo">
        <img src="/wp-content/uploads/2025/09/Логотип.svg" alt="logo">
    </a>
    <nav class="header__navigation">
        <?php
        global $post;

        $menu_items = wp_get_nav_menu_items(16);

        $current_url = trailingslashit( home_url( add_query_arg( [], $wp->request ) ) );

        foreach ($menu_items as $menu_item) {
            $item_url = trailingslashit( $menu_item->url );

            $active_class = '';

            if ( isset($post->ID) && (int)$menu_item->object_id === (int)$post->ID ) {
                $active_class = 'active';
            }

            elseif ( strpos($current_url, $item_url) === 0 ) {
                $active_class = 'active';
            }

            ?>
            <a href="<?= esc_url($menu_item->url); ?>"
               class="header__navigation__item <?= esc_attr($active_class); ?>">
                <?= esc_html($menu_item->title); ?>
            </a>
            <?php
        }
        ?>
    </nav>
    <div class="header__buttons">
        <div class="header__cart" data-count="">
            <div class="header__cart__count"></div>
            <svg width="26" height="24" viewBox="0 0 26 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M2.73463 17.773L1.2138 9.516C0.983907 8.2671 0.868961 7.6438 1.2378 7.2344C1.60538 6.825 2.28242 6.825 3.63525 6.825H22.3652C23.718 6.825 24.3951 6.825 24.7626 7.2344C25.1315 7.6438 25.0153 8.2671 24.7866 9.516L23.2658 17.773C22.7618 20.51 22.5104 21.8774 21.481 22.6893C20.4528 23.5 18.9698 23.5 16.004 23.5H9.99646C7.03059 23.5 5.54765 23.5 4.51945 22.6881C3.48999 21.8774 3.23862 20.5089 2.73463 17.7719M19.9475 6.825C19.9475 5.14751 19.2156 3.53872 17.9127 2.35255C16.6098 1.16638 14.8428 0.5 13.0002 0.5C11.1577 0.5 9.3906 1.16638 8.08773 2.35255C6.78486 3.53872 6.05291 5.14751 6.05291 6.825" stroke="white" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div class="header__menu">
            <svg class="header__menu__open" width="26" height="18" viewBox="0 0 26 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect y="2" width="26" height="2" fill="#D9D9D9"/>
                <rect y="8" width="26" height="2" fill="#D9D9D9"/>
                <rect y="14" width="26" height="2" fill="#D9D9D9"/>
            </svg>
            <svg class="header__menu__close" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M1 17L17 1M1 1L17 17" stroke="white" stroke-linecap="round"/>
            </svg>
        </div>
        <a href="#form" class="header__button-form">Связаться с нами</a>
    </div>
</header>
<div class="menu-navigation">
    <div class="menu-navigation__list">
        <?php
        $menu_items = wp_get_nav_menu_items(16);

        foreach ($menu_items as $menu_item) {
            ?>
            </a>
            <a href="<?= esc_url($menu_item->url); ?>" class="menu-navigation__item"><?= esc_html($menu_item->title); ?></a>
            <?php
        }
        ?>
    </div>
    <div class="menu-navigation__inner">
        <a href="#form" class="menu-navigation__button">Связаться с нами</a>
    </div>
</div>
<div class="popup">
    <div class="popup__background"></div>
    <button class="popup__exit">
        <svg width="76" height="76" viewBox="0 0 76 76" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="38" cy="38" r="38" fill="#F07D2C"/>
            <path d="M18 58L58 18M18 18L58 58" stroke="white" stroke-linecap="round"/>
        </svg>
    </button>
    <div class="popup__content">
        <div class="popup__content__title">Ваш заказ:</div>
        <div class="popup__content__hr__inner">
            <hr class="popup__content__hr">
        </div>
        <div class="popup__content__products">
            <?php if ( function_exists('WC') && WC()->cart ): ?>
                <?php foreach ( WC()->cart->get_cart() as $cart_item ):
                    $prod       = $cart_item['data'];
                    if ( ! $prod ) continue;
                    $product_id = (int) $cart_item['product_id'];
                    $qty        = (int) $cart_item['quantity'];
                    $title      = $prod->get_name();
                    $price_html = wc_price( WC()->cart->get_product_subtotal( $prod, $qty ) );

                    $minOrder_for_this_product = trim( (string) $prod->get_attribute('pa_minimum-order', $prod->id) );
                    if ($minOrder_for_this_product === '') {
                        $minOrder_for_this_product = '1';
                    }


                    preg_match('/\d+/', $minOrder_for_this_product, $matches);
                    $minOrder_for_this_product = isset($matches[0]) ? (int) $matches[0] : 1;
                    ?>
                    <div class="popup__content__products__item" data-product-id="<?php echo esc_attr($product_id); ?>">
                    <img src="<?= esc_url( get_the_post_thumbnail_url($prod->id)) ?>" alt="" class="popup__content__products__item__image sd">
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
                                <div class="popup__content__products__item__content__control__price" data-unit-price="<?php echo esc_attr( $unit_display_price ); ?>"  data-currency="<?php echo esc_attr( $currency_code ); ?>" data-decimals="<?php echo esc_attr( $decimals ); ?>"><?php echo wc_price( WC()->cart->get_product_subtotal( $prod, $qty ) ); ?>></div>
                            </div>
                        </div>
                    </div>
                    <hr class="popup__content__products__hr">
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="popup__content__all-price">
            <span class="popup__content__all-price__title">ИТОГ: </span>
            <span class="popup__content__all-price__coast"></span>
        </div>
        <div class="popup__content__form">
            <?= do_shortcode('[contact-form-7 id="9b1a87e" title="Форма для заказа"]') ?>
        </div>
    </div>
</div>
