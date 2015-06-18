<?php
    include_once(app_path('api/wechat/jssdk.php'));
    $jssdk          = new JSSDK("wx85a303018cc9100b", "be2909ec1f4f590feb25aa6638a63d5f");
    $signPackage    = $jssdk->GetSignPackage();
?>

<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript">
  wx.config({
    debug: false,
    appId: '<?php echo $signPackage["appId"];?>',
    timestamp: <?php echo $signPackage["timestamp"];?>,
    nonceStr: '<?php echo $signPackage["nonceStr"];?>',
    signature: '<?php echo $signPackage["signature"];?>',
    jsApiList: [
      // 所有要调用的 API 都要加到这个列表中
    'onMenuShareTimeline'
    ]
  });

 wx.ready(function () {
        // 在这里调用 API
        wx.onMenuShareTimeline({
        title: '大三学生研发脱单神器，朋友圈福利！', // 分享标题
        link: 'http://www.pinai521.com/wap', // 分享链接
        imgUrl: "http://www.pinai521.com/assets/images/wechat/boy.jpg", // 分享图标
        success: function () {
        },
        cancel: function () {
        },
        fail: function (res) {
        alert('wx.onMenuShareTimeline:fail: '+JSON.stringify(res));
        }
        });
  });
 wx.error(function (res) {
        alert('wx.error: '+JSON.stringify(res));
  });
</script>