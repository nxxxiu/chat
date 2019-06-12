<?php
$server = new swoole_websocket_server("0.0.0.0", 9502);

$server->on('open', function($server, $req) {
    echo "connection open: {$req->fd}\n";
});

$server->on('message', function($server, $frame) {
    echo "received message: {$frame->data}\n";
//    $server->push($frame->fd, json_encode(["hello", "world"]));

    //检查当前所有连接 广播所有消息
    foreach($server->connections as $fds){
        //判断是否与websocket链接，是否有可能会push失败
        if($server->isEstablished($fds)){
//            echo '<pre>';print_r($frame->data);echo '</pre>';
            $server->push($fds,$frame->data);
        }
    }

});

$server->on('close', function($server, $fd) {
    echo "connection close: {$fd}\n";
});

$server->start();