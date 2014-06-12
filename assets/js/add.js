$(function() {
    $('input:submit').button();
    $('input:radio').button();
    $('input:text').button().css({
        'background' : '#fff',
        'text-align':'left',
        'outline':'none',
        'cursor':'text',
        'padding-left':'3px',
        'padding-right':'3px'
    });
});
