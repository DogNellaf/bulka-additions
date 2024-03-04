jQuery.noConflict();
(function($){
    $(function(){

        if ($('.swiper').length) {

            let $slider = $('.swiper')[0];

            const swiper = new Swiper( $slider, {
                // Default parameters
                spaceBetween: 0,
                slidesPerView: "auto",
                // simulateTouch: false,
                shortSwipes: true,
                speed: 700,
                longSwipes: true,
                mousewheel: true,
                breakpoints: {
                    960: {
                        spaceBetween: 22,
                    },
                }

            });

            function page_nav() {
                $('.nav-container a').each(function () {
                    let $this = $(this);
                    let id = $(this).attr('href');
                    let $target = $('.menu-container').find(id);
                    let index = $target.index();
                    let target_pos = $target.offset().top - 200;
                    let target_h = $target.outerHeight();
                    let scroll_pos = window.pageYOffset;

                    if (target_pos < scroll_pos && (target_pos + target_h) > scroll_pos && !$this.hasClass('active')) {
                        $('.nav-container a').removeClass('active');
                        $this.addClass('active');
                        swiper.slideTo(index);

                    }

                });
            }

            window.addEventListener("scroll", function(){
                page_nav();
            });

            $('.nav-container a').click(function (event) {
                event.preventDefault();

                let id = $(this).attr('href');

                let $target = $('.menu-container').find(id);
                let target_pos = $target.offset().top - 150;


                $("html, body").stop().animate({scrollTop: target_pos}, 500, 'swing', function() {
                });

            });


        }





    });
})(jQuery);






















