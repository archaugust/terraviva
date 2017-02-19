(function($) {
    "use strict";

    $('.navbar-collapse ul li a').click(function(){
        $('.navbar-toggle:visible').click();
    });

    $('#mainNav').affix({
        offset: {
            top: 135
        }
    });

    $('[data-toggle="tooltip"]').tooltip();

    jQuery.fn.verticalAlign = function ()
	{
	    return this
	            .css("margin-top",($(this).parent().height() - $(this).height())/2 + 'px' )
	};

	$('.mask-text').verticalAlign();

	$('.carousel').carousel();

    $('#form_subscribe').submit(function(event) {
    	var data = $('#form_subscribe').serialize();
    	$('#subscribeDiv').hide();
        $('#subscribeLoader').html('<i class="fa fa-refresh fa-spin fa-5x red"></i><span class="sr-only">Loading...</span>');
        $.ajax({
            url: "/subscribe",
            data: data,
            type: "POST",
            success: function (data) {
                $('#subscribeLoader').html(data);
            }
        })
        event.preventDefault();
    });
})(jQuery);

$(window).scroll(function() {
    if ($(this).scrollTop() >= 50) {
        $('#return-to-top').fadeIn(200);
    } else {
        $('#return-to-top').fadeOut(200);
    }
});

$('#return-to-top').click(function() {
    $('body,html').animate({
        scrollTop : 0
    }, 500);
});