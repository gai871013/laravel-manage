<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>测试小程序</title>
</head>
<body>
<script>
    // web-view下的页面内
    function ready() {
        console.log(window.__wxjs_environment === 'miniprogram') // true
    }

    if (!window.WeixinJSBridge || !WeixinJSBridge.invoke) {
        document.addEventListener('WeixinJSBridgeReady', ready, false)
    } else {
        ready()
    }
</script>
</body>
</html>
