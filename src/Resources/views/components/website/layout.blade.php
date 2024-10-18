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
  <link rel="stylesheet" href="/public/church/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="/public/church/css/custom.css">
  @laravelPWA
</head>

<body>
  <div class="">
    <nav class="navbar navbar-expand-lg bg-dark" data-bs-theme="dark">
      <div class="container-fluid">
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
            @if (!count($member))
              <li>
                <a class="nav-link" href="{{url('/')}}/login"></a>Login
              </li>
            @else
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Members</a>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="{{url('/')}}/mymenu">My menu</a>
                  <a class="dropdown-item" href="{{url('/')}}/books">Books</a>
                </div>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Resources</a>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="{{url('/')}}/blog">Blog archive</a>
                  <a class="dropdown-item" href="{{url('/')}}/sermons">Sermon archives</a>
                </div>
              </li>
            @endif          
          </ul>
          <form class="d-flex">
            <input class="form-control me-sm-2" type="search" placeholder="Search">
            <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
          </form>
        </div>
      </div>
    </nav>
  </div>
  <main class="main">
    @if($pageName<>"Home")
    <div class="container my-3">
    @else
    <div>
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

  <footer id="footer" class="footer light-background">
    <div class="container">
      <div class="row g-4">
        <div class="col-md-4 mb-md-0">
          <div class="widget text-start">
            @if (count($member))
              <h3 class="widget-heading">User details</h3>
            @else
              <h3 class="widget-heading">Login</h3>
            @endif
            <p class="mb-4">
              @if (count($member))
                Logged in as {{$member['fullname']}}
              @else
                Log in using your cellphone number and get access to member content
              @endif
            </p>
            <p class="mb-0">
              @if (count($member))
                <a href="{{url('/')}}/logout" class="btn-learn-more">Logout</a>
              @else 
                <a href="{{url('/')}}/login" class="btn-learn-more">Login</a>
              @endif
            </p>
          </div>
        </div>
        <div class="col-md-4 mb-md-0">
          <div class="widget">
            <div class="footer-subscribe">
              <h3 class="widget-heading">Subscribe to Staying Connected</h3>
              <form action="forms/newsletter.php" method="post">
                <div class="mb-2">
                  <input type="text" class="form-control" name="email" placeholder="Enter your email">

                  <button type="submit" class="btn btn-link">
                    <span class="bi bi-arrow-right"></span>
                  </button>
                </div>
                <div class="loading">Loading</div>
                <div class="error-message"></div>
                <div class="sent-message">
                  Your subscription request has been sent. Thank you!
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-md-0">
          <div class="widget">
            <h3 class="widget-heading">Connect</h3>
            <ul class="list-unstyled social-icons light mb-3">
              <li>
                <a target="_blank" title="Facebook page" href="{{setting('website.facebook_page')}}"><span class="bi bi-facebook"></span></a>
              </li>
              <li>
                <a target="_blank" title="Instagram page" href="{{setting('website.instagram_page')}}"><span class="bi bi-instagram"></span></a>
              </li>
              <li>
                <a target="_blank" title="YouTube channel" href="{{setting('website.youtube_channel')}}"><span class="bi bi-youtube"></span></a>
              </li>
              <li>
                <a target="_blank" title="Youversion page" href="{{setting('website.youversion_page')}}"><span class="bi bi-bookmark-plus"></span></a>
              </li>
              <li>
                <a target="_blank" title="WhatsApp" href="https://wa.me/27{{substr(setting('communication.whatsapp'),1)}}"><span class="bi bi-whatsapp"></span></a>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <div class="copyright d-flex flex-column flex-md-row align-items-center justify-content-md-between">
        <p>© Westville Methodist Church {{date('Y')}}</p>
        <div class="credits">
        </div>
      </div>
    </div>
  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>
  <!-- Vendor JS Files -->
  <script src="/public/church/js/bootstrap-bundle.min.js"></script>
  <script src="/public/church/js/custom.js"></script>
</body>

</html>