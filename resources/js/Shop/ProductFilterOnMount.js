export const productFilterOnMount = () => {
    $('.products-filter-item__arrow').click(function () {
        $(this).toggleClass('active')
        $('.products-filter__body').slideToggle();
    })

    $('.acc-history__arrow').click(function () {
        $(this).toggleClass('active')
        $(this).parent().parent().siblings('.acc-history__item-body').slideToggle();
    })
    $('.form-section__item-ttl').click(function () {
        $(this).toggleClass('active')
        $(this).siblings('.form-section__item-content').slideToggle();
    })

/*    $('.products-filter__arrow').click(function () {
        $(this).toggleClass('active')
        $(this).parent().siblings('.products-filter__content').slideToggle();
    })*/
}
