(function($) {
    const AuthorsField = {
        maxAuthorsCount: mpaJsVars.maxAuthors,
        fieldsWrapper: null,
        listSelector: '.mpa-post-authors-list',
        init() {
            if ( $("body").hasClass("post-php") ) {
                this.fieldsWrapper = $('#mpauthors_metabox');
            }

            this.removeItemsInit();
            this.showAvailableTools();
        },
        removeItemsInit() {
            let selector = $( this.listSelector, this.fieldsWrapper);
            let fieldObj = this;
            if (selector.length > 0) {
                selector.on("click", ".author-item-remove", function () {
                    let el = $(this);
                    if ( el.hasClass('disabled') ) {
                        return;
                    }
                    el.parent().remove();
                    fieldObj.showAvailableTools();
                });
            }
        },
        addItem($item) {
            if ( !this.fieldsWrapper ) {
                return;
            }

            let template = wp.template('post-authors-partial');
            if ( !template ) {
                return;
            }

            let html = template({
                title: $item.text(),
                value: $item.data('value')
            });

            $( this.listSelector, this.fieldsWrapper).append( html );
        },
        showAvailableTools() {
            if ( null == this.fieldsWrapper ) {
                return;
            }

            let authors_list = $( this.listSelector, this.fieldsWrapper),
                authors_list_items = $('.mpa-post-authors-list__item', this.fieldsWrapper);

            if ( authors_list.length ) {
                authors_list.sortable();
            }

            // check possibility to remove item from list
            if (authors_list_items.length > 1 ) {
                authors_list_items.children('.author-item-remove.disabled').removeClass('disabled');
            } else {
                authors_list_items.children('.author-item-remove').addClass('disabled');
            }

            // check possibility to add new items to the list
            if ( this.isMaxAuthorsAdded( authors_list_items.length ) ) {
                authors_list.addClass('disabled');
            } else {
                authors_list.removeClass('disabled');
            }
        },
        isMaxAuthorsAdded(count) {
            if ( this.maxAuthorsCount > 0 && this.maxAuthorsCount <= count ) {
                return true;
            }
            return false;
        },
        hideSearchResults() {
            $(".mpa-search-results").hide();
        },
    };

    $(document).ready( function() {

        AuthorsField.init();

        $(".post-authors-search-field").on('input propertychange', function() {
            let $this = $(this),
                ignored = [],
                $results_box = $this.parent().find('.mpa-search-results');

            if ( 2 > $this.val().length ) {
                return;
            }

            $( AuthorsField.listSelector + ">li>input").each(function(){
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
                        AuthorsField.hideSearchResults();
                    }
                },
                error: function (e) {
                    console.log(e);
                }
            });

        });

        $(document).on('click', '.mpa-search-results>p', function(e){

            AuthorsField.addItem( $(this) );
            AuthorsField.showAvailableTools();

            // clear up input field
            $(".post-authors-search-field").val('');
        });

        $(document).on('click', function(e){
            if (e.target.class != 'mpa-search-results') {
                AuthorsField.hideSearchResults();
            }
        });

        // quick edit post mode function
        if (typeof inlineEditPost !== 'undefined') {
            let wp_inline_edit_function = inlineEditPost.edit;

            inlineEditPost.edit = function( post_id ) {
                wp_inline_edit_function.apply( this, arguments );

                // get the post ID from the argument
                if ( typeof( post_id ) == 'object' ) { // if it is object, get the ID number
                    post_id = parseInt( this.getId( post_id ) );
                }

                if ( post_id > 0 ) {
                    // fill the authors field values
                    let specific_post_row = $( '#post-' + post_id ),
                        specific_post_edit_row = $( '#edit-' + post_id ),
                        authors_list_wrapper = $(AuthorsField.listSelector, specific_post_edit_row),
                        post_authors = $( '.column-mpa_authors .author_name', specific_post_row );

                    AuthorsField.fieldsWrapper = authors_list_wrapper.parent();

                    authors_list_wrapper.html('');
                    post_authors.each(function(){
                        AuthorsField.addItem( $(this) );
                    });

                    AuthorsField.showAvailableTools();
                }
            }
        }

    });

})(jQuery);