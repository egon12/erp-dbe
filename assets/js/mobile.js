/* Javascript Document */
$.extend( $.fn.dataTable.defaults, {
    "sDom": '<"tablePars"<"desktopOnly"l>f>rt<"tableFooter"<"desktopOnly"i>p>',
    "sPaginationType":"full_numbers",
    "bAutoWidth":false
});

$(function () {
    $("a.mobileMenu").click(function () {
        $(".leftNav").toggle("slow");
    });
});
