$(function () {
    $('.content').on ('click', 'a', function (e) {
        e.preventDefault();
        $('.content .wrapper').load( $(this).attr('href'), '');
    });

    $('.content').on ('submit', 'form', function (e) {
        e.preventDefault();
        $('.content .wrapper').load( $(this).attr('action'), $(this).serializeArray() );
    });
});
