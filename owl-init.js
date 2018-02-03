jQuery(function($) {
    $('.owl-carousel').owlCarousel({
        loop:false,
        dots:false,
        responsive:{
            0:{
                items:1
            }
        },
        navText: [
            "<i class='glyphicon glyphicon-menu-left'></i>",
            "<i class='glyphicon glyphicon-menu-right'></i>"
        ]
    })
});