$(function() {
    var ajax = new Ajax();
    var facebook = new Facebook(ajax);

    var facebookDiv = $('#facebook-quick-publish');
    var twitterDiv  = $('twitter-quick-publish');

    facebookDiv.find('button').on('click', facebook.quickPublish);

    $('.facebook-video-picture').on('click', facebook.startVideo);

    $('.yop').on("loadstart", function () {
            var width = this.videoWidth;
            var height = this.videoHeight;
            console.log(width);
            console.log(height);

        });
});