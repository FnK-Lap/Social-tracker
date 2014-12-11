$(function() {
    var ajax = new Ajax();
    var facebook = new Facebook(ajax);

    var facebookDiv = $('#facebook-quick-publish');
    var twitterDiv  = $('twitter-quick-publish');

    facebookDiv.find('button').on('click', facebook.quickPublish);

});