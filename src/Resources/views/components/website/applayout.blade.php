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
  <link rel="stylesheet" href="https://cdn.vidstack.io/player/theme.css" />
  <link rel="stylesheet" href="https://cdn.vidstack.io/player/audio.css" />
  <script src="https://cdn.vidstack.io/player" type="module"></script>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
  <link rel="stylesheet" href="/public/church/css/leaflet.css">
  <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.3/dist/{{setting('website.theme')}}/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="/public/church/css/custom.css">
  @laravelPWA
</head>

<body style="background-color: #f6f6f6;">
  <nav class="navbar navbar-expand-lg bg-dark fixed-top" data-bs-theme="dark">
    <div id="container" class="container-fluid">
      <button class="navbar-toggler" style="padding:0; border:0;" type="button" data-bs-toggle="collapse" data-bs-target="#appMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <h2 class="text-white ms-auto pt-2"><a href="{{url('/app')}}"><i class="bi bi-house text-white" style="font-size:1.5rem;" ></i>{{setting('general.church_abbreviation')}}</a></h2>      
      <div class="collapse navbar-collapse" id="appMenu">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link active" href="{{url('/app')}}">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{url('/books/app')}}">Books</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{url('/app/devotionals')}}">Devotionals</a>
          </li>
          @if (count($member))
            <li class="nav-item">
              <a class="nav-link" href="{{url('/app/songs')}}">Songs</a>
            </li>
          @endif
          <li class="nav-item">
            <a class="nav-link" href="{{url('/')}}">WMC website</a>
          </li>
          <div class="dropdown-divider" style="color:grey; border:solid 1px;"></div>
          @if (count($member))
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{$member['firstname']}}'s menu
              </a>
              <div class="dropdown-menu mb-2" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{url('/app/practices')}}">WMC practices</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{url('/app/details')}}">Personal details</a>
              </div>
            </li>
          @else
          <li class="nav-item">
            <a class="nav-link" href="{{url('/login/app')}}">Login</a>
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
      <i class="bi bi-book-fill"></i>
      <i class="bi bi-mic"></i>
      <i class="bi bi-calendar-week"></i>
    </div>
    <div class="col-1"></div>
  </div>
  <script src="/public/church/js/bootstrap-bundle.min.js"></script>
  <script src="/public/church/js/custom.js"></script>
</body>

</html>