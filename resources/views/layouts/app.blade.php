<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PapperclipSA') }}</title>

    <!-- Scripts -->
    <!-- <script src="{{ asset('js/app.js') }}" defer></script> -->
    @yield('header')

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link rel="stylesheet" href="{{asset('vendor/bootstrap-4.0.0/dist/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->
    <style>
        body
        {
            /* -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            -o-user-select: none;
            user-select: none; */
            background-color: #181818; 
        }
        .navbar-brand
        {
            margin-top: 5px;
        }
        .banner
        {
            background: url("<?php echo  asset('images/banners/img-2.jpg')?>");
            border-radius: 0px;
            background-repeat: no-repeat;
            background-size: contain;
            height: 706px;
        }

        .vod-item
        {
        padding:1px !important;
        margin: 0px !important;
        }
        .vod-item a
        {
        text-decoration: none;
        color: #ffffff;

        }
        .vod-item a:hover
        {
            text-decoration: none;
            color: #808080;
            -webkit-box-shadow: 0px 3px 15px rgba(0,0,0,0.2); /* Safari 3-4, iOS 4.0.2 - 4.2, Android 2.3+ */
            -moz-box-shadow:    0px 3px 15px rgba(0,0,0,0.2); /* Firefox 3.5 - 3.6 */
            box-shadow:         0px 3px 15px rgba(0,0,0,0.2); /* Opera 10.5, IE 9, Firefox 4+, Chrome 6+, iOS 5 */
        }
        .play-icon 
        { 
            font-size: 24px;
            color: #ffffff;
            position: absolute;
            top: 5px;
            right: 5px;
        }

        .main-heading
        {
            color: #ffffff;
        }

        .card
        {
            color: #ffffff;
        }

        .card-header
        {
            background-color: #D00000;
            color: #ffffff;
        }
        .card-body
        {
            background-color: #181818;

        }

        .btn-link
        {
            color: #ffffff;
        }

        .btn-link:hover{
            color: #ffffff;
        }

        .navbar {
            
            background: rgba(208, 0, 0 , 0.7) /* Green background with 30% opacity */
        }
    </style>
    @yield('styles')
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark sticky-top">
        <a class="navbar-brand" href="{{ url('/') }}">
            <svg id="Layer_1" data-name="Layer 1" height="35px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 241.28 54.17"><defs><style>.cls-1{fill:#fff;}.cls-2{fill:none;stroke:#fff;stroke-miterlimit:10;stroke-width:1.91px;}</style></defs>
            <title>PClogo</title>
            <path class="cls-1" d="M65.46,8.55h8.19a9.78,9.78,0,0,1,3.23.5,7,7,0,0,1,2.4,1.42,6.12,6.12,0,0,1,1.52,2.19,7.34,7.34,0,0,1,.53,2.82v.06a7,7,0,0,1-.63,3.07A6.34,6.34,0,0,1,79,20.81a7.52,7.52,0,0,1-2.58,1.33,10.8,10.8,0,0,1-3.17.45H69.87v6H65.46Zm7.91,10.11A3.61,3.61,0,0,0,76,17.79a2.86,2.86,0,0,0,.91-2.16v-.06a2.73,2.73,0,0,0-1-2.26,4,4,0,0,0-2.62-.78H69.87v6.13Z"/>
            <path class="cls-1" d="M88.49,28.89a6.87,6.87,0,0,1-2-.3,4.81,4.81,0,0,1-1.67-.9,4.33,4.33,0,0,1-1.13-1.48,4.62,4.62,0,0,1-.42-2v-.06A4.73,4.73,0,0,1,83.68,22,4.16,4.16,0,0,1,85,20.44a5.84,5.84,0,0,1,2-.9,9.82,9.82,0,0,1,2.51-.3,10.41,10.41,0,0,1,2,.17,10.93,10.93,0,0,1,1.7.46v-.26a2.6,2.6,0,0,0-.83-2.09,3.65,3.65,0,0,0-2.46-.72,9.81,9.81,0,0,0-2.22.22,13.86,13.86,0,0,0-2,.61l-1.09-3.32a17.68,17.68,0,0,1,2.63-.89,13.93,13.93,0,0,1,3.33-.34,9.6,9.6,0,0,1,3.08.44,5.4,5.4,0,0,1,2.1,1.28,5.18,5.18,0,0,1,1.26,2.1,9,9,0,0,1,.4,2.79V28.6H93.13V26.94a5.93,5.93,0,0,1-1.92,1.42A6.34,6.34,0,0,1,88.49,28.89Zm1.32-3a3.9,3.9,0,0,0,2.48-.74,2.39,2.39,0,0,0,.93-1.95v-.77A5.83,5.83,0,0,0,91.94,22a7.11,7.11,0,0,0-1.5-.16,3.84,3.84,0,0,0-2.21.55,1.78,1.78,0,0,0-.8,1.57V24a1.6,1.6,0,0,0,.66,1.37A2.78,2.78,0,0,0,89.81,25.88Z"/>
            <path class="cls-1" d="M101.1,13.25h4.35v2.2a7,7,0,0,1,1.93-1.79,5.42,5.42,0,0,1,2.85-.7,6.72,6.72,0,0,1,2.61.52A6.36,6.36,0,0,1,115.07,15a7.65,7.65,0,0,1,1.56,2.49,9,9,0,0,1,.59,3.4V21a9,9,0,0,1-.59,3.4,7.78,7.78,0,0,1-1.54,2.49,6.23,6.23,0,0,1-2.22,1.53,6.79,6.79,0,0,1-2.64.52,5.48,5.48,0,0,1-2.88-.69,7.68,7.68,0,0,1-1.9-1.6v6.59H101.1Zm8,11.94a3.51,3.51,0,0,0,1.44-.3,3.46,3.46,0,0,0,1.19-.86,4.15,4.15,0,0,0,.82-1.33,4.79,4.79,0,0,0,.3-1.75V20.9a4.89,4.89,0,0,0-.3-1.74,4.2,4.2,0,0,0-.82-1.34,3.59,3.59,0,0,0-1.19-.86,3.63,3.63,0,0,0-2.89,0,3.55,3.55,0,0,0-1.18.86,4.34,4.34,0,0,0-.8,1.34,4.89,4.89,0,0,0-.3,1.74V21a4.89,4.89,0,0,0,.3,1.74,4.34,4.34,0,0,0,.8,1.34,3.42,3.42,0,0,0,1.18.86A3.52,3.52,0,0,0,109.12,25.19Z"/>
            <path class="cls-1" d="M127.71,29a8.79,8.79,0,0,1-3.2-.58A7.38,7.38,0,0,1,122,26.74a7.61,7.61,0,0,1-1.67-2.51,8.29,8.29,0,0,1-.61-3.22V21a8.64,8.64,0,0,1,.56-3.09,8.25,8.25,0,0,1,1.56-2.55,7.43,7.43,0,0,1,2.4-1.72,7.34,7.34,0,0,1,3.08-.63,7.48,7.48,0,0,1,3.32.69,6.74,6.74,0,0,1,2.33,1.85,7.75,7.75,0,0,1,1.38,2.66,11.24,11.24,0,0,1,.44,3.14c0,.17,0,.35,0,.54s0,.39-.05.6H124a3.63,3.63,0,0,0,1.3,2.25,3.89,3.89,0,0,0,2.45.76,4.81,4.81,0,0,0,2-.37,7,7,0,0,0,1.78-1.2l2.49,2.2a7.83,7.83,0,0,1-2.64,2.11A8.23,8.23,0,0,1,127.71,29Zm2.8-9.23a4.24,4.24,0,0,0-1-2.36,2.85,2.85,0,0,0-2.2-.9,2.89,2.89,0,0,0-2.22.89A4.38,4.38,0,0,0,124,19.72Z"/><path class="cls-1" d="M138,13.25h4.35v3.09a6.34,6.34,0,0,1,1.81-2.52,4.41,4.41,0,0,1,3.09-.86v4.56H147a4.5,4.5,0,0,0-3.42,1.31,5.81,5.81,0,0,0-1.25,4.1V28.6H138Z"/>
            <path class="cls-1" d="M157.21,29a8.08,8.08,0,0,1-3.2-.63,7.85,7.85,0,0,1-2.53-1.71,7.63,7.63,0,0,1-1.66-2.52,7.94,7.94,0,0,1-.6-3.08V21a8.06,8.06,0,0,1,.6-3.09,8,8,0,0,1,1.66-2.55A7.88,7.88,0,0,1,154,13.59a8.11,8.11,0,0,1,9.39,1.95l-2.67,2.86a7.65,7.65,0,0,0-1.53-1.23,3.92,3.92,0,0,0-2-.46,3.38,3.38,0,0,0-1.51.33,3.56,3.56,0,0,0-1.17.91,3.9,3.9,0,0,0-.77,1.33,4.52,4.52,0,0,0-.28,1.62V21a4.8,4.8,0,0,0,.28,1.67,4,4,0,0,0,.78,1.34,3.91,3.91,0,0,0,1.23.9,4.21,4.21,0,0,0,3.51-.11,7.81,7.81,0,0,0,1.62-1.19l2.55,2.58a9.76,9.76,0,0,1-2.55,2A7.72,7.72,0,0,1,157.21,29Z"/>
            <path class="cls-1" d="M166.52,7.69h4.35V28.6h-4.35Z"/>
            <path class="cls-1" d="M175.05,7.69h4.59v3.87h-4.59Zm.12,5.56h4.35V28.6h-4.35Z"/>
            <path class="cls-1" d="M183.62,13.25H188v2.2a7,7,0,0,1,1.94-1.79,5.37,5.37,0,0,1,2.85-.7,6.7,6.7,0,0,1,2.6.52A6.4,6.4,0,0,1,197.6,15a7.65,7.65,0,0,1,1.56,2.49,9.21,9.21,0,0,1,.58,3.4V21a9.18,9.18,0,0,1-.58,3.4,7.8,7.8,0,0,1-1.55,2.49,6.23,6.23,0,0,1-2.22,1.53,6.78,6.78,0,0,1-2.63.52,5.43,5.43,0,0,1-2.88-.69A7.74,7.74,0,0,1,188,26.6v6.59h-4.35Zm8,11.94A3.52,3.52,0,0,0,194.27,24a4,4,0,0,0,.82-1.33,4.79,4.79,0,0,0,.3-1.75V20.9a4.89,4.89,0,0,0-.3-1.74,4,4,0,0,0-.82-1.34,3.59,3.59,0,0,0-1.19-.86,3.47,3.47,0,0,0-1.44-.3,3.52,3.52,0,0,0-1.45.3,3.42,3.42,0,0,0-1.17.86,4.15,4.15,0,0,0-.8,1.34,4.68,4.68,0,0,0-.31,1.74V21a4.68,4.68,0,0,0,.31,1.74A4.15,4.15,0,0,0,189,24a3.29,3.29,0,0,0,1.17.86A3.52,3.52,0,0,0,191.64,25.19Z"/>
            <path class="cls-1" d="M210.57,28.89a13.17,13.17,0,0,1-4.5-.79,11.86,11.86,0,0,1-4-2.42l2.61-3.12a13.06,13.06,0,0,0,2.82,1.77,7.56,7.56,0,0,0,3.17.66,3.79,3.79,0,0,0,2.13-.5,1.57,1.57,0,0,0,.76-1.39v-.05a1.67,1.67,0,0,0-.16-.76,1.52,1.52,0,0,0-.6-.62,6.4,6.4,0,0,0-1.24-.57,21.27,21.27,0,0,0-2.05-.6,27.74,27.74,0,0,1-2.76-.83,7.81,7.81,0,0,1-2.09-1.14,4.47,4.47,0,0,1-1.34-1.67,5.92,5.92,0,0,1-.47-2.49v-.06a5.85,5.85,0,0,1,.51-2.48,5.57,5.57,0,0,1,1.44-1.9A6.35,6.35,0,0,1,207,8.69a9,9,0,0,1,2.83-.43,12,12,0,0,1,4,.66,11.78,11.78,0,0,1,3.39,1.89L215,14.14a14.44,14.44,0,0,0-2.63-1.45,6.81,6.81,0,0,0-2.58-.53,3.13,3.13,0,0,0-1.93.5,1.54,1.54,0,0,0-.65,1.25V14a1.74,1.74,0,0,0,.19.84,1.66,1.66,0,0,0,.67.64,5.84,5.84,0,0,0,1.34.55c.56.17,1.28.37,2.14.6a21.49,21.49,0,0,1,2.71.9,7.5,7.5,0,0,1,2,1.21,4.49,4.49,0,0,1,1.24,1.64,5.58,5.58,0,0,1,.41,2.27v.05a6.18,6.18,0,0,1-.54,2.65,5.37,5.37,0,0,1-1.5,1.95,6.68,6.68,0,0,1-2.31,1.2A10,10,0,0,1,210.57,28.89Z"/>
            <path class="cls-1" d="M228.62,8.41h4.06l8.6,20.19h-4.61l-1.84-4.49h-8.48l-1.83,4.49H220Zm4.64,11.8-2.67-6.5-2.66,6.5Z"/><path class="cls-1" d="M64.2,46l2.35-10.6h2l-2,8.9h4.61L70.83,46Z"/><path class="cls-1" d="M74.17,46H72.35L74,38.39h1.82Zm.18-9,.38-1.7h1.82L76.17,37Z"/>
            <path class="cls-1" d="M77.13,38.39H79l.12,5.16h0l2.44-5.16h1.82L79.48,46H77.57Z"/><path class="cls-1" d="M84.76,42.5c-.32,1.58.25,2.2,1.1,2.2a2.62,2.62,0,0,0,1.91-.91l1.13.82a4.35,4.35,0,0,1-3.54,1.56c-2,0-2.89-1.54-2.35-4s2.12-4,4.19-4,2.74,1.68,2.34,3.51l-.17.75ZM88,41.27c.21-1.06-.21-1.68-1.09-1.68A1.93,1.93,0,0,0,85,41.27Z"/>
            <path class="cls-1" d="M98.45,46l0-.66a4.78,4.78,0,0,1-2.55.81,1.53,1.53,0,0,1-1.62-2c.38-1.73,2-2.64,5-3l.15-.65c.13-.58-.27-.91-.92-.91a2.55,2.55,0,0,0-1.88.94l-1-.89a4.31,4.31,0,0,1,3.15-1.4c2,0,2.76.79,2.25,3.08l-.79,3.6a3.25,3.25,0,0,0-.1,1.1Zm.62-3.65c-1.27.23-2.66.48-2.89,1.55-.13.57.16.9.68.9A4,4,0,0,0,98.71,44Z"/>
            <path class="cls-1" d="M101.9,46l1.69-7.63h1.68l-.18.82a5.24,5.24,0,0,1,2.56-1c1.52,0,2,1,1.69,2.56L108.18,46h-1.82l1.1-5c.23-1,0-1.35-.64-1.35a2.93,2.93,0,0,0-1.88.81L103.72,46Z"/>
            <path class="cls-1" d="M116.34,46H114.8l.1-.82a3.56,3.56,0,0,1-2.29,1c-2,0-2.65-1.79-2.13-4.17.42-1.88,1.92-3.76,3.89-3.76a2.07,2.07,0,0,1,1.66.85h0l.81-3.67h1.82Zm-.56-5.66a1.62,1.62,0,0,0-1.27-.65c-1.08,0-1.89.88-2.25,2.49s.06,2.5,1.15,2.5A2.43,2.43,0,0,0,115,44Z"/>
            <path class="cls-1" d="M126.61,46.17c-2.89,0-4-2-3.24-5.45s2.77-5.44,5.66-5.44,4,2,3.24,5.44S129.51,46.17,126.61,46.17Zm2-9.19c-1.66,0-2.7,1.26-3.25,3.74s-.08,3.74,1.59,3.74,2.7-1.26,3.25-3.74S130.31,37,128.65,37Z"/>
            <path class="cls-1" d="M132.49,46l1.69-7.63h1.67l-.18.82a5.31,5.31,0,0,1,2.56-1c1.53,0,2,1,1.7,2.56L138.77,46H137l1.1-5c.23-1,0-1.35-.64-1.35a2.91,2.91,0,0,0-1.88.81L134.31,46Z"/>
            <path class="cls-1" d="M141,43l.35-1.59H145L144.62,43Z"/>
            <path class="cls-1" d="M151.82,46h-1.54l.09-.82a3.56,3.56,0,0,1-2.28,1c-2,0-2.66-1.79-2.13-4.17.41-1.88,1.92-3.76,3.88-3.76a2.07,2.07,0,0,1,1.66.85h0l.81-3.67h1.82Zm-.57-5.66a1.62,1.62,0,0,0-1.26-.65c-1.09,0-1.9.88-2.26,2.49s.06,2.5,1.15,2.5a2.43,2.43,0,0,0,1.56-.68Z"/>
            <path class="cls-1" d="M155.71,42.5c-.32,1.58.24,2.2,1.1,2.2a2.58,2.58,0,0,0,1.9-.91l1.14.82a4.38,4.38,0,0,1-3.55,1.56c-2,0-2.88-1.54-2.35-4s2.13-4,4.2-4,2.74,1.68,2.33,3.51l-.16.75Zm3.21-1.23c.2-1.06-.22-1.68-1.1-1.68A2,2,0,0,0,156,41.27Z"/>
            <path class="cls-1" d="M161,46l1.69-7.63h1.66l-.18.82a3.74,3.74,0,0,1,2.5-1,1.55,1.55,0,0,1,1.64,1.12,4.27,4.27,0,0,1,2.87-1.12c.79,0,1.77.47,1.37,2.29L171.36,46h-1.81l1.18-5.33c.13-.61,0-.95-.7-1a3.6,3.6,0,0,0-1.7.82L167.11,46h-1.82l1.18-5.33c.14-.61,0-.95-.69-1a3.63,3.63,0,0,0-1.71.82L162.85,46Z"/>
            <path class="cls-1" d="M177.15,46l0-.66a4.84,4.84,0,0,1-2.56.81,1.53,1.53,0,0,1-1.62-2c.38-1.73,2-2.64,5-3l.14-.65c.13-.58-.27-.91-.91-.91a2.53,2.53,0,0,0-1.88.94l-1-.89a4.35,4.35,0,0,1,3.16-1.4c2,0,2.75.79,2.25,3.08l-.8,3.6a3.53,3.53,0,0,0-.1,1.1Zm.62-3.65c-1.27.23-2.66.48-2.9,1.55a.66.66,0,0,0,.68.9A4,4,0,0,0,177.4,44Z"/>
            <path class="cls-1" d="M180.6,46l1.69-7.63H184l-.18.82a5.31,5.31,0,0,1,2.56-1c1.53,0,2,1,1.7,2.56L186.88,46h-1.82l1.1-5c.23-1,0-1.35-.64-1.35a2.91,2.91,0,0,0-1.88.81L182.42,46Z"/>
            <path class="cls-1" d="M195,46H193.5l.09-.82a3.54,3.54,0,0,1-2.28,1c-2,0-2.66-1.79-2.13-4.17.41-1.88,1.92-3.76,3.88-3.76a2.07,2.07,0,0,1,1.66.85h0l.82-3.67h1.82Zm-.57-5.66a1.6,1.6,0,0,0-1.26-.65c-1.09,0-1.9.88-2.26,2.49s.07,2.5,1.15,2.5a2.43,2.43,0,0,0,1.56-.68Z"/>
            <path class="cls-1" d="M202.29,38.39h1.82l.12,5.16h0l2.43-5.16h1.82L204.64,46h-1.91Z"/>
            <path class="cls-1" d="M209.92,46H208.1l1.69-7.63h1.82Zm.18-9,.38-1.7h1.82l-.38,1.7Z"/>
            <path class="cls-1" d="M218.27,46h-1.54l.09-.82a3.54,3.54,0,0,1-2.28,1c-2,0-2.66-1.79-2.13-4.17.42-1.88,1.92-3.76,3.89-3.76a2.08,2.08,0,0,1,1.66.85h0l.81-3.67h1.82Zm-.56-5.66a1.64,1.64,0,0,0-1.27-.65c-1.09,0-1.9.88-2.25,2.49s.06,2.5,1.14,2.5a2.41,2.41,0,0,0,1.56-.68Z"/>
            <path class="cls-1" d="M222.16,42.5c-.32,1.58.25,2.2,1.1,2.2a2.58,2.58,0,0,0,1.9-.91l1.14.82a4.35,4.35,0,0,1-3.54,1.56c-2,0-2.89-1.54-2.35-4s2.12-4,4.19-4,2.74,1.68,2.34,3.51l-.17.75Zm3.21-1.23c.21-1.06-.22-1.68-1.1-1.68a1.93,1.93,0,0,0-1.83,1.68Z"/>
            <path class="cls-1" d="M231.94,38.24c2.07,0,3,1.54,2.44,4s-2.13,4-4.2,4-3-1.54-2.43-4S229.87,38.24,231.94,38.24Zm-.32,1.47c-1,0-1.68.79-2.05,2.49s-.07,2.5.94,2.5,1.67-.79,2.05-2.5S232.63,39.71,231.62,39.71Z"/>
            <path class="cls-1" d="M239.78,40.4a1.56,1.56,0,0,0-1.24-.81c-.7,0-1.09.3-1.19.75-.29,1.31,3.65.75,3,3.54a3.06,3.06,0,0,1-3.31,2.29,2.61,2.61,0,0,1-2.55-1.54l1.42-.91a1.7,1.7,0,0,0,1.53,1.1,1.24,1.24,0,0,0,1.28-.87c.29-1.31-3.63-.84-3-3.49a3,3,0,0,1,3-2.22,2.78,2.78,0,0,1,2.43,1.16Z"/><path class="cls-1" d="M27.08,0A27.09,27.09,0,1,0,54.17,27.08,27.09,27.09,0,0,0,27.08,0Zm0,50.07a23,23,0,1,1,23-23A23,23,0,0,1,27.08,50.07Z"/><circle class="cls-2" cx="27.08" cy="27.08" r="18.44"/><circle class="cls-1" cx="27.08" cy="27.08" r="5.01"/><circle class="cls-1" cx="35.85" cy="34.69" r="2.62"/><path class="cls-1" d="M13.29,21.77l6,1.92a5.71,5.71,0,0,1,5.17-4.39l-.27-6.51A16.05,16.05,0,0,0,13.29,21.77Z"/><path class="cls-1" d="M18.33,25.18l-5.7-1.88s-.58.81-.65,4.6l6.24,0A16.15,16.15,0,0,1,18.33,25.18Z"/></svg>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                <!-- Nav Links -->
                <li class="nav-item dropdown active">
                    <a class="nav-link dropdown-toggle" href="#" id="channelsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Channels <span class="sr-only"></span></a>
                    <div class="dropdown-menu" aria-labelledby="channelsDropdown">
                    <?php $channels = \App\Venue::all() ?>
                        @foreach($channels as $channel)
                            @if($channel->active_status == "active")
                            <a class="dropdown-item" href="/channel/{{$channel->id}}">{{$channel->name}}</a>
                            @endif
                        @endforeach
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="/channels">All Channels</a>
                    </div>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="/new/live-now">Live Now <span class="sr-only"></span></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="/new/on-demand">On Demand <span class="sr-only"></span></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="/contact">Contact Us <span class="sr-only"></span></a>
                </li>

            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
                    <li><a class="nav-link" href="/signup">{{ __('Register') }}</a></li>
                @else
                    
                        <?php $user_profile = \App\UserProfile::where('user_id', Auth::user()->id)->first();?>
                        @if($user_profile)
                            @if($user_profile->profile_image)
                            <li class="nav-item">
                            <img src="{{asset('storage/userprofile_imgs/'. $user_profile->profile_image)}}" height="45" width="45" class="rounded-circle">
                            </li>
                            @endif
                        @else
                        <li class="nav-item">
                            <img src="{{asset('storage/userprofile_imgs/profile_img.svg')}}" height="45" width="45" class="rounded-circle">
                        </li>
                        @endif
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->firstname }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <?php 
                                $is_admin = \App\Admin::where('user_id', Auth::user()->id)->first();
                                $is_referee = \App\Referee::where('user_id', Auth::user()->id)->first();
                                $is_superuser = \App\SuperUser::where('user_id', Auth::user()->id)->first();
                            ?>
                            @if(!$is_referee)
                            <a class="dropdown-item" href="/">Profile</a>
                            @endif
                            @if($is_admin)
                                <a class="dropdown-item" href="/admin/dashboard">Dashboard</a>
                            @endif
                            @if($is_referee)
                                <a class="dropdown-item" href="/referee/dashboard">Dashboard</a>
                            @endif
                            @if($is_superuser)
                                <a class="dropdown-item" href="/superuser/dashboard">Dashboard</a>
                            @endif
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                    <li class="nav-item">
                    <?php $account_balance =  \App\AccountBalance::where('user_id', Auth::user()->id)->first() ?>
                        @if($account_balance)
                            <span class="nav-link">Credits ({{$account_balance->balance_value}})</scpam>
                        @else
                        <span class="nav-link">Credits (0)</spam>
                        @endif
                    </li>
                @endguest
            </ul>
        </div>
    </nav>

        @include('flash-message')
    
        @yield('content')
        
        @yield('modal')
        
    <nav class="navbar sticky-bottom navbar-dark bg-dark" style="margin-top: 30%; bottom: 0px;">
        <a class="navbar-brand" href="#"><small>PAPERCLIP SOUTH AFRICA © 2017-2018</small></a>
    </nav>
    
    <script src="{{asset('vendor/jquery-3.3.1/jquery.min.js')}}"></script>
    <script src="{{asset('vendor/bootstrap-4.0.0/assets/js/vendor/popper.min.js')}}"></script>
    <script src="{{asset('vendor/bootstrap-4.0.0/dist/js/bootstrap.min.js')}}"></script>
    <script>
        $( document ).ready(function() {
            $( ".js-item" )
            .hover(function() {
                $(this).find('.play-icon').css("display","block");
            }, function() {
                $(this).find('.play-icon').css("display","none");
            });
            // $( function() {
            //     $( "#datepicker" ).datepicker({
            //     changeMonth: true,
            //     changeYear: true,
            //     yearRange: "1902:1999",
            //     setDate: "01/01/1999",
            //     });
            // });
        });
    </script>
    @yield('scripts')
</body>
</html>
