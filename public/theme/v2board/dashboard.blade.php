<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8"/>
    <meta
        name="viewport"
        content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no"
    />
    <link href='https://vpndata.xyz/theme/GIF/SkyhtIcon.ico' rel='shortcut icon' type='image/x-icon' />
    <link rel="stylesheet" href="/theme/{{$theme}}/assets/vendors.chunk.css?v={{$version}}"/>
    <link rel="stylesheet" href="/theme/{{$theme}}/assets/compoments.chunk.css?v={{$version}}"/>

    <script>window.routerBase = "/";</script>
    <script>
        window.settings = {
            title: '{{$title}}',
            theme: {
                sidebar: '{{$theme_sidebar}}',
                header: '{{$theme_header}}',
                color: '{{$theme_color}}',
            },
            version: '{{$version}}',
            background_url: '{{$background_url}}',
            description: '{{$description}}',
            
        }
    </script>
</head>

<body>
<div id="root"></div>
<script src="/theme/{{$theme}}/assets/vendors.js?v={{$version}}"></script>
<script src="/theme/{{$theme}}/assets/compoments.js?v={{$version}}"></script>
<script src="/theme/{{$theme}}/assets/umi.js?v={{$version}}"></script>

</body>

</html>
