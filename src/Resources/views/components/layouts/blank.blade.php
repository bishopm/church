<!DOCTYPE html>
<html lang="en">

<head>
  <title>{{setting('general.church_name')}} - {{$pageName}}</title>
  <meta name="description" content="">
  <meta name="keywords" content="">
  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">

  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
  <link rel="stylesheet" href="/public/church/css/leaflet.css">
  <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.3/dist/{{setting('website.theme')}}/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="/public/church/css/custom.css">
</head>
<body style="background-color: #000000; padding-top:0px;">
  <main>  
    {{$slot}}
  </main>
  <script src="/public/church/js/bootstrap-bundle.min.js"></script>
  <script src="/public/church/js/custom.js"></script>
</body>

</html>