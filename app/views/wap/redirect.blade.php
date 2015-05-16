<!DOCTYPE html>
<html lang="zh_CN">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	{{ HTML::script('assets/js/jquery-1.11.1/jquery.min.js') }}
	<title>点击下载应用</title>
	<style type="text/css">
	*{margin:0; padding:0;}
	a{text-decoration: none;}
	img{max-width: 100%; height: auto;}
	.weixin-tip{display: none; position: fixed; left:0; top:0; bottom:0; background: rgba(0,0,0,0.8); filter:alpha(opacity=80);  height: 100%; width: 100%; z-index: 100;}
	.weixin-tip p{text-align: center; margin-top: 10%; padding:0 5%;}
	</style>
</head>
<body>
	<div class="weixin-tip">
		<p>
			{{ HTML::image('assets/images/wap/live_weixin.png', '微信打开', array()) }}
		</p>
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
				window.location.href = "{{ route('home') }}";
			}
        })
	</script>
</body>
</html>