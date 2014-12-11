var Facebook = function (ajax){

    function quickPublish() {
        var message = $('#facebook-quick-publish').children('input').val();
        $('#facebook-quick-publish').children('input').val('');
        $('#facebook-quick-publish').children('input').attr('placeholder', 'Envoie ...');
        ajax.send('/facebook/publish', {message: message}, true, 'POST', quickPublishSuccess);
    }

    function quickPublishSuccess(data) {
        console.log(data);
        if(typeof(data.message) == "undefined" || data.message === null) {
            $('#facebook-quick-publish').children('input').attr('placeholder', 'Statut publié');
        } else {
            $('#facebook-quick-publish').children('input').attr('placeholder', data.message);
        }
    }


    return{
        quickPublish: quickPublish,
        quickPublishSuccess: quickPublishSuccess,
    }
}