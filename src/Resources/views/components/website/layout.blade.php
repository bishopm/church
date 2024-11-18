<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" user-scalable="no" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{setting('general.church_name')}} - {{$pageName}}</title>
  <meta name="description" content="">
  <meta name="keywords" content="">
  <!-- Favicons -->
  <link href="/public/church/images/icons/favicon.png" rel="icon">
  <link href="/public/church/images/icons/apple-touch-icon.png" rel="apple-touch-icon">
  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
  <!-- Media player -->  
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
  <link rel="stylesheet" href="/public/church/css/leaflet.css">
  <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.3/dist/{{setting('website.theme')}}/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="/public/church/css/custom.css">
    <!-- PWA -->
    <link rel="manifest" href="/public/manifest.json" crossorigin="use-credentials" />
    <!-- Chrome for Android theme color -->
    <meta name="theme-color" content="#159CA0">
    
    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="application-name" content="WMC">
    <link rel="icon" sizes="512x512" href="/public/church/images/icons/icon-512x512.png">
    
    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="WMC">
    <link rel="apple-touch-icon" href="/public/church/images/icons/icon-512x512.png">
    
    
    <link href="/public/church/images/icons/splash-640x1136.png" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
    <link href="/public/church/images/icons/splash-750x1334.png" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
    <link href="/public/church/images/icons/splash-1242x2208.png" media="(device-width: 621px) and (device-height: 1104px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image" />
    <link href="/public/church/images/icons/splash-1125x2436.png" media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image" />
    <link href="/public/church/images/icons/splash-828x1792.png" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
    <link href="/public/church/images/icons/splash-1242x2688.png" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image" />
    <link href="/public/church/images/icons/splash-1536x2048.png" media="(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
    <link href="/public/church/images/icons/splash-1668x2224.png" media="(device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
    <link href="/public/church/images/icons/splash-1668x2388.png" media="(device-width: 834px) and (device-height: 1194px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
    <link href="/public/church/images/icons/splash-2048x2732.png" media="(device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
    
    <!-- Tile for Win8 -->
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/public/church/images/icons/icon-512x512.png">
    
    <script type="text/javascript">
        // Initialize the service worker
        if ('serviceWorker' in navigator) {
          navigator.serviceWorker.register('/public/serviceworker.js', {
              scope: '/public/'
          }).then(function (registration) {
              // Registration was successful
              console.log('ServiceWorker registration successful with scope: ', registration.scope);
          }, function (err) {
              // registration failed :(
              console.log('ServiceWorker registration failed: ', err);
          });
        }
    </script>
</head>

<body>
  <div>
    <nav class="navbar navbar-expand-lg bg-dark fixed-top" data-bs-theme="dark">
      <div id="container" class="container-fluid">
        <a href="{{url('/')}}" class="navbar-brand">
          <!-- Uncomment the line below if you also wish to use an image logo -->
          <!-- <img src="/public/church/img/logo.png" alt=""> -->
          <h1 class="sitename">{{setting('general.church_abbreviation')}}</h1>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarColor02">
          <ul class="navbar-nav me-auto">
            <li class="nav-item">
              <a class="nav-link active" href="{{url('/')}}#about">Home
                <span class="visually-hidden">(current)</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{url('/')}}#sundays">Sundays</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{url('/')}}#blog">Blog</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{url('/')}}#connecting">Connecting</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{url('/')}}#serving">Getting involved</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{url('/')}}#contact">Contact</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Resources</a>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="{{url('/')}}/blog">Blog archive</a>
                <a class="dropdown-item" href="{{url('/')}}/sermons">Sermon archives</a>
              </div>
            </li>          
          </ul>
          <ul class="navbar-nav ms-auto">
            @if (!count($member))
              <li class="nav-item">
                <a class="nav-link" href="{{url('/')}}/app">Login</a>
              </li>
            @else
              <li class="nav-item">
                <a class="nav-link" href="{{url('/')}}/app">Go to app</a>
              </li>
            @endif
          </ul>
        </div>
      </div>
    </nav>
  </div>
  <main class="main">
    @if($pageName<>"Home")
      <div class="container" style="padding-bottom:10px;padding-top:50px;">
    @else
      <div style="padding-bottom:10px;">
    @endif
      @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">  
          <p>{{ session('message') }}</p>          
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
      {{$slot}}
    </div>
  </main>

  <footer class="bg-dark text-center text-white p-3">&copy;{{date('Y')}} Westville Methodist Church</footer>

  <!-- Vendor JS Files -->
  <script src="/public/church/js/bootstrap-bundle.min.js"></script>
  <script src="/public/church/js/custom.js"></script>
</body>

</html>