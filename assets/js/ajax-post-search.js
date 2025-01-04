jQuery(document).ready(function( $ ) {

    function send_ajax( filter = {} ) {
        $.ajax({
            url: AjaxPostSearch.ajax_url,
            method: "POST",
            data: {
                action: "abfp_ajax_post_search",
                nonce: AjaxPostSearch.nonce,
                cat_id: filter.cat_id ? filter.cat_id : 0,
            }
        }).done(function( response ) {
            // console.log(response);
            let html = "<ul>";
            response.forEach(function(item) {
                html += '<li>' + item.post_title + '</li>';
            });

            html += "</ul>";

            $('#ajax-post-search-items').html( html );
        });
    }

    // Get all posts.
    send_ajax();

    $('#post-search-category-select').on( "change", function() {
        send_ajax( {
            cat_id: $(this).val(),
        } );
    } );
});
