$(function() {
    var ajax = new Ajax();
    var instagram = new Instagram(ajax);

    $('#load-more').on('click', instagram.loadMore);
    $('#like-dislike').on('click', instagram.likeDislike);

});