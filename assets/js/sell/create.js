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

//on start
$(function () {
    $('.delButton').prop('disabled', true);
    //$('.delButton').on('click','button',del);
    $('.addButton').click(add);

    $.getScript("/assets/js/jquery-ui.min.js", function () {

        $('#customer_id').autocomplete ({ 
            source: function ( request,response ) {
                $.getJSON(
                    $('#customer_id').data('source'),
                    request,
                    response
                    )},
            delay: 0,
            minLength: 1
        });

    });
});
