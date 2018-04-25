<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>live cam 录像页面</title>
</head>
<body>
    <video autoplay id="sourcevid" style="width:320;height:240px"></video>
    <br>
    提示：最好用火狐测试，谷歌浏览器升级了安全策略，谷歌浏览器只能在https下才能利用html5打开摄像头。

    <canvas id="output" style="display:none"></canvas>

    <script type="text/javascript" charset="utf-8">

        var socket = new WebSocket("ws://"+document.domain+":8080");
        var back = document.getElementById('output');
        var backcontext = back.getContext('2d');
        var video = document.getElementsByTagName('video')[0];
        
        var success = function(stream){
            video.src = window.URL.createObjectURL(stream);
        }

        socket.onopen = function(){
            draw();
        }

        var draw = function(){
            try{
                backcontext.drawImage(video,0,0, back.width, back.height);
            }catch(e){
                if (e.name == "NS_ERROR_NOT_AVAILABLE") {
                    return setTimeout(draw, 100);
                } else {
                    throw e;
                }
            }
            if(video.src){
                socket.send('{"image":"'+back.toDataURL("image/jpeg", 0.5)+'","zhuboName":"<?=$_GET['zhuboName']?>"}')
            }
            setTimeout(draw, 100);
        }
        navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia ||
        navigator.mozGetUserMedia || navigator.msGetUserMedia;
        navigator.getUserMedia({video:true, audio:false}, success, console.log);
    </script>
</body>
</html>
