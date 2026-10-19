<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>

  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="author" content="SemiColonWeb" />

  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600&display=swap" rel="stylesheet" />

  <title>WMS AJC - Login</title>

  {{-- Tampilan disamakan dengan halaman login newSML (resources/views/welcome.blade.php).
       Logika login ajcwms tetap: cek user online (checkOnline) saat submit, pesan error
       "1" / "2", dan updateIdle saat halaman dibuka. --}}
  <link rel="stylesheet" href="{!! URL::asset('public/css/loginpage.css') !!}">
  <link rel="stylesheet" href="{{ URL::asset('public/css/alertify.css') }}">

</head>

<body class='background-login'>
  <div class="login-wrapper">

    <!-- Left: Branding Panel -->
    <div class="left-panel">
      <div class="brand-icon">
        <svg viewBox="0 0 24 24">
          <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
          <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
        </svg>
      </div>
      <p class="brand-name">AJC</p>
      <p class="brand-subtitle">WMS</p>
    </div>

    <!-- Right: Login Form -->
    <div class="right-panel">
      <p class="form-title">Login</p>
      <p class="form-subtitle">Masukan Username dan Password</p>

      <form action="{{ url('checkLogin') }}" method="post" onsubmit="return checkOnline()">

        <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />

        <div class="form-group">
          <label for="username">Username</label>
          <div class="input-wrapper">
            <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <input type="text" id="username" name="username" placeholder="Username" required autofocus autocomplete="off" />
          </div>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <div class="input-wrapper">
            <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            <input type="password" id="password" name="password" placeholder="Password" />
          </div>
        </div>

        <button type="submit" class="btn-login" id="login-form-submit" name="login-form-submit" value="login">Sign in</button>
      </form>

      <p class="form-footer">AnekaJC &copy; {{ date('Y') }} &mdash; All rights reserved</p>
    </div>

  </div>

  <script src="{!! URL::asset('public/js/canvas/jquery.js') !!}"></script>
  <script src="{{ URL::asset('public/js/alertify.js') }}"></script>

  <script type="text/javascript">
    $(document).ready(function(){
      @if($errors->any())
        if(JSON.parse({!! $errors !!}) == "1") {
          alertify.alert('Login gagal!', 'Username dan Password tidak cocok.', function(){ });
        }
        if(JSON.parse({!! $errors !!}) == "2") {
          alertify.alert('Login gagal!', 'Username sudah di delete', function(){ });
        }
      @endif

      $.ajax({
        url     : "{{ url('updateIdle') }}",
        type    : "GET",
        async   : false
      });
    });

    function checkOnline() {
      var _token = $("#_token").val();
      var _username = $("#username").val();
      var check = 0;
      $.ajax({
        url     : "{{ url('checkOnline') }}",
        type    : "POST",
        async   : false,
        data    : {
          _token : _token,
          username : _username
        },
        success : function(result) {
          check = result;
        }
      });
      if (check == 0) { return true; }
      else { alertify.alert('Login gagal!', 'User sudah login.', function(){ }); return false; }
    }
  </script>

</body>
</html>
