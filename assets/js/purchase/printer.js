$(function () {
    $('*').css('visibility', 'hidden');
    $('aside').hide();
    $('#po').css ('visibility', 'visible');
    $('#po *').css ('visibility', 'visible');
    window.print();
    history.back();
});
