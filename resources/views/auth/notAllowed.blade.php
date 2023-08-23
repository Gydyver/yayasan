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
        <p class="login-box-msg">Anda Tidak Memiliki Izin Untuk Mengakses Menu Ini</p>
        <div class="row">
          <div class="col-12">
            <!-- <a href="{{ URL::previous() }}">Kembali ke Halaman Sebelumnya</a> -->
            <button type="button" onClick="window.location.href='/'" style="width:100%" class="btn btn-primary btn-block" id="back_button">Kembali ke Halaman Dashboard</button>
          </div>
        </div>
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


</html>