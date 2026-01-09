<?php
get_header();
?>
<main class="main single-product">
    <div class="product container container_first">
        <div class="product__information grid-12">
            <?php
            global $product;

            $product_id = get_the_ID();
            $product = new WC_product($product_id);
            $attachment_ids = $product->get_gallery_image_ids();
            ?>
            <div class="product__information__gallery">
                <div class="product__information__gallery__thumbs">
                    <button class="navigation__button _prev" style="transform: rotate(90deg);">
                        <svg width="10" height="16" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0.397833 8.49715L8.06071 15.7984C8.18857 15.9203 8.35844 15.9883 8.53508 15.9883C8.71173 15.9883 8.8816 15.9203 9.00946 15.7984L9.01771 15.7901C9.07991 15.7311 9.12943 15.6599 9.16328 15.5811C9.19712 15.5023 9.21457 15.4174 9.21457 15.3316C9.21457 15.2458 9.19712 15.1609 9.16328 15.0821C9.12943 15.0032 9.07991 14.9321 9.01771 14.873L1.80171 7.99802L9.01771 1.12577C9.07991 1.06669 9.12943 0.995561 9.16328 0.916729C9.19712 0.837895 9.21457 0.753002 9.21457 0.667211C9.21457 0.581421 9.19712 0.496526 9.16328 0.417694C9.12943 0.338861 9.07991 0.267737 9.01771 0.208648L9.00946 0.200398C8.8816 0.0785118 8.71173 0.0105158 8.53508 0.0105158C8.35844 0.0105158 8.18857 0.0785118 8.06071 0.200398L0.397833 7.50165C0.330441 7.56586 0.276789 7.64308 0.240131 7.72864C0.203472 7.8142 0.18457 7.90632 0.18457 7.9994C0.18457 8.09248 0.203472 8.1846 0.240131 8.27016C0.276789 8.35572 0.330441 8.43294 0.397833 8.49715Z" fill="white"></path>
                        </svg>
                    </button>
                    <div class="product__information__gallery__thumbs__inner">
                        <div class="swiper">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <img src="<?php the_post_thumbnail_url(); ?>" alt="" class="product__information__gallery__thumbs__image">
                                </div>
                                <?php foreach ( $attachment_ids as $attachment_id ) : ?>
                                    <div class="swiper-slide">
                                        <img src="<?= wp_get_attachment_url($attachment_id); ?>" alt="" class="product__information__gallery__thumbs__image">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <button class="navigation__button _next" style="transform: rotate(-90deg);">
                        <svg width="10" height="16" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0.397833 8.49715L8.06071 15.7984C8.18857 15.9203 8.35844 15.9883 8.53508 15.9883C8.71173 15.9883 8.8816 15.9203 9.00946 15.7984L9.01771 15.7901C9.07991 15.7311 9.12943 15.6599 9.16328 15.5811C9.19712 15.5023 9.21457 15.4174 9.21457 15.3316C9.21457 15.2458 9.19712 15.1609 9.16328 15.0821C9.12943 15.0032 9.07991 14.9321 9.01771 14.873L1.80171 7.99802L9.01771 1.12577C9.07991 1.06669 9.12943 0.995561 9.16328 0.916729C9.19712 0.837895 9.21457 0.753002 9.21457 0.667211C9.21457 0.581421 9.19712 0.496526 9.16328 0.417694C9.12943 0.338861 9.07991 0.267737 9.01771 0.208648L9.00946 0.200398C8.8816 0.0785118 8.71173 0.0105158 8.53508 0.0105158C8.35844 0.0105158 8.18857 0.0785118 8.06071 0.200398L0.397833 7.50165C0.330441 7.56586 0.276789 7.64308 0.240131 7.72864C0.203472 7.8142 0.18457 7.90632 0.18457 7.9994C0.18457 8.09248 0.203472 8.1846 0.240131 8.27016C0.276789 8.35572 0.330441 8.43294 0.397833 8.49715Z" fill="white"></path>
                        </svg>
                    </button>
                </div>
                <div class="product__information__gallery__main">
                    <?php if (get_field('product_tag')): ?>
                        <div class="product-item__tag">
                            <svg class="product-item__tag__icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect width="20" height="20" rx="10" fill="white"/>
                                <path d="M11.334 7.91406L11.3896 8.08691H15.8887L12.3955 10.624L12.249 10.7305L12.3047 10.9033L13.6387 15.0078L10.1465 12.4717L10 12.3643L9.85352 12.4717L6.36035 15.0078L7.69531 10.9033L7.75098 10.7305L7.60449 10.624L4.11133 8.08691H8.61035L8.66602 7.91406L10 3.80762L11.334 7.91406Z" stroke="#F07D2C" stroke-width="0.5"/>
                            </svg>
                            <div class="product-item__tag__title"><?= get_field('product_tag')->name; ?></div>
                        </div>
                    <?php endif; ?>
                    <div class="swiper">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <img src="<?php the_post_thumbnail_url(); ?>" alt="" class="product__information__gallery__main__image">
                            </div>
                            <?php foreach ( $attachment_ids as $attachment_id ) : ?>
                                <div class="swiper-slide">
                                    <img src="<?= wp_get_attachment_url($attachment_id); ?>" alt="" class="product__information__gallery__main__image">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="product__information__inner">
                <div class="product__information__content">
                    <?php
                    $unit = get_option('woocommerce_weight_unit');

                    $weight       = $product->get_attribute('pa_weight');
                    $weight_title = wc_attribute_label('pa_weight');

                    $volume       = $product->get_attribute('pa_volume');
                    $volume_title = wc_attribute_label('pa_volume');

                    if ( !empty($weight) ) {
                        $attr_value = $weight;
                        $attr_title = $weight_title;
                    } elseif ( !empty($volume) ) {
                        $attr_value = $volume;
                        $attr_title = $volume_title;
                    } else {
                        $attr_value = '';
                        $attr_title = '';
                    }

                    $quantity = $product->get_attribute('pa_quantity');
                    ?>
                    <h1 class="product__information__content__title"><?php the_title(); ?></h1>
                    <div class="product__information__content__weight"><?= $attr_title; ?>: <span><?= $attr_value ?></span></div>
                    <p class="product__information__content__description"><?php the_field('boxing_composition'); ?></p>
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
                    // вытащим только число
                    preg_match('/\d+/', $minOrderRaw, $matches);
                    $minOrder = isset($matches[0]) ? (int) $matches[0] : 1;
                    ?>
                    <div class="product-item__content__buttons__inner" data-product-id="<?php echo esc_attr($product->get_id()); ?>" data-min-value="<?= esc_attr($minOrder); ?>">
                        <button class="product-item__content__buttons__add <?php echo $cart_qty > 0 ? 'active' : ''; ?>">
                            В корзину
                        </button>
                        <div class="product-item__content__buttons__wrapper" style="<?php echo $cart_qty > 0 ? '' : 'display:none;'; ?>">
                            <button class="product-item__content__buttons__count-value">-</button>
                            <div class="product-item__content__buttons__count-number"><?php echo $cart_qty > 0 ? $cart_qty : 1; ?></div>
                            <button class="product-item__content__buttons__count-value">+</button>
                        </div>
                    </div>
                    <div class="product__information__content__price__inner">
                        <div class="product__information__content__price__title">Стоимость за бокс</div>
                        <div class="product__information__content__price__number"><?= esc_attr($product->get_price()); ?>₽</div>
                    </div>
                    <?php
                    if ($minOrder){
                        ?>
                        <div class="product__information__content__min">
                            <div class="product__information__content__min__title">Минимальный заказ</div>
                            <div class="product__information__content__min__value"><?= esc_html($minOrder); ?></div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <div class="product__information__contacts">
                    <div class="product__information__contacts__description">Есть вопросы? <a href="#form">Нажмите тут,</a> чтобы уточнить у менеджера</div>
                    <div class="product__information__contacts__social">
                        <?php if (get_field('footer_vk', 10)): ?>
                            <a href="<?php the_field('footer_vk', 10); ?>" class="product__information__contacts__social__item" target="_blank">
                                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="20" cy="20" r="20" fill="#F07D2C"/>
                                    <g clip-path="url(#clip0_444_19655)">
                                        <path d="M19.9995 7.51953C13.1069 7.51953 7.51953 13.1069 7.51953 19.9995C7.51953 26.8921 13.1069 32.4795 19.9995 32.4795C26.8921 32.4795 32.4795 26.8921 32.4795 19.9995C32.4795 13.1069 26.8921 7.51953 19.9995 7.51953ZM24.7991 21.5998C24.7991 21.5998 25.9028 22.6892 26.1745 23.1949C26.1823 23.2062 26.1871 23.2149 26.1888 23.2209C26.2989 23.4055 26.3262 23.5516 26.2707 23.659C26.1797 23.8384 25.8677 23.9268 25.7611 23.9346H23.8111C23.6759 23.9346 23.3925 23.8995 23.0493 23.6629C22.7854 23.4783 22.5254 23.1754 22.2719 22.8803C21.8936 22.4409 21.566 22.0613 21.2358 22.0613C21.1939 22.0611 21.1522 22.0677 21.1123 22.0808C20.8627 22.1614 20.5429 22.5176 20.5429 23.4666C20.5429 23.763 20.3089 23.9333 20.1438 23.9333H19.2507C18.9465 23.9333 17.3618 23.8267 15.9578 22.346C14.2392 20.5325 12.6922 16.8951 12.6792 16.8613C12.5817 16.626 12.7832 16.4999 13.0029 16.4999H14.9724C15.235 16.4999 15.3208 16.6598 15.3806 16.8015C15.4508 16.9666 15.7082 17.6231 16.1307 18.3615C16.8158 19.5653 17.2357 20.0541 17.5724 20.0541C17.6356 20.0534 17.6976 20.0373 17.7531 20.0073C18.1925 19.7629 18.1106 18.1964 18.0911 17.8714C18.0911 17.8103 18.0898 17.1707 17.8649 16.8639C17.7037 16.6416 17.4294 16.5571 17.263 16.5259C17.3304 16.433 17.4191 16.3576 17.5217 16.3062C17.8233 16.1554 18.3667 16.1333 18.9062 16.1333H19.2065C19.7915 16.1411 19.9423 16.1788 20.1542 16.2321C20.5832 16.3348 20.5923 16.6117 20.5546 17.5594C20.5429 17.8285 20.5312 18.1327 20.5312 18.4915L20.5273 18.7411C20.5143 19.2234 20.4987 19.7707 20.8393 19.9956C20.8833 20.0246 20.9349 20.0396 20.9875 20.0385C21.1058 20.0385 21.462 20.0385 22.4266 18.3836C22.723 17.8504 22.9815 17.297 23.2001 16.7274C23.2196 16.6936 23.2768 16.5896 23.3444 16.5493C23.3948 16.5254 23.4498 16.5125 23.5056 16.5116H25.8209C26.0731 16.5116 26.246 16.5493 26.2785 16.6468C26.3357 16.8015 26.2681 17.2734 25.2112 18.7047L24.7393 19.3274C23.7812 20.5832 23.7812 20.6469 24.7991 21.5998Z" fill="white"/>
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_444_19655">
                                            <rect width="26" height="26" fill="white" transform="translate(7 7)"/>
                                        </clipPath>
                                    </defs>
                                </svg>
                            </a>
                        <?php endif;
                        if (get_field('footer_whatsapp', 10)): ?>
                            <a href="<?php the_field('footer_whatsapp', 10); ?>" class="product__information__contacts__social__item" target="_blank">
                                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="20" cy="20" r="20" fill="#F07D2C"/>
                                    <path d="M19.8334 9C25.8166 9 30.6667 13.8501 30.6667 19.8333C30.6667 25.8166 25.8166 30.6667 19.8334 30.6667C17.9188 30.67 16.038 30.1633 14.3842 29.1988L9.00435 30.6667L10.469 25.2847C9.50372 23.6303 8.99666 21.7487 9.00002 19.8333C9.00002 13.8501 13.8501 9 19.8334 9ZM16.1414 14.7417L15.9247 14.7503C15.7846 14.76 15.6477 14.7968 15.5217 14.8587C15.4042 14.9253 15.297 15.0085 15.2032 15.1057C15.0732 15.2281 14.9995 15.3342 14.9204 15.4372C14.5197 15.9581 14.304 16.5978 14.3073 17.255C14.3094 17.7858 14.4481 18.3026 14.6648 18.7858C15.1079 19.7629 15.8369 20.7975 16.7989 21.7563C17.0308 21.987 17.2583 22.2188 17.5031 22.4344C18.6985 23.4868 20.1229 24.2457 21.6631 24.6509L22.2784 24.7452C22.4789 24.756 22.6793 24.7408 22.8808 24.7311C23.1962 24.7144 23.5042 24.629 23.7832 24.4808C23.9249 24.4075 24.0634 24.328 24.1981 24.2425C24.1981 24.2425 24.244 24.2114 24.3335 24.145C24.4798 24.0367 24.5697 23.9598 24.691 23.833C24.782 23.7391 24.8579 23.6301 24.9185 23.5058C25.003 23.3293 25.0875 22.9923 25.1222 22.7118C25.1482 22.4972 25.1406 22.3803 25.1374 22.3077C25.133 22.1918 25.0366 22.0715 24.9315 22.0206L24.301 21.7378C24.301 21.7378 23.3585 21.3273 22.7822 21.0651C22.7219 21.0388 22.6572 21.0238 22.5915 21.0207C22.5174 21.0129 22.4425 21.0212 22.3718 21.0449C22.3012 21.0687 22.2364 21.1073 22.182 21.1582C22.1766 21.1561 22.104 21.2178 21.3208 22.1668C21.2758 22.2272 21.2139 22.2729 21.1429 22.298C21.0719 22.3231 20.995 22.3264 20.9221 22.3077C20.8515 22.2888 20.7823 22.265 20.7152 22.2362C20.5809 22.1798 20.5343 22.1582 20.4422 22.1192C19.8202 21.8482 19.2445 21.4816 18.7359 21.0326C18.5994 20.9134 18.4727 20.7834 18.3427 20.6578C17.9165 20.2496 17.5451 19.7878 17.2377 19.2841L17.1738 19.1812C17.1286 19.1116 17.0915 19.0371 17.0633 18.9591C17.0221 18.7998 17.1294 18.672 17.1294 18.672C17.1294 18.672 17.3926 18.3838 17.515 18.2278C17.6342 18.0762 17.7349 17.9288 17.7999 17.8238C17.9278 17.6179 17.9679 17.4067 17.9007 17.2431C17.5974 16.5021 17.2839 15.7651 16.9604 15.032C16.8964 14.8868 16.7069 14.7828 16.5346 14.7622C16.4761 14.755 16.4176 14.7493 16.3591 14.7449C16.2136 14.7366 16.0678 14.738 15.9225 14.7493L16.1414 14.7417Z" fill="white"/>
                                </svg>
                            </a>
                        <?php endif;
                        if (get_field('footer_telegram', 10)): ?>
                            <a href="<?php the_field('footer_telegram', 10); ?>" class="product__information__contacts__social__item" target="_blank">
                                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="20" cy="20" r="20" fill="#F07D2C"/>
                                    <path d="M19.8333 9C13.8533 9 9 13.8533 9 19.8333C9 25.8133 13.8533 30.6667 19.8333 30.6667C25.8133 30.6667 30.6667 25.8133 30.6667 19.8333C30.6667 13.8533 25.8133 9 19.8333 9ZM24.86 16.3667C24.6975 18.0783 23.9933 22.2383 23.6358 24.1558C23.4842 24.9683 23.1808 25.2392 22.8992 25.2717C22.2708 25.3258 21.7942 24.86 21.1875 24.4592C20.2342 23.8308 19.6925 23.4408 18.7717 22.8342C17.6992 22.13 18.3925 21.74 19.01 21.1117C19.1725 20.9492 21.9458 18.425 22 18.1975C22.0075 18.163 22.0065 18.1273 21.9971 18.0933C21.9876 18.0593 21.97 18.0281 21.9458 18.0025C21.8808 17.9483 21.7942 17.97 21.7183 17.9808C21.6208 18.0025 20.1042 19.01 17.1467 21.0033C16.7133 21.2958 16.3233 21.4475 15.9767 21.4367C15.5867 21.4258 14.85 21.22 14.2975 21.0358C13.615 20.8192 13.0842 20.7 13.1275 20.3208C13.1492 20.1258 13.42 19.9308 13.9292 19.725C17.0925 18.3492 19.1942 17.4392 20.245 17.0058C23.2567 15.7492 23.8742 15.5325 24.2858 15.5325C24.3725 15.5325 24.5783 15.5542 24.7083 15.6625C24.8167 15.7492 24.8492 15.8683 24.86 15.955C24.8492 16.02 24.8708 16.215 24.86 16.3667Z" fill="white"/>
                                </svg>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tabs container grid-12">
        <div class="tabs__item active" data-tab="delivery">Доставка и оплата</div>
        <div class="tabs__item" data-tab="questions">Ответы на вопросы</div>
        <div class="tabs__content active" data-content="delivery">
            <div class="tabs__content__delivery"><p>Мы готовы доставить Ваш заказ по Ростову-на-Дону. Стоимость доставки составляет 400 руб.<br>Так же, мы осуществляем доставку по Ростовской области. Стоимость доставки рассчитывается индивидуально, исходя из удалённости населённого пункта от Ростова-на-Дону, а так же от объёма Вашего заказа. Рассчитать доставку можно оставив заявку на нашем сайте или позвонив по телефону – +7 (928) 620-80-10.</p></div>
        </div>
        <div class="tabs__content" data-content="questions">
            <div class="tabs__content__questions">
                <?php
                    while ( have_rows('repeater_questions_products', 110) ) : the_row();
                        ?>
                        <div class="tabs__content__questions__item">
                            <div class="tabs__content__questions__item__title"><?php the_sub_field('question') ?></div>
                            <p class="tabs__content__questions__item__description"><?php the_sub_field('answer') ?></p>
                        </div>
                    <?php
                    endwhile;
                ?>
            </div>
        </div>
    </div>
    <div class="product-block">
        <div class="product-block__title">Популярные фуршетные наборы на любой повод</div>
        <div class="product-block__list grid-12">
            <?php
            $args = [
                'post_type' => 'product',
                'taxonomy' => 'gotovye-boksy',
                'posts_per_page' => 4,
                'post_status' => 'publish',
                'orderby' => 'rand',
                'tax_query' => [
                    [
                        'taxonomy' => 'product_cat',
                        'field'    => 'slug',
                        'terms'    => ['gotovye-boksy'],
                    ],
                ],
            ];
            $loop = new WP_Query($args);

            while ($loop->have_posts()) : $loop->the_post();
                global $product;
                ?>
                <div class="product-item">
                    <a href="<?php the_permalink(); ?>">
                        <img src="<?php the_post_thumbnail_url(); ?>" alt="" class="product-item__image">
                    </a>
                    <div class="product-item__content">
                        <div class="product-item__content__inner">
                            <h3 class="product-item__content__title"><?php the_title(); ?></h3>
                            <div class="product-item__content__weight"><?= $product->get_weight(); ?> гр</div>
                        </div>
                        <p class="product-item__content__description"><?php the_field('boxing_composition'); ?></p>
                        <div class="product-item__content__price"><?= $product->get_price_html(); ?></div>
                        <div class="product-item__content__buttons">
                            <div class="product-item__content__buttons__inner" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                                <button class="product-item__content__buttons__add">В корзину</button>
                                <div class="product-item__content__buttons__wrapper">
                                    <button class="product-item__content__buttons__count-value">-</button>
                                    <div class="product-item__content__buttons__count-number">1</div>
                                    <button class="product-item__content__buttons__count-value">+</button>
                                </div>
                            </div>
                            <a href="<?php the_permalink(); ?>" class="product-item__content__buttons__more">Подробнее</a>
                        </div>
                    </div>
                </div>
            <?php
            endwhile;
            wp_reset_postdata();
            ?>
        </div>
    </div>
</main>
<?php
get_footer();
?>
