<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <link rel="stylesheet" href="/style/breakEgg.css">
    <link rel="stylesheet" href="/css/normalize.css">
    <link rel="stylesheet" href="/css/main.css">
    <script src="/js/vendor/modernizr-2.6.2.min.js"></script>
    <script src="/js/weixin.js"></script>

</head>
<body>
<div class="chui" id="chui" style="display: none">
    <img src="/img/chui1.png" width="80"/>
</div>
<section class="stage">
    <img class="bg" src="/img/bj.png">
    <img class="yc" src="/img/YC.png"  />
    <div class="guize">
        <!--<a href="#">活动规则</a> -->
        <ul>
            <li>
                <img width="40px" style="border-radius: 50px" src="/img/head.jpg">
                <div class="qian" >19.07元</div>
                <div>苗 2014-12-30 21：23：12 </div><br>
                <div>小手一抖 红包到手 </div>

            </li>
            <li>
                <img width="40px" style="border-radius: 50px" src="/img/head.jpg">
                <div class="qian" >19.07元</div>
                <div>苗 2014-12-30 21：23：12 </div><br>
                <div>小手一抖 红包到手 </div>

            </li>
        </ul>
    </div>
    <div class="info">
        <img src="/img/force.png" class="infopic">
        <div  class="qianshu">19.07<span>元</span></div>
        <div style="color: #fff; font-family: 微软雅黑">邀请好友参与，红包马上变大</div>
        <div style="clear: both;height: 60px;margin-top: 10px">
            <a class="guanzhu twocol" onclick="$('.zhongjiangtip').hide();$('#mcover').show();$('#mcover img').show()">邀请好友</a>
            <p class="guanzhu twocol white"><a href="tixian.html">申请提现</a> </p>
        </div>
        <div style="color: #ffffff;font-size: .8rem;font-family: 微软雅黑;">期待好友合体，马上变大红包！<a href="#" style="color:#fff">刷新进度</a> </div>
    </div>
    <div class="main-box">

    </div>
    </div>
    </div>
    </div>

</section>
<div id="mcover" onclick="document.getElementById('mcover').style.display='';" style="display: block;">
    <img src="img/share.png" style="display: none">
    <div class="zhongjiangtip">
        <p class="  p3 css4401D34FA9112B9D66D0782EE9A1D68F">恭喜您获得<span class="yellow num3">0</span>元红包</p>
        <p class="  p3 css4401D34FA9112B9D66D0782EE9A1D68F">关注微信可申请提现</p>
        <p class="guanzhu">点此关注公众账户</p>
    </div>

</div>

<script src="/js/vendor/jquery-1.10.2.min.js"></script>
<script src="/js/hammer.min.js"></script>
<script src="/js/plugins.js"></script>

<script src="/js/main.js"></script>

<!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
<script>
    WeixinApi.ready(function(Api){
// 微信分享的数据
        var wxData = {
            "imgUrl":document.location.origin+"/img/force.png",
            "link":'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxc43356a7940e32d4&redirect_uri=http%3A%2F%2Fhongbao.webs.dlwebs.com%2Findex.php?parentid=<?php echo ($myid); ?>&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect',
            "desc":'领红包拿大奖！哈蓝送您100元红包大礼！',
            "title":"哈蓝送红包，还不快来领！"
        };
// 分享的回调
        var wxCallbacks = {
// 分享操作开始之前
            ready:function () {
// 你可以在这里对分享的数据进行重组
            },
// 分享被用户自动取消
            cancel:function (resp) {
// 你可以在你的页面上给用户一个小Tip，为什么要取消呢？
            },
// 分享失败了
            fail:function (resp) {
// 分享失败了，是不是可以告诉用户：不要紧，可能是网络问题，一会儿再试试？
            },
// 分享成功
            confirm:function (resp) {
// 分享成功了，我们是不是可以做一些分享统计呢？
                WeixinApi.closeWindow({
                    success : function(resp){
                    },
                    fail : function(resp){
                    }
                });
            },
// 整个分享过程结束
            all:function (resp) {
// 如果你做的是一个鼓励用户进行分享的产品，在这里是不是可以给用户一些反馈了？
            }
        };
// 用户点开右上角popup菜单后，点击分享给好友，会执行下面这个代码
        Api.shareToFriend(wxData, wxCallbacks);
// 点击分享到朋友圈，会执行下面这个代码
        Api.shareToTimeline(wxData, wxCallbacks);
// 点击分享到腾讯微博，会执行下面这个代码
        Api.shareToWeibo(wxData, wxCallbacks);
    });

</script>

</body>


<script type="text/javascript">


</script>
</html>