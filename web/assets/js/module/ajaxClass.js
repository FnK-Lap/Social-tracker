var Ajax = function (){
    // send method of ajax
    function send(url,myData,async,method,success,error){
        error = error || {};
        $.ajax({
            url      : url,
            data     : myData,
            async    : async,
            type     : method,
            dataType : 'json',
            error    : error,
            success  : function(data, textStatus, xhr){
                success(data, textStatus, xhr);
            }
        });
    }

    return{
        send: send,
    }
}