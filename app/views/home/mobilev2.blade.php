<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>聘爱 - 全国首个大学生恋爱平台</title>
    <meta id="viewport" name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,minimal-ui">

    @include('layout.meta')
    @yield('content')

    <meta http-equiv="Page-Enter" content="revealTrans(duration=2, transition=4">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type="text/css">
        body,h1,h2,h3,h4,h5,h6,p,dl,dd,ul,ol,pre,form,input,textarea,th,td,select{margin:0; padding:0;}
        em{font-style:normal}
        li{list-style:none}
        a{text-decoration:none;}
        img{border:none; vertical-align:top;}
        table{border-collapse:collapse;}
        input,textarea{outline:none;}
        textarea{ resize:none; overflow:auto;}
        body{font-size:12px; font-family:Microsoft YaHei,SimHei,Arial,Pro LiHei Pro Medium;max-width:639px;margin:0 auto;text-align:center;}
        .clear { zoom:1; }
        .clear:after { content:''; display:block; clear:both; }

        body{background:#ef698a;}
        .logo{width:100px;margin-top:30px;}
        .s_title{font-size:25px;color:#fff;display:block;margin-top:30px;}
        .instruct{width:80%;font-size:14px;color:#fff;display:block;margin-top:20px;}
        #android{margin-top:30px;}
        .but{display:block;width:180px;height:40px;border:1px solid #fff;color:#fff;line-height:40px;font-size:15px;border-radius:3px;margin-top:15px;}
    </style>
</head>
<body>
    <center>
    {{ HTML::image('assets/images/mobile-home-logo.png', '', array('class' => 'logo')) }}
    <span class="s_title" >聘爱</span>
    <span class="instruct">全国首个大学生恋爱平台，为恋爱营造一个良好的氛围，相同的年纪更多的话题，让你的大学不留白。</span>
    <a id="android" class="but" href="http://fir.im/pinai">安卓客户端下载</a>
    <a href="http://fir.im/pinios" class="but">iOS客户端下载</a>
    <span class="but" >PC端：百度搜索聘爱</span>
    </center>
    {{-- Analytics Code --}}
    @include('layout.analytics')
    @yield('content')
</body>
</html>