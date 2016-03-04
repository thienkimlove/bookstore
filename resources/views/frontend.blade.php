<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#" lang="en-US">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- This site is optimized with the Yoast SEO plugin v3.0.7 - https://yoast.com/wordpress/plugins/seo/ -->
    <title>{{$meta_title}}</title>
    <meta name="description"
          content="{{$meta_desc}}">
    <meta name="robots" content="noodp">
    <link rel="canonical" href="{{url('/')}}">
    <meta property="og:locale" content="en_US">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{$meta_title}}">
    <meta property="og:description"
          content="{{$meta_desc}}">
    <meta property="og:url" content="{{$meta_url}}">
    <meta property="og:site_name" content="{{$meta_title}}">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:description"
          content="{{$meta_desc}}">
    <meta name="twitter:title" content="{{$meta_title}}">


    <link rel="alternate" type="application/rss+xml" title="{{$meta_title}} » Feed"
          href="{{url('feed')}}">

    <link rel="stylesheet"
          id="pmpro_frontend-css"
          href="{{url('frontend/css/frontend.css')}}"
          type="text/css" media="screen">
    <link rel="stylesheet"
          id="pmpro_print-css"
          href="{{url('frontend/css/print.css')}}"
          type="text/css"
          media="print">
    <link rel="stylesheet"
          id="cenbooks-theme-css"
          href="{{url('frontend/css/style.css')}}"
          type="text/css" media="all">
    <link rel="stylesheet"
          id="book-review-css"
          href="{{url('frontend/css/book-review-public.css')}}"
          type="text/css" media="all">
    <link rel="stylesheet" id="google-fonts-css" href="{{url('frontend/css/css.css')}}" type="text/css"
          media="all">
    <script type="text/javascript" src="{{url('frontend/js/jquery.js')}}"></script>
    <script type="text/javascript" src="{{url('frontend/js/jquery-migrate.js')}}"></script>
    <script type="text/javascript" src="{{url('frontend/js/paid-memberships-pro.js')}}"></script>
    <script type="text/javascript" src="{{url('frontend/js/entry-date.js')}}"></script>
    <script type="text/javascript" src="{{url('frontend/js/responsive-menu.js')}}"></script>
    <!--[if lt IE 9]>
    <script type="text/javascript" src="{{url('frontend/js/html5shiv.min.js')}}"></script>
    <![endif]-->
</head>
<body class="home blog content-sidebar magazine-pro-orange magazine-home js" itemscope=""
      itemtype="http://schema.org/WebPage">
<div class="site-container">
    @include('frontend.nav')
    @include('frontend.header')
    @yield('content')
    @include('frontend.footer')
</div>

<script type="text/javascript" src="{{url('frontend/js/wp-embed.js')}}"></script>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-74695568-1', 'auto');
  ga('send', 'pageview');

</script>
</body>
</html>