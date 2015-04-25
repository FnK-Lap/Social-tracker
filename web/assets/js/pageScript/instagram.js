$(function() {
    var ajax = new Ajax();
    var instagram = new Instagram(ajax);

    $('#load-more').on('click', instagram.loadMore);
    $('#like-dislike').on('click', instagram.likeDislike);

    $('.instagram-media-refresh').click(instagram.refreshMedia);

    $('.instagram-media-img').each(function(index, el) {
        $(el).error(function() {
            console.log(delete_publication)
            $(el).attr('src', '/assets/img/delete_publication.jpg');
            $(el).parent('a').attr('href', '#');
            ajax.send('/instagram/' + $(el).data('id') + '/delete', {}, true, 'DELETE');
        });
    });

});