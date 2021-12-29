/*global common_params:false, Instafeed:false*/
jQuery(document).ready(function($) {

    /* #############################################################
    # MASONRY
    ############################################################# */
    if ($('#msnry').length){
        var $container = $('#msnry');
        $container.imagesLoaded( function() {
            $container.masonry({
                itemSelector: '.msnry-panel',
                columnWidth: function( containerWidth ) {
                    return containerWidth / 12;
                }
                //isFitWidth: true,
                //isRTL: true
            });
        });
    }

    /* #############################################################
    # TOOLTIP
    ############################################################# */
    if ($('[data-toggle=tooltip]').length) {
        $('[data-toggle=tooltip]').tooltip({
            delay: { show: 300, hide: 0 }
        });
    }

    /* #############################################################
    # CARROUSEL NEWS
    ############################################################# */
    if ($('#news-feed').length){
        $('#news-feed').owlCarousel({
            itemsCustom : [
                [0, 2],
                [450, 2],
                [600, 2],
                [700, 3],
                [1000, 3],
                [1200, 4],
                [1400, 4],
                [1600, 4]
            ],
            navigation : false,
            autoPlay: 3000
        });
    }

    /* #############################################################
    # NAVIGATION MENU
    ############################################################# */
    $('#cmdArtigosMenu').click(function (e) {
        e.preventDefault();
        navigationMenu('#cmdArtigosMenu');
    });
    $('#cmdInstitucionalMenu').click(function (e) {
        e.preventDefault();
        navigationMenu('#cmdInstitucionalMenu');
    });

    function navigationMenu(origin){
        $(origin).parent().addClass('active');
        if( origin === '#cmdArtigosMenu' ) {
            if ($( '#navigationInstitucional' ).is(':visible')) {
                $( '#navigationInstitucional' ).slideToggle( 'fast' );
                $( '#cmdInstitucionalMenu' ).parent().removeClass( 'active' );
            }
            if ($( '#navigationArtigos' ).is(':visible')) {
                $( '#cmdArtigosMenu' ).parent().removeClass( 'active' );
            }
            $( '#navigationArtigos' ).slideToggle( 'fast' );
            return true;
        }
        if( origin === '#cmdInstitucionalMenu' ) {
            if ($( '#navigationArtigos' ).is(':visible')) {
                $( '#navigationArtigos' ).slideToggle( 'fast' );
                $( '#cmdArtigosMenu' ).parent().removeClass( 'active' );
            }
            if ($( '#navigationInstitucional' ).is(':visible')) {
                $( '#cmdInstitucionalMenu' ).parent().removeClass( 'active' );
            }
            $( '#navigationInstitucional' ).slideToggle( 'fast' );
            return true;
        }
    }

    /* #############################################################
    # NAVIGATION COLLAPSE
    ############################################################# */
    $('.collapse').on('show.bs.collapse', function (e) {
        $( e.target ).parent().addClass( 'active' );
    });
    $('.collapse').on('hide.bs.collapse', function (e) {
        $( e.target ).parent().removeClass( 'active' );
    });

    /* #############################################################
    # NAVIGATION FUNCTIONS
    ############################################################# */
    $('#cmdGeneralMenu').click(function (e) {
        e.preventDefault();
        navigationFunctions('#cmdGeneralMenu');
    });
    $('#cmdUserArea').click(function (e) {
        e.preventDefault();
        navigationFunctions('#cmdUserArea');
    });
    $('#cmdSearh').click(function (e) {
        e.preventDefault();
        navigationFunctions('#cmdSerch');
    });

    function navigationFunctions(origin){
        if( origin === '#cmdGeneralMenu' ) {
            $( '#cmdUserArea' ).removeClass( 'active' );
            $( '#userArea' ).removeClass( 'aside-panel-open' );
            $( '#cmdSerch' ).removeClass( 'active' );
            $( '#search' ).removeClass( 'search-open' );

            $( '#cmdGeneralMenu' ).toggleClass( 'active' );
            $( '#generalMenu' ).toggleClass( 'aside-panel-open' );
            return true;
        }
        if( origin === '#cmdUserArea' ) {
            $( '#cmdGeneralMenu' ).removeClass( 'active' );
            $( '#generalMenu' ).removeClass( 'aside-panel-open' );
            $( '#cmdSerch' ).removeClass( 'active' );
            $( '#search' ).removeClass( 'search-open' );

            $( '#cmdUserArea' ).toggleClass( 'active' );
            $( '#userArea' ).toggleClass( 'aside-panel-open' );
            return true;
        }
        if( origin === '#cmdSerch' ) {
            $( '#cmdUserArea' ).removeClass( 'active' );
            $( '#userArea' ).removeClass( 'aside-panel-open' );
            $( '#cmdGeneralMenu' ).removeClass( 'active' );
            $( '#generalMenu' ).removeClass( 'aside-panel-open' );

            $( '#cmdSerch' ).toggleClass( 'active' );
            $( '#search' ).toggleClass( 'search-open' );
            return true;
        }
    }

    function hideNavigationFunctions( origin ){
        if( typeof origin === 'undefined' ) {
            $( '#cmdUserArea' ).removeClass( 'active' );
            $( '#userArea' ).removeClass( 'aside-panel-open' );

            $( '#cmdGeneralMenu' ).removeClass( 'active' );
            $( '#generalMenu' ).removeClass( 'aside-panel-open' );

            $( '#cmdSerch' ).removeClass( 'active' );
            $( '#search' ).removeClass( 'search-open' );
            return true;
        }
    }

    /* #############################################################
    # INFINITE SCROLL
    ############################################################# */
    var paged = 2;

    $( '#load-more' ).click(function() {
        var template = $(this).attr('data-template');
        var post_type = $(this).attr('data-post-type');
        var posts_per_page = $(this).attr('data-posts-per-page');
        var data_max_page = $(this).attr('data-max-page');

        if (paged > data_max_page){
            return false;
        }else{
            loadArticle(template, post_type, posts_per_page, paged);
        }
        paged++;
    });

    function loadArticle(template, post_type, posts_per_page, paged) {
        $( '#load-more' ).button('loading');
        $.ajax({
            url: common_params.site_url + '/wp-admin/admin-ajax.php',
            type:'POST',
            data: 'action=infinite_scroll&template='+ template + '&post_type='+ post_type + '&posts_per_page=' + posts_per_page +'&paged='+ paged,
            success: function(html){
                $('#article-list').append(html);
                $( '#load-more' ).button('reset');
            }
        });
        return false;
    }

    /* #############################################################
    # INSTAGRAM PLUGIN
    ############################################################# */
    if ($('#instagram').length){
        var feed = new Instafeed({
            get: 'tagged',
            tagName: 'demolay',
            clientId: 'a8e1384f0eb842f6a8d2f1314f02a0ee',
            limit:20,
            sortBy:'most-recent',
            template:'<a href="{{link}}" target="_blank"><img data-toggle="tooltip_instafeed" title="{{caption}}" src="{{image}}" class="instagram-image" /></a>',
            after:function() {
                $('[data-toggle=tooltip_instafeed]').tooltip();
            }
        });
        feed.run();
    }

    /* #############################################################
    # WINDOWS SCROLL
    ############################################################# */
    $(window).scroll(function(){
        //console.log($(this).scrollTop());
        if ($(this).scrollTop() >= 65) {
            $( '#generalMenu' ).css( 'top', '0');
            $( '#userArea' ).css( 'top', '0');
            hideNavigationFunctions();
        }else{
            $( '#generalMenu' ).css( 'top', 65 - $(this).scrollTop());
            $( '#userArea' ).css( 'top', 65 - $(this).scrollTop());
        }
    });

    /* #############################################################
    # SIDEBAR MÓVEL
    ############################################################# */
    if ($('#affix-sidebar').length){
        $('#affix-sidebar').affix({
            offset: {
                top: function () {
                    var headerHeight = $('#navigation').outerHeight(true) +
                                    $('#image-page').outerHeight(true) +
                                    $('#trending').outerHeight(true);
                    return headerHeight;
                    //return (this.bottom = $clear('.footer').outerHeight(true))
                    //return 260;
                },
                bottom: function () {
                    //return (this.bottom = $('.footer').outerHeight(true))
                    return 420; //420
                }
            }
        });
    }
});
