wideMenu = {
    create: function () {
        let mainBlock = $('.js_wide_menu');

        if (mainBlock.length) {
            let visibleElements = mainBlock.find('.visible-elements'),
                menuElements = mainBlock.find('.visible-elements span'),
                menuExtensionBlock,
                i = 0;

            menuElements.each(function () {

                let thisElement = $(this);

                if (i == 4) {

                    visibleElements.after(`
                    <div class="hidden-elements">
                        <span class="menu-extension-button"><i class="fas fa-bars"></i></span>
                        <div class="menu-extension-block"></div>
                    </div>
                `);

                    menuExtensionBlock = mainBlock.find('.menu-extension-block');
                }

                if (i >= 4) {
                    menuExtensionBlock.append(`<span>${thisElement.html()}</span>`);
                    thisElement.remove();
                }

                i++;
            });
        }
    },
}

$(() => {
    wideMenu.create();

    let sliderBlock = $('.slider');

    if (sliderBlock.length) {
        $('.slider').slick({
            infinite: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 5000,
            prevArrow: $('.slider-block .nav-left'),
            nextArrow: $('.slider-block .nav-right'),
        });
    }
});

$(document).ready(function() {
    $('.js_buy').click(function() {
        var productId = $(this).data('id');
        window.location.href = 'product_details.php?id=' + productId;
    });
});
