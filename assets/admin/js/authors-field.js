(function($) {

    const max_authors_count = mpaJsVars.maxAuthors;

    let template = false;

    $(document).ready( function() {

        if ( $(".mpa-post-authors-list").length ) {
            $(".mpa-post-authors-list").sortable();
        }

        template = wp.template('post-authors-partial');

        addAuthorsListItem();
        removeAuthorFromList();

        // only on the post edit page
        if ( $("body").hasClass("post-php") ) {
            show_available_tools($('.mpa-post-authors-list'));
        }

        // quick edit post mode function
        if (typeof inlineEditPost !== 'undefined') {
            let wp_inline_edit_function = inlineEditPost.edit;

            inlineEditPost.edit = function( post_id ) {

                wp_inline_edit_function.apply( this, arguments );

                // get the post ID from the argument
                var id = 0;
                if ( typeof( post_id ) == 'object' ) { // if it is object, get the ID number
                    id = parseInt( this.getId( post_id ) );
                }

                //if post id exists
                if ( id > 0 ) {

                    // fill the authors field values
                    let specific_post_row = $( '#post-' + id ),
                        specific_post_edit_row = $( '#edit-' + id ),
                        authors_list_wrapper = $(".mpa-post-authors-list", specific_post_edit_row),
                        post_authors = $( '.column-authors>.author_name', specific_post_row );

                    authors_list_wrapper.html('');

                    post_authors.each(function(){
                        add_author_item_to_list($(this), authors_list_wrapper);
                    });

                    show_available_tools( authors_list_wrapper.parent() );

                }
            }
        }

    });

    function addAuthorsListItem() {

        $("#post-authors-search-field").on('input propertychange', function() {

            let $this = $(this),
                ignored = [],
                $results_box = $('.mpa-search-results');

            if ( 2 > $this.val().length ) {
                return;
            }

            $(".mpa-post-authors-list>li>input").each(function(){
                ignored.push($(this).val());
            });

            $.ajax({
                url: mpaJsVars.searchUrl,
                type: "GET",
                dataType: "json",
                beforeSend: function ( xhr ) {
                    xhr.setRequestHeader( 'X-WP-Nonce', mpaJsVars.nonce );
                },
                data: {
                    search: $this.val(),
                    ignored: ignored
                },
                success: function(response) {
                    $results_box.html('');
                    if (response.data) {
                        $results_box.show();
                        $.each(response.data, function(ind, val){
                            $results_box.append('<p data-value="'+val.ID+'">'+val.display_name+'</p>');
                        });
                    } else {
                        hide_search_results();
                    }
                },
                error: function (e) {
                    console.log(e);
                }
            });

        });

        $(document).on('click', '.mpa-search-results>p', function(e){

            let authors_wrap = $('.mpa-post-authors-list');

            add_author_item_to_list($(this), $(".mpa-post-authors-list"));

            show_available_tools(authors_wrap);

            // clear up input field
            $(".post-authors-search__field").val('');
        });

        $(document).on('click', function(e){
            if (e.target.class != 'mpa-search-results') {
                hide_search_results();
            }
        });

    }

    function add_author_item_to_list($item, $parent ) {

        if ( !template ) {
            return;
        }

        let html     = template({
            title: $item.text(),
            value: $item.data('value')
        });

        $parent.append( html );
    }

    function hide_search_results() {
        $(".mpa-search-results").hide();
    }

    function show_available_tools( $wrap ) {

        let authors_list_items = $wrap.find('.mpa-post-authors-list__item');

        // check possibility to remove item from list
        if (authors_list_items.length > 1 ) {
            authors_list_items.children('.author-item-remove').removeClass('disabled');
        } else {
            authors_list_items.children('.author-item-remove').addClass('disabled');
        }

        // check possibility to add new items to the list
        if ( is_max_authors_count( authors_list_items.length ) ) {
            $wrap.addClass('disabled');
        } else {
            $wrap.removeClass('disabled');
        }

    }

    function removeAuthorFromList() {
        let selector = $(".mpa-post-authors-list");

        if (selector.length > 0) {
            selector.on("click", ".author-item-remove", function () {
                let el = $(this);
                if ( el.hasClass('disabled') ) {
                    return;
                }

                let items_list_wrapper = el.closest(".mpa-post-authors-list");
                el.parent().remove();
                show_available_tools(items_list_wrapper);
            });
        }
    }

    function is_max_authors_count(count) {
        if ( max_authors_count > 0 && max_authors_count <= count ) {
            return true;
        }
        return false;
    }

})(jQuery);