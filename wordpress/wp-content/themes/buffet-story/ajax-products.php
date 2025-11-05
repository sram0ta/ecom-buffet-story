<?php

if ( ! defined('ABSPATH') ) { exit; }

if ( ! class_exists('WooCommerce') ) {
    return;
}

function buffet_render_product_card( WC_Product $product ) {
    $post_id = $product->get_id();
    ob_start();
    $weight   = $product->get_attribute('pa_weight');
    ?>
    <div class="product-item">
        <?php if (get_field('product_tag')): ?>
            <div class="product-item__tag">
                <svg class="product-item__tag__icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="20" height="20" rx="10" fill="white"/>
                    <path d="M11.334 7.91406L11.3896 8.08691H15.8887L12.3955 10.624L12.249 10.7305L12.3047 10.9033L13.6387 15.0078L10.1465 12.4717L10 12.3643L9.85352 12.4717L6.36035 15.0078L7.69531 10.9033L7.75098 10.7305L7.60449 10.624L4.11133 8.08691H8.61035L8.66602 7.91406L10 3.80762L11.334 7.91406Z" stroke="#F07D2C" stroke-width="0.5"/>
                </svg>
                <div class="product-item__tag__title"><?= get_field('product_tag')->name; ?></div>
            </div>
        <?php endif; ?>
        <img src="<?= esc_url( get_the_post_thumbnail_url($post_id)); ?>" alt="<?= esc_attr(get_the_title($post_id)); ?>" class="product-item__image" loading="lazy">
        <div class="product-item__content">
            <div class="product-item__content__wrapper">
                <div class="product-item__content__inner">
                    <h3 class="product-item__content__title"><?= esc_html(get_the_title($post_id)); ?></h3>
                    <?php if ($product->get_weight()) : ?>
                        <div class="product-item__content__weight"><?= esc_html($weight); ?></div>
                    <?php endif; ?>
                </div>
                <p class="product-item__content__description">
                    <?= wp_kses_post( get_field('boxing_composition', $post_id) ); ?>
                </p>
                <div class="product-item__content__price"><?= wp_kses_post( $product->get_price_html() ); ?></div>
            </div>
            <div class="product-item__content__buttons">
                <?php
                $cart_qty = 0;
                if ( function_exists('WC') && WC()->cart ) {
                    foreach ( WC()->cart->get_cart() as $cart_item ) {
                        if ( (int) $cart_item['product_id'] === $product->get_id() ) {
                            $cart_qty = (int) $cart_item['quantity'];
                            break;
                        }
                    }
                }

                $minOrderRaw = $product->get_attribute('pa_minimum-order');
                preg_match('/\d+/', $minOrderRaw, $matches);
                $minOrder = isset($matches[0]) ? (int) $matches[0] : 1;
                ?>
                <div class="product-item__content__buttons__inner" data-product-id="<?php echo esc_attr($product->get_id()); ?>" data-min-value="<?= esc_attr($minOrder); ?>">
                    <button class="product-item__content__buttons__add <?php echo $cart_qty > 0 ? 'active' : ''; ?>">В корзину</button>
                    <div class="product-item__content__buttons__wrapper" style="<?php echo $cart_qty > 0 ? '' : 'display:none;'; ?>">
                        <button class="product-item__content__buttons__count-value">-</button>
                        <div class="product-item__content__buttons__count-number"><?php echo $cart_qty > 0 ? $cart_qty : 1; ?></div>
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

function buffet_filter_products() {
    check_ajax_referer('catalog_filter_nonce', 'nonce');

    $cat_term_id          = isset($_POST['cat_term_id']) ? absint($_POST['cat_term_id']) : 0;               // product_cat
    $product_type_term_id = isset($_POST['product_type_term_id']) ? absint($_POST['product_type_term_id']) : 0; // product-type

    // legacy поддержка (если где-то ещё шлют tax/term_id)
    $term_id  = isset($_POST['term_id']) ? absint($_POST['term_id']) : 0;
    $tax_raw  = isset($_POST['tax']) ? sanitize_key($_POST['tax']) : '';
    $tax      = $tax_raw ?: 'product_cat';

    $per_page = isset($_POST['per_page']) ? max(1, absint($_POST['per_page'])) : -1;

    $args = [
        'post_type'      => 'product',
        'posts_per_page' => $per_page,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];

    $seo = [
        'title'       => get_field('catalog_title', 110) ?: get_the_title(110),
        'description' => get_post_meta(110, '_yoast_wpseo_metadesc', true) ?: '',
        'url'         => trailingslashit( get_permalink(110) ),
        'is_child'    => false,
        'parent_url'  => '',
    ];

    $tax_query = [];

    // 1) product_cat фильтр
    if ( $cat_term_id ) {
        $tax_query[] = [
            'taxonomy'         => 'product_cat',
            'field'            => 'term_id',
            'terms'            => [$cat_term_id],
            'include_children' => true,
            'operator'         => 'IN',
        ];
        $term = get_term($cat_term_id, 'product_cat');
        if ($term && !is_wp_error($term)) {
            $seo['title']       = $term->name;
            $seo['description'] = term_description($term) ? wp_strip_all_tags(term_description($term)) : $seo['description'];
            $seo['url']         = trailingslashit( get_term_link($term) );
            if ($term->parent) {
                $parent = get_term($term->parent, 'product_cat');
                if ($parent && !is_wp_error($parent)) {
                    $seo['is_child']   = true;
                    $seo['parent_url'] = trailingslashit( get_term_link($parent) );
                }
            }
        }
    }

    // 2) product-type активный фильтр (может быть отключён)
    if ( $product_type_term_id ) {
        $tax_query[] = [
            'taxonomy'         => 'product-type',
            'field'            => 'term_id',
            'terms'            => [$product_type_term_id],
            'include_children' => true,
            'operator'         => 'IN',
        ];
        // если не выбран product_cat — сделаем SEO от product-type
        if ( ! $cat_term_id ) {
            $term_pt = get_term($product_type_term_id, 'product-type');
            if ($term_pt && !is_wp_error($term_pt)) {
                $seo['title']       = $term_pt->name;
                $seo['description'] = term_description($term_pt) ? wp_strip_all_tags(term_description($term_pt)) : $seo['description'];
                $seo['url']         = trailingslashit( get_term_link($term_pt) );
            }
        }
    }

    // 3) legacy бэкап, если новые параметры не пришли
    if ( empty($tax_query) && $term_id && taxonomy_exists($tax) ) {
        $tax_query[] = [
            'taxonomy'         => $tax,
            'field'            => 'term_id',
            'terms'            => [$term_id],
            'include_children' => true,
            'operator'         => 'IN',
        ];
        $term_legacy = get_term($term_id, $tax);
        if ($term_legacy && !is_wp_error($term_legacy)) {
            $seo['title']       = $term_legacy->name;
            $seo['description'] = term_description($term_legacy) ? wp_strip_all_tags(term_description($term_legacy)) : $seo['description'];
            $seo['url']         = trailingslashit( get_term_link($term_legacy) );
        }
    }

    if ( ! empty($tax_query) ) {
        $args['tax_query'] = ( count($tax_query) > 1 )
            ? array_merge( ['relation' => 'AND'], $tax_query )
            : $tax_query;
    }

    // === Основной запрос для списка товаров ===
    $q = new WP_Query($args);

    // === Подсчёт количества для всех product-type в рамках текущего product_cat ===
    // (НЕ учитываем активный product-type, чтобы остальные кнопки показывали "потенциальные" количества в текущей категории)
    $product_type_counts = [];
    $pt_terms = get_terms([
        'taxonomy'   => 'product-type',
        'hide_empty' => false, // хотим видеть и нули
        'parent'     => 0,
        'orderby'    => 'name',
        'order'      => 'ASC',
    ]);

    if ( ! is_wp_error($pt_terms) && $pt_terms ) {
        foreach ($pt_terms as $pt_term) {
            // Базовый tax_query для подсчёта: учитывает product_cat (если выбран), НО НЕ учитывает активный product-type
            $count_tax_query = [];
            if ( $cat_term_id ) {
                $count_tax_query[] = [
                    'taxonomy'         => 'product_cat',
                    'field'            => 'term_id',
                    'terms'            => [$cat_term_id],
                    'include_children' => true,
                    'operator'         => 'IN',
                ];
            }
            // Добавляем конкретный product-type
            $count_tax_query[] = [
                'taxonomy'         => 'product-type',
                'field'            => 'term_id',
                'terms'            => [$pt_term->term_id],
                'include_children' => true,
                'operator'         => 'IN',
            ];

            $count_args = [
                'post_type'      => 'product',
                'post_status'    => 'publish',
                'posts_per_page' => 1,           // минимально, нам нужен только found_posts
                'fields'         => 'ids',       // облегчаем выборку
                'no_found_rows'  => false,       // нужно увидеть общее количество
                'tax_query'      => ( count($count_tax_query) > 1 )
                    ? array_merge(['relation' => 'AND'], $count_tax_query)
                    : $count_tax_query,
            ];

            $q_count = new WP_Query($count_args);
            $product_type_counts[ $pt_term->term_id ] = (int) $q_count->found_posts;
            wp_reset_postdata();
        }
    }

    // === Рендер карточек ===
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

    wp_send_json_success([
        'html'                  => ob_get_clean(),
        'count'                 => (int) $q->found_posts,  // текущее количество результатов (с учётом обоих фильтров)
        'seo'                   => $seo,
        'product_type_counts'   => $product_type_counts,   // карта для обновления цифр на кнопках
    ]);
}

add_action('wp_ajax_buffet_filter_products', 'buffet_filter_products');
add_action('wp_ajax_nopriv_buffet_filter_products', 'buffet_filter_products');
