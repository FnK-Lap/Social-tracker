var Facebook = function (ajax){

    function quickPublish() {
        var message = $('#facebook-quick-publish').children('input').val();
        $('#facebook-quick-publish').children('input').val('');
        $('#facebook-quick-publish').children('input').attr('placeholder', 'Envoi ...');
        ajax.send('/facebook/publish', {message: message}, true, 'POST', quickPublishSuccess, function(){$('#facebook-quick-publish').children('input').attr('placeholder', 'Une erreur est survenue :(');});
    }

    function quickPublishSuccess(data) {
        if(typeof(data.message) == "undefined" || data.message === null) {
            $('#facebook-quick-publish').children('input').attr('placeholder', 'Statut publié');
        } else {
            $('#facebook-quick-publish').children('input').attr('placeholder', data.message);
        }
    }

    function startVideo() {
        var videoSrc   = $(this).data('video');
        var pictureSrc = $(this).attr('src');
        var regExWidthAndHeight = /rl=([0-9]+)&vabr=([0-9]+)/;

        var widthHeight = regExWidthAndHeight.exec(videoSrc);
        if (widthHeight !== null) {
            $(this).replaceWith("<embed src='" + videoSrc + "' width='"+widthHeight[1]+"' height='"+widthHeight[1]+"' >");
        }else {
            $(this).replaceWith("<embed src='" + videoSrc + "'>");
        };
        
    }


    return{
               quickPublish:   quickPublish,
        quickPublishSuccess:   quickPublishSuccess,
                 startVideo:   startVideo
    }
}