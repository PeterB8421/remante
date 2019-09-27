$(function () {
    $.nette.init();
    $("#frm-filtrForm").on("submit", function (event) {
        event.preventDefault();
        $.nette.ajax({
            url: window.location.href + "?" + $(this).serialize(),
            success: function (payload) {
                console.log(payload.produkty);
            },
        });
    })
});
