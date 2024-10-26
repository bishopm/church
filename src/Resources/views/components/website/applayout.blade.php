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

<body style="background-color: lightblue;">
  <nav class="navbar navbar-expand-lg bg-dark fixed-top" data-bs-theme="dark">
    <div id="container" class="container-fluid">
      <a href="{{url('/app')}}" class="navbar-brand">
        <i class="bi bi-house-fill text-white" style="font-size: 1.5rem;"></i>
      </a>
      <h2 class="text-white pt-2">{{setting('general.church_abbreviation')}}</h2>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarColor02">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link active" style="padding-left:7px;" href="{{url('/app')}}">Home</a>
            <a class="nav-link active" style="padding-left:7px;" href="{{url('/')}}">WMC website</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <main class="main" style="padding-top:20px; padding-left:10px; padding-right:10px;">
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