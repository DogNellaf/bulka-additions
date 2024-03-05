<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\components\Service;

/* @var $this yii\web\View */
/* @var $cart \common\models\Cart */
$this->title = "Корзина";

/* @var $user \common\entities\User */
$user = Yii::$app->user->identity;

/* @var $cost_module \common\entities\Modules */
$cost_module = \common\entities\Modules::findOne(9);
?>

<!--jQuery-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<!--Plugin JavaScript file-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js"></script>

<div id="cart" class="cart page padded padded_bottom">

    <div class="page_header">
        <div class="wrapper">
            <div class="title title_1 font_2">
                <?= \frontend\components\Service::strSplit(Html::encode($this->title)); ?>
            </div>
        </div>
    </div>

    <div class="cart_block_wrap animated">
        <div class="wrapper">
            <div class="cart_block">
                <div class="cart_row_header cart_row">
                    <div class="title">
                        товар
                    </div>
                    <div class="options_col">
                        опции
                    </div>
                    <div class="count_col">
                        количество
                    </div>
                    <div class="price">
                        стоимость
                    </div>
                    <div class="date">
                        ближайшая дата доставки
                    </div>
                    <div class="del">

                    </div>
                </div>
                <div class="cart_items">
                    <?php foreach ($cart->getItems() as $item):
                        /* @var  $item \common\models\CartItem */
                        $product = $item->getProduct();
                        $url = Url::to(['catalog/product', 'slug' => $product->slug]);
                        ?>
                        <div class="cart_item cart_row">
                            <div class="cart_item_pda_title">
                                товар
                            </div>
                            <div class="title">
                                <a class="img" href="<?= $url ?>">
                                    <img src="<?= $product->image_name ? $product->image : '/images/default_thumb.png'; ?>" alt="">
                                </a>
                                <div class="cont">
                                    <a class="name" href="<?= $url ?>">
                                        <?= Html::encode($product->title) ?>
                                    </a>
                                    <?php if ($item->weight) : ?>
                                        <div class="weight">
                                            <?= $item->getWeightTitle(); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="cart_item_pda_title">
                                опции
                            </div>
                            <div class="options_col">
                                <?php if ($product->productOptions) : ?>
                                    <div class="cart_options">
                                        <div class="cart_opts">
                                            <div class="cart_item_modify_block">
                                                <div class="cart_item_opts_list">
                                                    <?php foreach ($item->getOptions() as $option) : ?>
                                                        <div class="cart_item_opt">
                                                            <?= $option->title; ?>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                                <div class="cart_item_modify_opts">
                                                    <div class="cart_item_modify_opts_list">
                                                        <?php foreach ($product->productOptions as $option) : ?>
                                                            <?php
                                                            $checked = '';
                                                            if (in_array($option->id, $item->getOptionsIds())) {
                                                                $checked = 'checked';
                                                            }
                                                            ?>
                                                            <label class="option cartProductOption">
                                                                <input type="checkbox" name="product-option"
                                                                       value="<?= $option->id; ?>"
                                                                       data-price="<?= $option->getPrice(); ?>"
                                                                    <?= $checked; ?>
                                                                >
                                                                <i></i>
                                                                <span class="option_title"><?= $option->title; ?></span>
                                                                <!--<span class="option_price">- <?/*= Service::formatPrice($option->price) */?></span>-->
                                                            </label>
                                                        <?php endforeach; ?>
                                                    </div>
                                                    <a href="<?= Url::to(['cart/update-options', 'id' => $item->getProductId(), 'weight' => $item->weight, 'options' => $item->options, 'newOptions' => $item->options]); ?>"
                                                       data-href="<?= Url::to(['cart/update-options', 'id' => $item->getProductId(), 'weight' => $item->weight, 'options' => $item->options]); ?>"
                                                       class="cart_update_options cartUpdate cartButton common_btn">
                                                        Изменить опции
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="cart_options_update_btn cart_item_modify_btn">
                                                <i class="icon-note"></i>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </div>
                            <div class="cart_item_pda_title">
                                количество
                            </div>
                            <div class="count_col">
                                <div class="counts">
                                    <a href="<?= Url::to(['cart/minus', 'id' => $item->getProductId(), 'weight' => $item->weight, 'options' => $item->options]); ?>"
                                       data-href="<?= Url::to(['cart/minus', $item->getProductId(), 'weight' => $item->weight, 'options' => $item->options]); ?>"
                                       class="count_btn count cartButton">
                                        -
                                    </a>
                                    <span class="quantity count"><?= $item->getQuantity() ?></span>
                                    <a href="<?= Url::to(['cart/plus', 'id' => $item->getProductId(), 'weight' => $item->weight, 'options' => $item->options]); ?>"
                                       data-href="<?= Url::to(['cart/plus', 'id' => $item->getProductId(), 'weight' => $item->weight, 'options' => $item->options]); ?>"
                                       class="count_btn count cartButton">
                                        +
                                    </a>
                                </div>
                            </div>
                            <div class="cart_item_pda_title">
                                стоимость
                            </div>
                            <div class="price">
                                <?= Service::formatPrice($item->getCost()) ?>
                            </div>
                            <div class="cart_item_pda_title">
                                ближайшая дата доставки
                            </div>
                            <div class="date">
                                <?php
                                $min_delivery_date_timestamp = time();
                                if ($item->getProduct()->min_delivery_days) {
                                    $min_delivery_date_timestamp += $item->getProduct()->min_delivery_days * 24 * 3600;
                                }
                                $min_delivery_date = Yii::$app->formatter->asDatetime($min_delivery_date_timestamp, 'dd MMMM');
                                echo $min_delivery_date;
                                ?>
                            </div>
                            <div class="cart_item_pda_title">
                            </div>
                            <div class="del">
                                <a class="cart-remove"
                                   href="<?= Url::to(['cart/remove', 'id' => $item->getProductId(), 'weight' => $item->weight, 'options' => $item->options]) ?>" data-method="post">
                                    <span class="icon-delete"></span>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="cart_bottom">
                    <div class="left">
                        <?php if (Yii::$app->user->isGuest || !$user->isBusinessWholesale()) : ?>
                            <p>
                                Минимальная сумма заказа: <?= Service::formatPrice($cost_module->min_order_sum) ?>
                            </p>
                            <p>
                                Бесплатная доставка в пределах МКАД: от <?= Service::formatPrice($cost_module->min_free_delivery_sum) ?>
                            </p>
                            <p>
                                Бесплатная доставка за пределы МКАД: от <?= Service::formatPrice($cost_module->min_free_delivery_sum_outer_mkad) ?>
                            </p>
                        <?php endif; ?>
                    </div>
                    <div class="center">
                        <p>
                            Доступно бонусов: <strong>4 449</strong>
                        </p>
                        <div class="bonuses-block">
                            Списать:
                            <div class="bones-container">
                                <div class="range-block">
                                    <input type="text" class="js-range-slider" name="my_range" value="" />
                                    <script>
                                        $(".js-range-slider").ionRangeSlider({
                                            type: "double",
                                            min: 0,
                                            max: 1000,
                                            from: 200,
                                            to: 500,
                                            grid: true
                                        });
                                    </script>
                                </div>
                            </div>
                            <div class="bonuses-val">
                                <input type="number" name="bonuses" id="bonuses" value="0">
                            </div>
                        </div>
                        <div class="tip">
                            Возможно списать до 20% от суммы заказа
                        </div>
                    </div>
                    <div class="right">
                        <div class="cart_sum_wrap">
                            <span class="cart_sum_title">Общая стоимость:</span>
                            <span class="cart_sum"><?= Service::formatPrice($cart->getCost()) ?></span>
                        </div>
                        <?php if ($cart->getItems()): ?>
                            <?php if ($cart->isAllowedCost() && $cart->isEnoughItemsQty()): ?>
                                <a href="<?= Url::to(['cart/checkout']) ?>" class="cart_checkout_btn common_btn">
                                    оформить заказ
                                </a>
                            <?php elseif (!$cart->isEnoughItemsQty()): ?>
                                <p>
                                    Минимально допустимое количество:
                                </p>
                                <?php foreach ($cart->getNotEnoughQtyItems() as $notEnoughQtyItem) : ?>
                                    <p>
                                        <?= $notEnoughQtyItem['title']; ?>
                                        <?= ($notEnoughQtyItem['weight']) ? ' - ' . $notEnoughQtyItem['weight'] : ''; ?>
                                        <?= ' : ' . $notEnoughQtyItem['min_qty']; ?> шт.
                                    </p>
                                <?php endforeach; ?>
                            <?php elseif (!$cart->isAllowedCost()): ?>
                                <p>
                                    Сумма заказа слишком низкая
                                </p>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
window.$ = jQuery;
window.jQuery = jQuery;

$(function () {
    $(document).ready(function () {

        //notification opening count
        let notificationOpenIndex = 0;

        //sliders
        let $mainSlider = $('.main_slider .list');
        if ($mainSlider.length) {
            $mainSlider.slick({
                speed: 600,
                slidesToScroll: 1,
                slidesToShow: 1,
                infinite: true,
                dots: false,
                arrows: true,
                prevArrow: $('.main_slider .slider_nav.prev'),
                nextArrow: $('.main_slider .slider_nav.next'),
            });

            let $status = $mainSlider.closest('.main_slider').find('.slider_counter .current');

            $mainSlider.on('init reInit afterChange', function (event, slick, currentSlide, nextSlide) {
                //currentSlide is undefined on init -- set it to 0 in this case (currentSlide is 0 based)
                let i = (currentSlide ? currentSlide : 0) + 1;
                i = (i < 10) ? '0' + i : i;
                $status.text(i /*+ '/' + slick.slideCount*/);
            });
        }

        let $mainRecommendSlider = $('.main_recommend_slider .list');
        if ($mainRecommendSlider.length) {
            $mainRecommendSlider.slick({
                speed: 600,
                slidesToScroll: 4,
                slidesToShow: 4,
                infinite: false,
                dots: false,
                arrows: true,
                prevArrow: $('.main_recommend_slider .slider_nav.prev'),
                nextArrow: $('.main_recommend_slider .slider_nav.next'),
                responsive: [
                    {
                        breakpoint: 1400,
                        settings: {
                            slidesToScroll: 3,
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 991,
                        settings: {
                            slidesToScroll: 2,
                            slidesToShow: 2,
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToScroll: 1,
                            slidesToShow: 1,
                        }
                    },
                ]
            });
        }

        let $mainStoriesSlider = $('.main_stories_slider .list');
        if ($mainStoriesSlider.length) {
            $mainStoriesSlider.slick({
                speed: 600,
                infinite: false,
                dots: false,
                arrows: false,
                variableWidth: true,
            });
        }

        common_vars();

        if ($("*").is(".map")) {
            $('.map').each(function () {
                let $mapPlace = $(this).find('.map_place');
                initialize($mapPlace.data('id'), $mapPlace.data('lat'), $mapPlace.data('lng'));
            });
        }

        if ($("*").is(".checkout_map")) {
            // закомментировано для яндекс карты
//            initCheckoutMap();
        }

        catalogMobileScroll();

        $('body').imagesLoaded(function () {

            common_vars();

            //RESIZE
            $(window).resize(function () {

                common_vars();

            });
        });

        //FUNCTIONS

        //common variables
        function common_vars() {
            let w_w = $(window).width();
            let w_h = $(window).height();
            let headmargin = $('#header').innerHeight();
            let footmargin = $('#footer').innerHeight();

            // $('#main').css({marginTop: headmargin});
            $('#main').css('min-height', (w_h - footmargin) + 'px');
            //$('.fullheight').innerHeight(w_h - footmargin);

            if ($('#personal').length) {
                let hash = window.location.hash;
                if (hash.length) {
                    hash = hash.replace('#', '');
                    $('.personal_nav_item a[data-block="' + hash + '"]').click();
                }
            }

            headerDown();
            animatedTitles();
            animatedBlocks();
            animatedRoundBtns();
            animatedRotateImages();
            imgScroll();
            catalogNavScroll();
            //windowSizeCheck();
        }

        $(window).scroll(function () {
            headerDown();
            animatedTitles();
            animatedBlocks();
            animatedRoundBtns();
            animatedRotateImages();
            imgScroll();
            catalogNavScroll();
        });

        function headerDown() {
            let $header = $("#header");
            if ($(this).scrollTop() > $header.innerHeight()) {
                $header.addClass('down');
            } else {
                $header.removeClass('down');
            }
        }

        function catalogNavScroll() {
            let $wrap = $('.catalog_block');
            let $block = $wrap.find('.catalog_nav');
            if (!$block.length)
                return false;
            if ($(window).width() < 768) {
                $block.css('top', 0);
                return false;
            }
            let scrollStart = $wrap.offset().top - $('#header').innerHeight();
            let scrollValue = 0;
            if ($(this).scrollTop() > scrollStart) {
                scrollValue = $(this).scrollTop() - scrollStart;
                if (scrollValue + $block.innerHeight() > $wrap.innerHeight()) {
                    scrollValue = $wrap.innerHeight() - $block.innerHeight() - 50;
                }
            }
            $block.css('top', scrollValue + 'px');
        }

        //animated titles
        function animatedTitles() {

            $('.anim_title').each(
                function () {
                    if ($(window).scrollTop() > ($(this).offset().top - $(window).height() + 100)) {
                        $(this).addClass('ready');
                    }
                }
            );
        }

        //animated blocks
        function animatedBlocks() {

            $('.animated').each(
                function () {
                    if ($(window).scrollTop() > ($(this).offset().top - $(window).height() + 100)) {
                        $(this).addClass('ready');
                    }
                }
            );
        }

        //animated round buttons
        function animatedRoundBtns() {

            $('.common_btn_2').each(
                function () {
                    if ($(window).scrollTop() > ($(this).offset().top - $(window).height() + 100)) {
                        $(this).addClass('ready');
                    }
                }
            );
        }

        //animated rotated images
        function animatedRotateImages() {

            $('.anim_rot_img').each(
                function () {
                    if ($(window).scrollTop() < ($(this).offset().top - $(window).height() / 2)
                        && $(window).scrollTop() > ($(this).offset().top - $(window).height() - 100)) {
                        let rot = 1 + (((($(this).offset().top - ($(window).scrollTop())) / $(window).height()) - 1) * 2);
                        let rotPerc = rot * 4;
                        $(this).css('transform', 'rotate(' + rotPerc + 'deg)');
                    } else {
                        $(this).css('transform', 'rotate(0)');
                    }
                }
            );
        }

        //image's scroll
        function imgScroll() {
            let imgs = $('.img_scroll');
            imgs.each(
                function(index) {

                    var wrap = $(this).closest('.img_scroll_wrap');
                    var w_s = $(window).scrollTop();
                    var w_h = $(window).height();
                    var wr_h = wrap.height();
                    var wr_of_t = wrap.offset().top;

                    if((w_s >= (wr_of_t - w_h)) && (w_s <= (wr_of_t + wr_h)))
                    {
                        var t_h = $(this)[0].getBoundingClientRect().height;
                        var scrollHeight = w_h - wr_h;
                        if(scrollHeight <= 0) {
                            scrollHeight = w_h;
                        }
                        var start = wr_of_t + wr_h - w_h;
                        var scrollKoef = (w_s - start) / scrollHeight * 2 - 1;
                        var t_t = (t_h - wr_h) / 2 * scrollKoef;
                        $(this).css('top', t_t+'px');
                    }
                }
            );
        }

        //catalog mobile scroll
        function catalogMobileScroll() {
            let $catalogBlock = $('.catalog_list_block');
            if (!$catalogBlock.length)
                return false;
            if ($(window).width() > 768)
                return false;
            if (window.location.search.length)
                $('body,html').animate({scrollTop: $catalogBlock.offset().top - 100}, 1000);
        }

        //current window size
        function windowSizeCheck() {
            let whBlock = 'window_size_check';
            if (!($("div").is("#" + whBlock))) {
                $('body').append('<div id="' + whBlock + '"></div>');
                $('#' + whBlock).css({
                    'position': 'fixed',
                    'z-index': '10000000',
                    'left': '0',
                    'bottom': '0',
                    'background-color': 'rgba(255,255,255,0.8)',
                    'color': '#000',
                    'font-size': '10px',
                    'padding': '1px'
                });
            }
            $('#' + whBlock).html($(window).width() + 'X' + $(window).height());
        }


        //cart
        $(document).on("click", ".cartButton", function (event) {
            event.preventDefault();
            let url = $(this).attr('href');
            if ($(this).hasClass('cartButtonWithOptions')) {
                url = createCartButtonUrl(url);
            }
            console.log(url);
            ajaxCartNotification(url);
        });

        function createCartButtonUrl(url) {
            let $weightBlock = $('.productWeight input:checked');
            if ($weightBlock.length) {
                let weight = "weight="+$weightBlock.val();
                url+="&"+weight;
            }

            let $links = $('.cartButtonWithOptions');
            if ($links.length) {
                let options = [];
                $('.productOption input:checked').each(function(){
                    options.push($(this).val());
                });
                let optionsStr = "options="+options.join('__');

                url+="&"+optionsStr;
            }
            return url;
        }

        function ajaxCartNotification(url) {
            $.ajax({
                url: url,
                type: "GET",
                success: function (res) {
                    // if (!res) alert("Ошибка!");
                    showCartNotification(res);
                    ajaxCartReload();
                    ajaxCartCounterReload();
                    productDataReload();
                    catProductDataReload();
                },
                error: function () {
                    // alert('Ошибка AJAX!');
                }
            });
        }

        function ajaxCartReload() {
            if ($("*").is("#cart")) {
                $.ajax({
                    url: window.location,
                    type: "GET",
                    success: function (res) {
                        // if (!res) alert("Ошибка!");
                        $('.cart_block').html($(res).find('.cart_block').html());
                    },
                    error: function () {
                        // alert('Ошибка AJAX!');
                    }
                });
            }
        }

        function ajaxCartCounterReload() {
            $.ajax({
                url: window.location,
                type: "GET",
                success: function (res) {
                    // if (!res) alert("Ошибка!");
                    $('.cart-qty').html($(res).find('.cart-qty').html());
                },
                error: function () {
                    // alert('Ошибка AJAX!');
                }
            });
        }

        function showCartNotification(cart) {
            if (cart) {
                $('#notification_cart_popup .cart_popup_ins').html(cart);
                $('#notification_cart_popup').addClass('active');
                notificationOpenIndex++;
                let curOpenIndex = notificationOpenIndex;

                setTimeout(function () {
                    if (curOpenIndex !== notificationOpenIndex)
                        return;
                    $('#notification_cart_popup').removeClass('active');
                },2000)
            }
        }

        function productDataReload() {
            let $productBlock = $('.product_ins_top');
            if (!$productBlock.length)
                return;

            let $block = $('#cartRender');
            if ($block.find('.count').length) {
                let qty = $block.find('.count').html();
                $productBlock.find('.quantity').html(qty);
            }
        }

        function catProductDataReload() {
            let $productsBlock = $('.product_list');
            if (!$productsBlock.length)
                return;

            let $block = $('#cartRender');
            if ($block.data('id') > 0) {
                let $product = $productsBlock.find('.product_list_item[data-id="' + $block.data('id') + '"]');
                let qty = $block.find('.count').html();
                $product.find('.quantity').html(qty);
            }
        }

        //product options change
        $(document).on("change", ".productOption input, .productWeight input", function (event) {
            productPriceRecalc();
            productShowMinQuantity();
        });

        $(document).on("change", ".productWeight input", function (event) {
            let balance = $(this).data('balance');
            $('.cart_btn_block').removeClass('hidden');

            if (balance === 0) {
                $('.cart_btn_block.is_balance').addClass('hidden');
            } else {
                $('.cart_btn_block.no_balance').addClass('hidden');
            }
        });

        function productPriceRecalc() {
            let $basePriceBlock = $('.productPrice');
            let basePrice = $basePriceBlock.data('base-price');
            let $weightBlock = $('.productWeight input:checked');
            if ($weightBlock.length) {
                basePrice = $weightBlock.data('price');
            }
            $('.productOption input:checked').each(function () {
                basePrice += $(this).data('price');
            });
            $basePriceBlock.html(prettifyNumbers(basePrice) + ' ₽');
        }

        function productShowMinQuantity() {
            let $weightBlock = $('.productWeight input:checked');
            if ($weightBlock.length) {
                $('.product_min_qty').removeClass('active');
                $('.product_min_qty[data-weight=' + $weightBlock.val() + ']').addClass('active');
            }
        }

        //cart product options change
        $(document).on("change", ".cartProductOption input", function (event) {
            let $block = $(this).closest('.cart_item_modify_block');
            let $link = $block.find('.cartUpdate');

            let options = [];
            $block.find('.cartProductOption input:checked').each(function(){
                options.push($(this).val());
            });
            let optionsStr = "newOptions="+options.join('__');

            let href = $link.data('href');
            href+="&"+optionsStr;
            $link.attr('href', href);
        });

        //cart product options show
        $(document).on("click", ".cart_item_modify_btn", function (event) {
            let $item = $(this).closest('.cart_item');
            $item.find('.cart_item_opts_list').slideToggle(0);
            $item.find('.cart_item_modify_opts').slideToggle(0);
        });

        $(document).on("click", ".add-address-link", function (event) {
            event.preventDefault();
            let url = $(this).attr('href');
            ajaxAddAddress(url);
        });

        function ajaxAddAddress(url) {
            $.ajax({
                url: url,
                type: "GET",
                success: function (res) {
                    // if (!res) alert("Ошибка!");
                    $('.add_address_popup .min_popup_cont').html($(res).find('.ajax-cont').html());
                    // console.log(res);
                    $('.add_address_popup').addClass('active');
                },
                error: function () {
                    // alert('Ошибка AJAX!');
                }
            });
        }

        //cart


        //prettify numbers
        function prettifyNumbers(a) {
            let b = Math.round(a) + '';
            b = b.replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 ');
            return b;
        }

        //CLICKS

        //pda menu
        $('.pda_menu_btn_ins').click(function () {
            $(this).toggleClass('active');
            $('.pda_menu').toggleClass('active');
            $('#header').toggleClass('pda_open');
        });
        
        //show checkout map
        $('.checkout_map_btn').click(function () {
            $('.checkout_map_wrap').slideToggle();
        });

        //close min popup
        $('.min_popup_close').click(function () {
            $(this).closest('.min_popup').removeClass('active');
            return false;
        });

        //close popup
        $('.popup_close').click(function () {
            $(this).closest('.popup').removeClass('active');
            return false;
        });

        //search popup
        $('.search_popup_btn').click(function () {
            $('.search_popup').toggleClass('active');
            return false;
        });

        //reserve popup
        $('.reserve_popup_btn').click(function () {
            $('.reserve_popup').toggleClass('active');
            return false;
        });

        //scroll to second screen
        $('.scroll_down_btn').click(function () {
            $('body,html').animate({scrollTop: $(window).height()}, 1000);
        });

        //catalog nav
        $('.catalog_nav_item_link.child_link').click(function (e) {
            e.preventDefault();
            let $parent = $(this).closest('.catalog_nav_item');
            $parent.toggleClass('open');
            $parent.find('.catalog_nav_child_list').slideToggle();
        });

        //psevdoselect
        $(document).on('click', '.select_cur' ,function (event) {
            $(this).toggleClass('active');
            $(this).closest('.select_wrap').find('.select_list').slideToggle();
        });

        $(document).on('click', '.select_opt' ,function (event) {
            let cur_wrap = $(this).closest('.select_wrap');
            cur_wrap.find('.select_cur').removeClass('active');
            cur_wrap.find('.select_cur_text').text($(this).text());
            cur_wrap.find('.select_list').slideUp();

            if ($(this).closest('.reserveform-restaurant-id-block').length) {
                $('#reserveform-restaurant_id').val($(this).data('id'));
            }
        });

        //personal nav
        $('.personal_nav_item a').click(function () {
            if ($(this).hasClass('active'))
                return false;

            $('.personal_nav_item a').removeClass('active');
            $(this).addClass('active');
            let block = '#' + $(this).data('block');
            $('.personal_block').slideUp();
            $(block).slideDown();
            if(history.pushState) {
                history.pushState(null, null, block);
            }
            else {
                location.hash = block;
            }
            return false;
        });

        //personal addresses list nav
        $('.personal_address_nav').click(function () {
            if ($(this).hasClass('active'))
                return false;
            $('.personal_address_nav').removeClass('active');
            $(this).addClass('active');
            let idx = $(this).index();
            $('.personal_address').slideUp();
            $('.personal_address').eq(idx).slideDown();
            $('.personal_address_note').slideUp();
            $('.personal_address_note').eq(idx).slideDown();
        });

        //checkout addresses switch
        $(document).on("change", "input[name='checkout_addresses_switch']", function (event) {
            if ($(this).hasClass('clear_address')) {
                $('#orderform-street').val('');
                $('#orderform-house').val('');
                $('#orderform-apartment').val('');
                $('#orderform-floor').val('');
                $('#orderform-entrance').val('');
                $('#orderform-intercom').val('');
                $('#orderform-note').val('');
            } else {
                let $addressItems = $('.check_info_addresses_item');
                $addressItems.removeClass('active');
                let $addressItem = $addressItems.eq(0);
                $addressItem.addClass('active');
                $('#orderform-street').val($addressItem.data('street'));
                $('#orderform-house').val($addressItem.data('house'));
                $('#orderform-apartment').val($addressItem.data('apartment'));
                $('#orderform-floor').val($addressItem.data('floor'));
                $('#orderform-entrance').val($addressItem.data('entrance'));
                $('#orderform-intercom').val($addressItem.data('intercom'));
                $('#orderform-note').val($addressItem.data('note'));
            }
        });

        $('.check_info_addresses_item').click(function () {
            if ($(this).hasClass('active'))
                return false;
            $('.check_info_addresses_item').removeClass('active');
            $(this).addClass('active');
            $('#orderform-street').val($(this).data('street'));
            $('#orderform-house').val($(this).data('house'));
            $('#orderform-apartment').val($(this).data('apartment'));
            $('#orderform-floor').val($(this).data('floor'));
            $('#orderform-entrance').val($(this).data('entrance'));
            $('#orderform-intercom').val($(this).data('intercom'));
            $('#orderform-note').val($(this).data('note'));
        });

        //history item detail
        $('.history_item').click(function () {
            let $parent = $(this).closest('.history_item_wrap');
            $parent.toggleClass('active');
            $parent.find('.history_item_detail').slideToggle();
        });

        //contacts switch
        $('.contacts_nav_item a').click(function (e) {
            e.preventDefault();
            let $parent = $(this).closest('.contacts_nav_item');
            if ($parent.hasClass('active'))
                return;

            let target = $(this).data('contact');
            $('.contacts_nav_item').removeClass('active');
            $parent.addClass('active');
            $('.contacts_list_item').slideUp();
            $('.contacts_list_item[data-contact=' + target + ']').slideDown();
            $('.map').slideUp();
            $('.map[data-contact=' + target + ']').slideDown();
        });

    });


    //user fav products
    $(document).on('click', '.favAdd' ,function (event) {
        event.preventDefault();
        event.stopPropagation();
        let id = $(this).attr('data-id');
        let elem = $(this);
        $.ajax({
            url: '/catalog/favorite-add?id='+id,
            type: 'POST',
            success: function(data){
                elem.removeClass('favAdd');
                elem.addClass('favDel');
                elem.html('<i class="icon-heart"></i><span>В избранном</span>');
            }
        });
        return false;
    });

    $(document).on('click', '.favDel' ,function (event) {
        event.preventDefault();
        event.stopPropagation();
        let id = $(this).attr('data-id');
        let elem = $(this);
        $.ajax({
            url: '/catalog/favorite-del?id='+id,
            type: 'POST',
            success: function(data){
                elem.addClass('favAdd');
                elem.removeClass('favDel');
                elem.html('<i class="icon-heart-o"></i><span>Добавить в избранное</span>');
            }
        });
        return false;
    });

    $(document).on('click', '.fav-delete-link' ,function (event) {
        event.preventDefault();
        event.stopPropagation();
        if (confirm('Вы уверены, что хотите удалить блюдо?')){
            let id = $(this).attr('data-id');
            let elem = $(this);
            $.ajax({
                url: '/catalog/favorite-del?id='+id,
                type: 'POST',
                success: function(data){
                    elem.closest('.personal_favourites_item').remove();
                }
            });
        }
        return false;
    });

    if ($("#range_input").length) {
        // let max = $("#range_input").parents('.simple-input').data('max');

        let max = $("#range_input").data('max');

        let from, to;

        let saveResult = function (data) {
            from = data.from;

            $('#bonuses').val(from);
        };

        $("#range_input").ionRangeSlider({
            min: 0,
            max: max,
            from: 0,
            force_edges: true,
            grid: true,
            onChange: saveResult,
            onFinish: saveResult
        });

    }


    if ($(".code-timer").length) {

        code_timer ();

    }

    function code_timer () {
        let $timer = $(".code-timer");

        let count = $timer.data('start');
        $timer.text(count);

        let x = setInterval(function() {

            count = count - 1;

            if (count < 1) {
                clearInterval(x);
                $('.get-new-code').removeClass('timer-active');

            } else {
                $timer.text(count);
            }
        }, 1000);
    }

    $('.get-new-code-btn').click(function () {
        $('.get-new-code').addClass('timer-active');
        code_timer ();
    });

    $('.code-inputs-row input').on('focus', function (e) {
        $(this).val('');
    });

    $('.code-inputs-row input').on('input', function (e) {
        let $input = $(this);
        let index = $input.index();
        console.log(index);
        let value = e.target.value
        if (value.length > 1) {
            $input.val(value.slice(0, 1));
        }

        $('.code-inputs-row').find('input').eq(index + 1).focus();
    });

    $('.code-inputs-row input').on('input', function (e) {
        let has_val = 0;

        $('.code-inputs-row').find('input').each(function () {
            if ($(this).val()) {
                has_val = 1;
            } else {
                has_val = 0;
                return false;
            }
        });

        if (has_val) {
            has_val = 0;
            $('.code-form').addClass('validated');
       }

    });


});

</script>