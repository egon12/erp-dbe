$(function() {
    $('input#field-age').change( function () {
        var age_now = $(this).val();
        if (age_now != '') {
            var now = new Date();
            var born_year = now.getFullYear() - age_now;
            $('input#field-birth_date').val('1/1/' +born_year);
        }
    });
});
