/*global FB:false*/
jQuery(document).ready(function($) {
  $('#like_facebook').click(function (e) {
    e.preventDefault();

    FB.getLoginStatus(function(response) {
      if (response.status === 'connected') {
        FB.api(
          '/me/permissions',
          function (response) {
            if (response && !response.error) {

            }
          }
        );
        FB.api('/me/feed', 'post', {message: 'Hello, world!'});
      }
      else {
        FB.login(function(){}, {scope: 'publish_actions'});
      }
    });
  });

  $('#share_facebook').click(function (e) {
    e.preventDefault();
    FB.ui(
      {
        method: 'share',
        href: $(this).attr('href')
      }
    );
  });

  $('#share_twitter').click(function() {
    var width  = 575,
      height = 400,
      left   = ($(window).width()  - width)  / 2,
      top    = ($(window).height() - height) / 2,
      url    = this.href,
      opts   = 'status=1' +
        ',width='  + width  +
        ',height=' + height +
        ',top='    + top    +
        ',left='   + left;

    window.open(url, 'twitter', opts);

    return false;
  });

  $('#share_plus').click(function (e) {
    e.preventDefault();
    window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=450');
    return false;
  });
});
