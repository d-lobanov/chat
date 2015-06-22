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
                console.log(response.head);
        }
    };

    connection.onerror = function(error) {
        console.log('Error: ' + error.message);
    };

    connection.onclose = function(event) {
        console.log('Code: ' + event.code + ' reason: ' + event.reason);
    };

    connection.onEventDelete = function(response){ };

    connection.onEventMessage = function(response){ };

    connection.sendObject = function(obj) {
        data = JSON.stringify(obj);
        connection.send(data);
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