function add(e) {
    e.preventDefault();

    var inDiv = $('.poline:last').html();
    var number = parseInt (   inDiv.match(/code\[(\d)/m)[1]  );
    number += 1;

    inDiv = inDiv.replace(/code\[(\d)/m, 'code['+number);
    inDiv = inDiv.replace(/quantity\[(\d)/m, 'quantity['+number);
    inDiv = inDiv.replace(/price\[(\d)/m, 'price['+number);
    $('.poline:last').after('<div class="poline">'+inDiv+'</div>');
    $('.delButton').prop('disabled', false);
}

function del(object) {

    $(object).parent().remove();
    if ($('.poline').length < 2) {
        $('.delButton').prop('disabled', true);
    }
    return false;
}


$('.proAddButton').click(function () {
    console.log($(this).parent());
    $('.poline:last').after( $(this).parent().html() );

});

//on start
$(function () {
    $('.delButton').prop('disabled', true);
    //$('.delButton').on('click','button',del);
    $('.addButton').click(add);
});
