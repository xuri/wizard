<!DOCTYPE html>
<html lang="zh_CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    {{ HTML::script('assets/js/jquery-1.11.1/jquery.min.js') }}
    <title>点击下载应用</title>
    <style type="text/css">
        body,h1,h2,h3,h4,h5,h6,p,dl,dd,ul,ol,pre,form,input,textarea,th,td,select{margin:0; padding:0;}
        em{font-style:normal}
        li{list-style:none}
        a{text-decoration:none;}
        img{border:none; vertical-align:top;}
        table{border-collapse:collapse;}
        input,textarea{outline:none;}
        textarea{ resize:none; overflow:auto;}
        body{font-size:12px;font-family:Microsoft YaHei,SimHei,Arial,Pro LiHei Pro Medium;padding-bottom: 50px;
    background:#fe949e;content:center;}
        .div_t{width:100%;height:100px;}
        .btn{height:60px;float:right;margin-top:20px;margin-right:20px;}
        .tip{color:#fff;font-size:16px;float:right;margin-top:70px;margin-right:10px;}
        .logo{margin:0 auto;}
        .div_b{width:150px;margin:100px auto 0;text-align:center;}
        .logo{width:150px;margin-bottom:15px;}
        .tip_b{color:#fff;font-size:15px;margin-top:20px;}
    </style>
</head>
<body>
    <div class="div_t">
    {{ HTML::image('assets/images/wap/wx_btn.png', '微信打开', array('class' => 'btn')) }}
    <span class="tip">请点击从浏览器打开</span>
    </div>
    <div class="div_b">
        {{ HTML::image('assets/images/wap/wx_tip.png', '微信打开', array('class' => 'logo')) }}
        <span class="tip_b">浏览器不支持下载</span>
    </div>
    <script type="text/javascript">
        $(window).on("load",function(){
            var winHeight = $(window).height();
            function is_weixin() {
                var ua = navigator.userAgent.toLowerCase();
                if (ua.match(/MicroMessenger/i) == "micromessenger") {
                    return true;
                } else {
                    return false;
                }
            }
            var isWeixin = is_weixin();
            if(isWeixin){
                $(".weixin-tip").css("height",winHeight);
                $(".weixin-tip").show();
            } else {
                window.location.href = "{{ route('download') }}";
            }
        })
    </script>
</body>
</html>