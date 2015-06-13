var socket = new WebSocket("ws://192.168.56.101:8080");

socket.onmessage = function(event) {
    var incomingMessage = event.data;
    $('.msg-wrap').append(incomingMessage);
    $("#dialog-chat").animate({ scrollTop: $("#dialog-chat")[0].scrollHeight}, 500);
};

socket.onopen = function() {
    console.log('Connect.');
};

socket.onerror = function(error){
    alert('Error');
    console.log(error.message);
}

socket.onclose = function(event) {
    alert('Connection close');
    console.log('Code: ' + event.code + ' reason: ' + event.reason);
};



$('form[name=publish]').submit(function(){
    var message = this.message.value;
    if(message.replace(/\s/g, '').length > 0){
        window.socket.send(message);
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