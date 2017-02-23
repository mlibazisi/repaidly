$(document).ready(function() {

    $('.search').click( function() {

        var button = $(this);
        var btn_txt = button.html();

        var form = $('#search-form');
        var loans = $('#loans');

        var not_found = $('.loans-not-found');

        var paginator = $('#paginate-div');
        var page = $('#page');
        var next_page = parseInt(page.val(), 10) + 1;
        page.val( next_page );

        $.ajax({
            type: form.attr( 'method' ),
            url: form.attr( 'action' ),
            data: form.serialize(),
            dataType: 'json',
            beforeSend: function () {
                button.prop("disabled",true);
                button.html("Loading...");
                if ( !not_found.hasClass('hidden') ) {
                    not_found.addClass('hidden')
                }
            },
            success: function (response) {
                if (loans.hasClass('hidden')) {
                    loans.removeClass('hidden');
                }

                if ( !response.data.loans.length ) {
                    not_found.removeClass('hidden')
                    loans.empty()
                } else {
                    loans.append(response.data.loans)
                }

                if ( response.data.paging.page >= response.data.paging.pages ) {
                    if (!paginator.hasClass('hidden')) {
                        paginator.addClass('hidden');
                    }
                } else {
                    if (paginator.hasClass('hidden')) {
                        paginator.removeClass('hidden');
                    }
                    page.val(response.data.paging.page);
                }
            },
            error: function ( jqXHR, exception ) {
                console.log( jqXHR );
                console.log( exception );
            },
            complete: function ( jqXHR, textStatus ) {
                button.html(btn_txt);
                button.prop("disabled",false);
            }
        });

    });

});