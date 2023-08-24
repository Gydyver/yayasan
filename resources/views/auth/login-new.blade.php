<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Log in (v2)</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('templates/AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{asset('templates/AdminLTE-3.2.0/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('templates/AdminLTE-3.2.0/dist/css/adminlte.min.css')}}">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <a href="{{asset('templates/AdminLTE-3.2.0/index2.html')}}" class="h1"><b>RQ </b>Al-Muklisin</a>
      </div>
      <div class="card-body">
        <p class="login-box-msg">Sign in to start your session</p>

        <form action="{{ route('login.perform') }}" id="form-login" method="POST">
          <div class="input-group mb-3">
            <input type="text" name="username" id="username" onkeydown="return /[a-z]/i.test(event.key)" class="form-control" placeholder="Username">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          @csrf
          @if ($errors->has('username'))
          <span class="text-danger">{{ $errors->first('username') }}</span>
          @endif
          <div class="input-group mb-3">
            <input type="password" name="password" id="password" onkeydown="return /[a-z]/i.test(event.key)" class="form-control" placeholder="Password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          @if ($errors->has('password'))
          <span class="text-danger">{{ $errors->first('password') }}</span>
          @endif
          <!--  -->
          <div class="alert alert-danger alert-usernotfound" style="display:none;" role="alert">
            <strong>Error</strong><br>
            These credentials do not match our records
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>



          <div class="row">
            <!-- <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div> -->
            <!-- /.col -->
            <div class="col-12">
              <button type="submit" style="width:100%" class="btn btn-primary btn-block btn-submit submit-form" id="login_button">Sign In</button>
            </div>
            <!-- /.col -->
          </div>
        </form>

        <!-- <p class="mb-0">
        <a href="register.html" class="text-center">Register a new membership</a>
      </p> -->
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
  <!-- /.login-box -->

  <!-- jQuery -->
  <script src="{{asset('templates/AdminLTE-3.2.0/plugins/jquery/jquery.min.js')}}"></script>
  <!-- Bootstrap 4 -->
  <script src="{{asset('templates/AdminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <!-- AdminLTE App -->
  <script src="{{asset('templates/AdminLTE-3.2.0/dist/js/adminlte.min.js')}}"></script>
  <script src="{{asset('js/jquery.md5.min.js')}}"></script>

</body>
<script type="text/javascript">
  $(".submit-form").click(function(e) {
    e.preventDefault();
    // var data = $('#form-login').serialize();
    var username = $('#username').val()
    var password = $('#password').val()
    $(".alert-usernotfound").hide()
    $.ajax({
      type: 'post',
      url: "/login_perform",
      data: {
        username: username,
        password: password,
        '_token': $('meta[name="csrf-token"]').attr('content')
      },
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      beforeSend: function() {
        $('#login_button').html('....Please wait');
      },
      success: function(response) {
        if (response == false) {
          $('.alert-usernotfound').show();
        } else {
          window.location.href = "{{URL::to('/')}}"
        }

      },
      complete: function(response) {
        // $('#login_button').html('Create New');
      }
    });
  });
</script>

</html>