<!DOCTYPE html>
<html>
<head>
    <title>@lang('index.welcome')@lang('index.access')"@lang('index.appName')"，Welcome</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
    <style>
        html, body {
            height: 100%;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            color: #B0BEC5;
            display: table;
            font-weight: 100;
            font-family: 'Lato', 'Microsoft YaHei', SimHei, sans-serif;
        }

        .container {
            text-align: center;
            display: table-cell;
            vertical-align: middle;
        }

        .content {
            text-align: center;
            display: inline-block;
        }

        .title {
            font-size: 72px;
            margin-bottom: 40px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="content">
        <div class="title">@lang('index.welcome')@lang('index.access')"@lang('index.appName')"，Welcome</div>
    </div>
    <h5>
        <a href="http://www.miitbeian.gov.cn/" target="_blank">{{ base64_decode(env('BASE64_ICP')) }}</a>
    </h5>
</div>
</body>
</html>
