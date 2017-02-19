$('#form_contact').submit(function(event) {
	var data = $('#form_contact').serialize();
    var recaptcha = grecaptcha.getResponse();
    $('#contactDiv').html('<div class="col-md-12"><i class="fa fa-refresh fa-spin fa-5x red"></i><span class="sr-only">Loading...</span></div>');
    $.ajax({
        url: "/contact",
        data: data+"&g-recaptcha-response="+recaptcha,
        type: "POST",
        success: function (data) {
            $('#contactDiv').html(data);
        }
    })
    event.preventDefault();
    
    $('body,html').animate({
        scrollTop : $('#contactDiv').offset().top - 100
    }, 500);
});