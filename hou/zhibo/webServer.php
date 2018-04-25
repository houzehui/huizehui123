<?php 

$server = new swoole_websocket_server("0.0.0.0",8080);
$redis=new Redis();
$redis->connect('127.0.0.1',6379);

// $server->on('open', function (swoole_websocket_server $server, $request) {
//     echo "server: handshake success with fd{$request->fd}\n";
// });

$server->on('message', function (swoole_websocket_server $server, $frame) use($redis) {
    //echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
    
    $json=$frame->data;

    $array=json_decode($json,true);

    if(isset($array['type']) and $array['type']=='guanzhong'){//当条件成立的时候，则说明是观众

         //为主播保存好自己的观众
         $redis->hset('customer_'.$array['zhuboName'],$frame->fd,11);
         $redis->hset('customer_zhubo',$frame->fd,$array['zhuboName']);

    }else{
                 //这里是主播的代码
                 
                //数据流，，图片
			    $image=$array['image'];
        
			    $zhuboName=$array['zhuboName'];
                //数据图片队列
			    //$redis->rpush('zhubo_images_'.$zhuboName,$image);
                //取出的视频流
               // $imgeWillSend=$redis->lpop('zhubo_images_'.$zhuboName);
               
                //取出主播下所有的 ‘观众’
                $customer=$redis->hkeys('customer_'.$array['zhuboName']);
     
                foreach($customer as $val){
                     $server->push($val, $image);

                }
    }

       

//    $server->push($frame->fd, "我是学生");
});

$server->on('close', function ($ser, $fd) use($redis) {

	 $zhuboName=$redis->hget('customer_zhubo',$fd);
     $redis->hrem('customer_'.$zhuboName,$fd);
});

$server->start();