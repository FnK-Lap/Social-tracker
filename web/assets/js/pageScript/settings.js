$(function() {
    var ajax = new Ajax();
    var instagram = new Instagram(ajax);

    $('.open-active-modal').on('click', function (event) {
        $('#modal-active').modal();
        var button = $(event.target); // Button that triggered the modal
        var social  = button.data('social'); // Extract info from data-* attributes
        var data = {'social': social};
        var action = button.data('action');

        var modal = $('#modal-active');
        var submit = modal.find('.modal-footer .submit');

        if (action == 'activate') {
            modal.find('.modal-title').text('Activer ' + social)
            modal.find('.modal-body p').text('Voulez-vous activer ' + social + '?');
            submit.off('click').on('click', function (e) {
                ajax.send('/social/add',data,true,'POST',function(){instagram.success()},function(){instagram.success()});
                modal.modal('hide');
            });
        }else if (action == 'desactivate'){
            modal.find('.modal-title').text('Désactiver ' + social)
            modal.find('.modal-body p').text('Voulez-vous désactiver ' + social + '?');
            submit.off('click').on('click', function (e) {
                ajax.send('/social/remove',data,true,'POST',function(){instagram.success()},function(){instagram.success()});
                modal.modal('hide');
            });
        };    
    });

    // Toggle label Connected/Disconnected
    // Hover
    $('.disconnect').hover(
        function() {
            $(this).removeClass('label-default');
            $(this).addClass('label-success');
            $(this).children().html('Se Connecter');
        },
        function() {
            $(this).removeClass('label-success');
            $(this).addClass('label-default');
            $(this).children().html('Déconnecté');
        }
    );
});