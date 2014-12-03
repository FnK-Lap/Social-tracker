var Instagram = function (ajax){


        function success() {

            location.reload();
        }

        function loadMore() {
            $('#load-more').hide();
            $('#loader').css('display', 'inline-block');

            ajax.send('/instagram/feed/' + maxId, {}, true, 'GET', successLoadMore);
        }

        function successLoadMore(data) {
            maxId = data.pagination.next_max_id;
            var posts = data.data;
            for (var i = 0; i < posts.length; i++) {
                var html = "<li><figure>";
                if (posts[i].type == 'video') {
                    html += "<i class='fa fa-video-camera fa-border fa-lg'></i>";
                };
                if (posts[i].user_has_liked == true) {
                    html += "<i class='fa fa-heart fa-lg'></i>";
                };
                html += "<a href='/instagram/" + posts[i].id + "'><img src=" + posts[i].images.low_resolution.url + "></a><figcaption><h5>" + posts[i].user.username + "</h5>";
                if (posts[i].caption != null) {
                    html += "<span>" + posts[i].caption.text + "</span>";
                };
                html += "</figcaption></figure></li>";

                $('.load-more').before(html);
            };
            
            $('#loader').hide();
            $('#load-more').show();
        }

        function likeDislike() {
            if ($(this).hasClass('liked')) {
                ajax.send('/instagram/' + mediaId + "/dislike", {}, true, 'GET', function(){location.reload()});
            }else{
                ajax.send('/instagram/' + mediaId + "/like", {}, true, 'GET', function(){location.reload()});
            };
        }


    return{
        success: success,
        loadMore: loadMore,
        successLoadMore: successLoadMore,
        likeDislike: likeDislike
    }
}