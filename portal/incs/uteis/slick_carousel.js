$('.slick-carousel').slick();
$('.news .items').slick({
    infinite: true,
    prevArrow: '<button class="dna-prev"><i class="fa fa-angle-left"></i></button>',
    nextArrow: '<button class="dna-next"><i class="fa fa-angle-right"></i></button>',
    slidesToShow: 3,
    slidesToScroll: 1,
    arrows: true,
    dots: false,
    autoplay: true,
    autoplaySpeed: 3000,
    responsive: [
        {
            breakpoint: 1024,
            settings: {
            slidesToShow: 3,
            slidesToScroll: 1
            }
        },
        {
            breakpoint: 768,
            settings: {
            slidesToShow: 1,
            slidesToScroll: 1
            }
        }
    ]
    });