<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>live cam 接收页面</title>
</head>
<body>
    <img id="receiver" style="width:320px;height:240px"/>
    <br><br>如果显示空白，说明当前没有人在直播，<a href="/camera.html" target="_blank">点击这里直播</a>
    <script type="text/javascript" charset="utf-8">
        var receiver_socket = new WebSocket("ws://"+document.domain+":8080");
        var image = document.getElementById('receiver');

        receiver_socket.onopen=function(){
          receiver_socket.send('{"zhuboName":"<?=$_GET['zhuboName']?>","type":"guanzhong"}');
            

        }
        receiver_socket.onmessage = function(data)
        {
            image.src=data.data;
        }
    </script>
</body>
</html>
