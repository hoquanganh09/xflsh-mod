<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8"/>
    <meta
        name="viewport"
        content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no"
    />

    <title>{{$title}}</title>
    <link href='https://vpndata.xyz/theme/GIF/SkyhtIcon.ico' rel='shortcut icon' type='image/x-icon' />
    <link rel="stylesheet" href="/assets/admin/vendors.chunk.css?v={{$version}}"/>
    <link rel="stylesheet" href="/assets/admin/compoments.chunk.css?v={{$version}}"/>
    <link rel="stylesheet" href="/assets/admin/custom.css?v={{$version}}">
    <script>window.routerBase = "/";</script>
    <script>
        window.settings = {
            title: '{{$title}}',
            theme: {
                sidebar: '{{$theme_sidebar}}',
                header: '{{$theme_header}}',
                color: '{{$theme_color}}',
            },
            description: '{{$description}}',
            version: '{{$version}}',
            background_url: '{{$background_url}}'
        }
    </script>
</head>


<body>
<div id="root"></div>
<script src="/assets/admin/vendors.js?v={{$version}}"></script>
<script src="/assets/admin/compoments.js?v={{$version}}"></script>
<script src="/assets/admin/umi.js?v={{$version}}"></script>



<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }

    gtag('js', new Date());
    gtag('config', 'AHIHI');
</script>
</body>
</html>
