<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>测试小程序</title>
    <script src="https://res.wx.qq.com/open/js/jweixin-1.3.2.js"></script>
</head>
<body>
<a href="javascript:ready()">点击测试</a>
<script>
    // web-view下的页面内
    function ready() {
        console.log(window.__wxjs_environment === 'miniprogram') // true
        if (window.__wxjs_environment === 'miniprogram') {
            wx.miniProgram.redirectTo({
                url:'/pages/wxpay/wxpay?appId=wx940ea58d8789b8ff&timeStamp=1620276669&nonceStr=609375bdd146c&package=prepay_id=wx061251097921020f6c4370880acd380000&signType=MD5&paySign=BF5A7E664DF6428A6E6F78896C469EE1&order_no=210506125109000025'
            })
        }
    }

    if (!window.WeixinJSBridge || !WeixinJSBridge.invoke) {
        document.addEventListener('WeixinJSBridgeReady', ready, false)
    } else {
        ready()
    }

    function loadJs(src) {
        return new Promise((resolve, reject) => {
            let script = document.createElement('script')
            script.type = "text/javascript"
            script.onload = () => {
                resolve()
            }
            script.onerror = () => {
                reject()
            }
            script.src = src
            document.getElementsByTagName('body')[0].appendChild(script)
        })
    }
</script>
</body>
</html>
