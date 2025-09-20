<?php
/**
 * AJAX фильтрация каталога WooCommerce
 * File: inc/ajax-catalog-filter.php
 */

if ( ! defined('ABSPATH') ) { exit; }

// Опционально: загружаем только если активен WooCommerce
if ( ! class_exists('WooCommerce') ) {
    return;
}

/**
 * Вспомогательная функция рендера карточки товара
 * (скопирована из прошлой версии)
 */
function buffet_render_product_card( WC_Product $product ) {
    $post_id = $product->get_id();
    ob_start(); ?>
    <div class="product-item" data-product-id="<?php echo esc_attr($post_id); ?>">
        <img src="<?php echo esc_url( get_the_post_thumbnail_url($post_id, 'medium') ?: wc_placeholder_img_src('medium') ); ?>"
             alt="<?php echo esc_attr(get_the_title($post_id)); ?>" class="product-item__image" loading="lazy">
        <div class="product-item__content">
            <div class="product-item__content__inner">
                <h3 class="product-item__content__title"><?php echo esc_html(get_the_title($post_id)); ?></h3>
                <?php if ($product->get_weight()) : ?>
                    <div class="product-item__content__weight"><?php echo esc_html( wc_format_weight($product->get_weight()) ); ?></div>
                <?php endif; ?>
            </div>
            <p class="product-item__content__description">
                <?php echo wp_kses_post( get_field('boxing_composition', $post_id) ); ?>
            </p>
            <div class="product-item__content__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
            <div class="product-item__content__buttons">
                <div class="product-item__content__buttons__inner">
                    <button class="product-item__content__buttons__add">В корзину</button>
                    <div class="product-item__content__buttons__wrapper" style="display:none">
                        <button class="product-item__content__buttons__count-value">-</button>
                        <div class="product-item__content__buttons__count-number">1</div>
                        <button class="product-item__content__buttons__count-value">+</button>
                    </div>
                </div>
                <a href="<?php echo esc_url( get_permalink($post_id) ); ?>" class="product-item__content__buttons__more">Подробнее</a>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Основной обработчик: buffet_filter_products
 * POST: term_id, per_page (опц)
 */
function buffet_filter_products() {
    check_ajax_referer('catalog_filter_nonce', 'nonce');

    $term_id  = isset($_POST['term_id']) ? absint($_POST['term_id']) : 0;
    $per_page = isset($_POST['per_page']) ? max(1, absint($_POST['per_page'])) : 12;

    $args = [
        'post_type'      => 'product',
        'posts_per_page' => $per_page,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];

    if ( $term_id ) {
        $args['tax_query'] = [[
            'taxonomy'         => 'product_cat',
            'field'            => 'term_id',
            'terms'            => [$term_id],
            'include_children' => true,
            'operator'         => 'IN',
        ]];
    }

    $q = new WP_Query($args);

    ob_start();
    if ( $q->have_posts() ) {
        while ( $q->have_posts() ) { $q->the_post();
            $product = wc_get_product( get_the_ID() );
            echo buffet_render_product_card( $product );
        }
    } else {
        echo '<div class="catalog__empty">Товары не найдены</div>';
    }
    wp_reset_postdata();

    $html = ob_get_clean();
    wp_send_json_success([
        'html'  => $html,
        'count' => (int) $q->found_posts,
    ]);
}
add_action('wp_ajax_buffet_filter_products', 'buffet_filter_products');
add_action('wp_ajax_nopriv_buffet_filter_products', 'buffet_filter_products');
