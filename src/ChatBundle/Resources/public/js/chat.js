var socketUrl = "ws://chat.dev/websocket";

var ChatSocket = function (socketUrl) {

    var connection = new WebSocket(socketUrl);

    connection.onopen = function() {
        console.log('Connect.');
    };

    connection.onmessage = function(event) {
        var response = JSON.parse(event.data);
        switch (response.head.event){
            case 'message':
                this.onEventMessage(response);
                break;
            case 'delete':
                this.onEventDelete(response);
                break;
            default:
                console.log(response.head.event);
        }
    };

    connection.onerror = function(error) {
        console.log('Error: ' + error.message);
    };

    connection.onclose = function(event) {
        alert('Connection close');
        console.log('Code: ' + event.code + ' reason: ' + event.reason);
    };

    connection.onEventDelete = function(response) {
        var id = response.body.messageId;
        var messageBlock = $( '#msg_' + id );
        messageBlock.addClass('msg-delete');
        messageBlock.text('Message delete');
    };

    connection.onEventMessage = function(response) {
        var message = response.body.messTemplate;
        var roomId = response.head.roomId;
        if (roomId == currRoom) {
            $('.msg-wrap').append(message);
            $("#dialog-chat").animate({ scrollTop: $("#dialog-chat")[0].scrollHeight}, 500);
        } else {
            var selector = '#room_' + roomId + ' svg';
            $(selector).css('display', '');
        }
    };

    connection.sendObject = function(obj) {
        data = JSON.stringify(obj);
        this.send(data);
    };

    connection.sendMessage = function(roomId, text) {
        obj = {event: 'message', info: {roomId: roomId, text: text}};
        this.sendObject(obj);
    };

    connection.deleteMessage = function(roomId, messageId) {
        obj = {event: 'delete', info: {roomId: roomId, messageId: messageId}};
        this.sendObject(obj);
    };

    return connection;
};

var socket = new ChatSocket(socketUrl);

$('form[name=publish]').submit(function(){
    var message = this.message.value;
    if(message.replace(/\s/g, '').length > 0){
        window.socket.sendMessage(currRoom , message);
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

$(document).delegate('.btn-trash', 'click', function(){
    var messageBlock = this.closest('.media');
    var messageId = messageBlock.getAttribute('id').replace('msg_', '');
    window.socket.deleteMessage(currRoom, messageId);
});

$(document).ready(function()
{
    $("#dialog-chat").animate({ scrollTop: $("#dialog-chat")[0].scrollHeight}, 0);
});