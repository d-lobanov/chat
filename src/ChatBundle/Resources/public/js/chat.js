var ws = new function (){
    var conn = new ChatSocket(global.socketUrl);
    conn.onEventDelete = function(response) {
        var id = response.body.messageId;
        var messageBlock = $( '#msg_' + id );
        messageBlock.addClass('msg-delete');
        messageBlock.text('Message delete');
    };

    conn.onEventMessage = function (response) {
        var message = response.body.messTemplate;
        if(global.isModerator == true) {
            message = response.body.messModeratorTemplate;
        }

        var roomId = response.head.roomId;
        if (roomId == global.currRoom) {
            $('.msg-wrap').append(message);
            $("#dialog-chat").animate({ scrollTop: $("#dialog-chat")[0].scrollHeight}, 500);
        } else {
            var selector = '#room_' + roomId + ' .label';
            $(selector).css('display', '');
        }
    };
    return conn;
};

$('form[name=publish]').submit(function() {
    var message = this.message.value;
    if(message.replace(/\s/g, '').length > 0){
        window.ws.sendMessage(global.currRoom , message);
    }
    this.message.value = '';
    return false;
});

$('form[name=publish] textarea').keypress(function(event) {
    if (event.which == 13) {
        event.preventDefault();
        $('form[name=publish]').submit();
    }
});

$(document).delegate('.btn-trash', 'click', function() {
    var messageBlock = this.closest('.msg');
    var messageId = messageBlock.getAttribute('id').replace('msg_', '');
    window.ws.deleteMessage(global.currRoom, messageId);
});

$(document).ready(function() {
    $("#dialog-chat").animate({ scrollTop: $("#dialog-chat")[0].scrollHeight}, 0);
});