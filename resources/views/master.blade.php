<!DOCTYPE html>

<html class="" lang="{{ app()->getLocale() }}">
<!-- Where Imagination Meets Innovation - VOGO -->
@vite('resources/css/app.css')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // axios available in the window object
    })
</script>

<!-- Head -->
<head>
    <title>{{ $settings->_get('title') }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta name="description" content="{{ $settings->_get('description') }}">

    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta name="viewport" content="user-scalable=no, width=device-width, height=device-height, initial-scale=1, maximum-scale=1, minimum-scale=1, minimal-ui" />

    <meta name="theme-color" content="#FCDB5A" />
    <meta name="msapplication-navbutton-color" content="#FCDB5A" />
    <meta name="apple-mobile-web-app-status-bar-style" content="#FCDB5A" />

    <!-- Favicons
    ================================================== -->
    <link rel="shortcut icon" href="{{ Storage::url($settings->_get('favicon')) }}">
    <link rel="apple-touch-icon" href="{{ Storage::url($settings->_get('favicon')) }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ Storage::url($settings->_get('favicon')) }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ Storage::url($settings->_get('favicon')) }}">

    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
    <!-- Critical styles
    ================================================== -->
    <link rel="stylesheet" href="/css/critical.min.css" type="text/css">

    <!-- Load google font
    ================================================== -->
    <script type="text/javascript">
        WebFontConfig = {
            google: { families: [ 'Open+Sans:300,400,500,600,700,800', 'Raleway:100,400,400i,500,500i,700,700i,900'] }
        };
        (function() {
            var wf = document.createElement('script');
            wf.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js';
            wf.type = 'text/javascript';
            wf.async = 'true';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(wf, s);
        })();
    </script>

    <!-- Load other scripts
    ================================================== -->
    <script type="text/javascript">
        var _html = document.documentElement,
            isTouch = (('ontouchstart' in _html) || (navigator.msMaxTouchPoints > 0) || (navigator.maxTouchPoints));

        _html.className = _html.className.replace("no-js","js");
        _html.classList.add( isTouch ? "touch" : "no-touch");
    </script>
    <script type="text/javascript" src="/js/device.min.js"></script>
</head>

<style>
    .top-bar--style-1 {
        background-color: #24292c;
    }
</style>

<body>
<div id="app">
    <!-- start header -->
    <header id="top-bar" class="top-bar {{ request()->routeIs('home') ? 'top-bar--style-2  ' : 'top-bar--style-1' }}">
        <div class="top-bar__bg" style="background-color: #24292c;background-image: url(img/top_bar_bg-1.jpg);background-repeat: no-repeat;background-position: left bottom;"></div>

        <div class="container-fluid">
            <div class="row align-items-center justify-content-between no-gutters">

                <a class="top-bar__logo site-logo" href="i/">
                    <img class="img-fluid" style="padding: 15px 0; max-height: 80px !important;" src="{{ Storage::url($settings->_get('logo')) }}" alt="demo" />
                </a>

                <a id="top-bar__navigation-toggler" class="top-bar__navigation-toggler top-bar__navigation-toggler--light" href="javascript:void(0);"><span></span></a>

                <style>
                    .navigation ul li a {
                        font-weight: bold;
                        font-size: 15px !important;
                    }

                    .navigation li.active>a:not(.custom-btn):after, .navigation li:hover>a:not(.custom-btn):after {
                        margin-top: 19px !important;
                    }
                </style>
                <div id="top-bar__inner" class="top-bar__inner">
                    <div>
                        <nav id="top-bar__navigation" class="top-bar__navigation navigation" role="navigation">
                            <ul>
                                <li class="active">
                                    <a href="/">{{ __('Ana Sayfa') }}</a>
                                </li>

                                <li>
                                    <a href="/#about">{{ __('Hakkımızda') }}</a>
                                </li>

                                <li class="">
                                    <a href="/#science">{{ __('Bilim') }}</a>
                                </li>

                                <li class="">
                                    <a href="/#product">{{ __('Ürün') }}</a>
                                </li>

                                <li class="">
                                    <a href="/#contact">{{ __('İletişim') }}</a>
                                </li>

                                <li class="has-submenu">
                                    <a href="javascript:void(0);">{{ __('Dil') }}</a>

                                    <ul class="submenu">
                                        <li><a href="{{ route('locale', 'tr') }}">TR</a></li>
                                        <li><a href="{{ route('locale', 'en') }}">EN</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>

            </div>
        </div>
    </header>
    <!-- end header -->

<!-- Body -->
@yield('content')

    <!-- start footer -->
    <footer id="footer" class="footer--style-4">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-auto mt-4">
                    <div class="footer__item">
                        <a class="site-logo" href="/">
                            <img class="img-fluid  lazy"
                                 src="/img/blank.gif"
                                 width="130"
                                 data-src="{{ Storage::url($settings->_get('logo')) }}" alt="demo" />
                        </a>
                    </div>
                </div>

                <div class="col-12 col-sm">
                    <div class="row align-items-md-center no-gutters">
                        <div class="col-12 col-md mr-5">
                            <div class="footer__item">
                                <p style="color: #adadb0">
                                    {{ $settings->_get('footer_text') }}
                                </p>
                            </div>
                        </div>

                        <div class="col-12 col-md">
                            <div class="footer__item text-right">
                                <address>
                                    <p>
                                        {{ $settings->_get('address') }}
                                    </p>

                                    <p>
                                        {{ $settings->_get('phone') }} <br>
                                        <a href="mailto:{{ $settings->_get('email') }}">{{ $settings->_get('email') }}</a>
                                    </p>
                                </address>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <div class="row flex-lg-row-reverse mt-5">
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

                <div class="col-12 col-md-auto">
                    <div class="footer__item">
                        <div class="social-btns">
                            @foreach($settings->fields['socials'] as $social)
                                <a href="{{ $social['url'] }}"><i class="bi {{ $social['icon'] }}"></i></a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-12 text-right">
                    <div class="footer__item">
                        <span class="__copy">© {{ date('Y') }}. All rights reserved.
                            Created by <a class="__dev" href="https://lim10soft.com" target="_blank">lim10soft</a></span>
                            &
                            <a class="__dev" href="https://webvogo.com" target="_blank">vogo</a></span>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- end footer -->
</div>

<div id="btn-to-top-wrap">
    <a id="btn-to-top" class="circled" href="javascript:void(0);" data-visible-offset="800"></a>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="/js/jquery-2.2.4.min.js"><\/script>')</script>

<script type="text/javascript" src="/js/main.min.js"></script>
<!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
<script>
    (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
        function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
        e=o.createElement(i);r=o.getElementsByTagName(i)[0];
        e.src='https://www.google-analytics.com/analytics.js';
        r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
    ga('create','UA-XXXXX-X','auto');ga('send','pageview');
</script>
</body>
</html>

<!-- Footer -->
@vite('resources/js/app.js')
