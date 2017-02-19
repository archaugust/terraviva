const loader = '<i class="fa fa-refresh fa-spin fa-5x"></i><span class="sr-only">Loading...</span>';
if( $("#xs-check").is(":visible") )
    $("#collapseTags").removeClass("in");

function setSort(sort) {
    $('#sort').val(sort);
    $('#sort_btn').val(sort);
    $('#form').trigger('submit');
}

function setPage(page) {
    $('#pagenum').val(page);
    $('#itemDiv').fadeOut('normal');
    $('#categoryDiv').fadeIn('normal');
    $("body,html").animate({scrollTop:0},800);
    $('#form').trigger('submit');
}

function setItem(alias) {
    $('#categoryDiv').fadeOut('normal');
    $("body,html").animate({scrollTop:0},800);

    $.ajax({
        beforeSend: function() {
            $('#itemDiv').html(loader).fadeIn();
        },
        url: "/ajax-shop-front-item",
        data: 'item='+ alias,
        type: "POST",
        success: function (data) {
            $('#itemDiv').hide().html(data).fadeIn();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Something went wrong. Please try again.')
        }
    })

    $.ajax({
        url: "/ajax-shop-front-item-related",
        data: 'item='+ alias,
        type: "POST",
        success: function (data) {
            $('#bottomDiv').hide().html(data).fadeIn();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Something went wrong. Please try again.')
        }
    })

    $('#plantFinderDiv').fadeIn('normal');
}

function showList() {
    $('#itemDiv').fadeOut('normal');
    $('#plantFinderDiv').fadeOut('normal');
    $('#categoryDiv').fadeIn('normal');
    $('#breadcrumb_item').fadeOut();
}

$('#collapseTags :checkbox').on('change', function() {
    $('#breadcrumb_item').fadeOut();
    $('#itemDiv').fadeOut('normal');
    $('#categoryDiv').fadeIn('normal');
    if (this.checked) {
        $('#tagsDiv a').remove(":contains('All')");
        var tag = $('<a class="btn">'+ this.value +' <i class="fa fa-times-circle"></i> </a>').hide();
        $('#tagsDiv').append(tag);
        tag.show('normal')
        $('#tags').val($('#tags').val()+1)
    }
    else {
        $('#tagsDiv a').fadeOut().remove(":contains('"+ $(this).val() +"')").fadeIn();
        $('#tags').val($('#tags').val()-1)
        if ($('#tagsDiv').html() == '') {
            tag = $('<a class="btn">All <i class="fa fa-times-circle"></i> </a>').hide();
            $('#tagsDiv').append(tag);
            tag.show('normal');
        }
    }

    $('#tagsDiv a').click(function () {
        var id = $(this).text();
        $('#'+ id).prop('checked',false);

        $('#tagsDiv a').fadeOut().remove(":contains('"+ id +"')").fadeIn();
        $('#tags').val($('#tags').val()-1)
        if ($('#tagsDiv').html() == '') {
            tag = $('<a class="btn">All <i class="fa fa-times-circle"></i> </a>').hide();
            $('#tagsDiv').append(tag);
            tag.show('normal');
        }
        $('#pagenum').val(1);
        $('#form').trigger('submit');
    });

    $('#pagenum').val(1);
    $('#form').trigger('submit');
});

$('#form').submit(function(event) {
    var data = $(this).serializeArray();
    $.ajax({
        beforeSend: function() {
            $('#contentDiv').hide('normal').html(loader).fadeIn('normal');
        },
        url: "/ajax-shop-front-category",
        data: data,
        type: "POST",
        success: function (data) {
            $('#contentDiv').hide().html(data).fadeIn('normal');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Something went wrong. Please try again.')
        }
    })

    event.preventDefault();
});