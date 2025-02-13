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
        navigator.serviceWorker.register('/public/serviceworker.js?version={{setting("general.app_version")}}', {
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
<body style="background-color: #f6f6f6;">
  <nav class="navbar navbar-expand-lg bg-dark fixed-top" data-bs-theme="dark">
    <div id="container" class="container-fluid">
      <button class="navbar-toggler" style="padding:0; border:0;" type="button" data-bs-toggle="collapse" data-bs-target="#appMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <h2 class="text-white ms-auto pt-2"><a href="{{route('app.home')}}"><i class="bi bi-house text-white" style="font-size:1.5rem;" ></i>{{setting('general.church_abbreviation')}}</a></h2>      
      <div class="collapse navbar-collapse" id="appMenu">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link active" href="{{route('app.home')}}">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{route('app.blog')}}">Blog</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{route('app.books')}}">Books</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{route('app.calendar')}}">Calendar</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{route('app.events')}}">Coming up at {{setting('general.church_abbreviation')}}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{route('app.courses')}}">Courses</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{route('app.devotionals')}}">Devotionals</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{route('app.sermons')}}">Sermons</a>
          </li>
          @if (count($member))
            <li class="nav-item">
              <a class="nav-link" href="{{route('app.songs')}}">Songs</a>
            </li>
          @endif
          <li class="nav-item">
            <a class="nav-link" href="{{route('web.home')}}">WMC website</a>
          </li>
          <div class="dropdown-divider" style="color:grey; border:solid 1px;"></div>
          @if (count($member))
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{$member['firstname']}}'s menu
              </a>
              <div class="dropdown-menu mb-2" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{route('app.details')}}">Personal details</a>
                <a class="dropdown-item" href="{{route('app.practices')}}">Discipleship</a>
                @if (isset($member['pastor_id']))
                  <a class="dropdown-item" href="{{route('app.pastoral')}}">Pastoral care</a>
                @endif
              </div>
            </li>
          @else
          <li class="nav-item">
            <a class="nav-link" href="{{route('app.login')}}">Login</a>
          </li>
          @endif
        </ul>
      <div>
    </div>
  </nav>
  <main class="main" style="padding-top:20px; padding-left:10px; padding-right:10px; padding-bottom:90px;">  
    @if (session()->has('message'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">  
        <p>{{ session('message') }}</p>          
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif
    {{$slot}}
  </main>
  <div class="row fixed-bottom bg-dark text-white">
    <div class="col-1"></div>
    <div class="col-10 text-center py-3 d-flex justify-content-between">
      <a href="{{route('app.devotionals')}}"><i class="bi bi-book-fill text-white"></i></a>
      <a href="{{route('app.sermons')}}"><i class="bi bi-mic text-white"></i></a>
      <a href="{{route('app.calendar')}}"><i class="bi bi-calendar-week text-white"></i></a>
    </div>
    <div class="col-1"></div>
  </div>
  <script src="/public/church/js/bootstrap-bundle.min.js"></script>
  <script src="/public/church/js/custom.js"></script>
</body>

</html>