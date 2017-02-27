
$("document").ready(function() {
    $(".cp").keyup(function() {
        if ($(this).val().length === 5) {
            $.ajax({
                type: 'get',
                url: 'http://localhost/symfony/web/app_dev.php/ville/' + $(this).val(),
                beforeSend: function() {
                    if ($(".loading").length == 0) {
                        $("form .ville").parent().append('<div class="loading"></div>');
                    }
                },
                success: function(data) {
                    $(".ville").val(data.ville);
                    $(".loading").remove();
                }
            });
        } else {
            $(".ville").val('');
        }
    });
});





