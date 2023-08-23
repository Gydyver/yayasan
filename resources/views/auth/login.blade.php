<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Company Form - Laravel 9 CRUD</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>




<body>
    <main class="login-form">
        <div class="cotainer">
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <div class="card">
                        <h3 class="card-header text-center">Login</h3>
                        <div class="card-body">
                            <form method="POST" id="form-login" action="{{ route('login.perform') }}">
                                @csrf
                                <div class="form-group mb-3">
                                    <input type="text" placeholder="Username" id="username" class="form-control" name="username" required autofocus>
                                    @if ($errors->has('username'))
                                    <span class="text-danger">{{ $errors->first('username') }}</span>
                                    @endif
                                </div>
                                <div class="form-group mb-3">
                                    <input type="password" placeholder="Password" id="password" class="form-control" name="password" required>
                                    @if ($errors->has('password'))
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                    @endif
                                </div>
                                @if ($errors->has('username'))
                                <span class="text-danger">{{ $errors->first('username') }}</span>
                                @endif

                                <!-- @if (Session::has('error')) -->
                                <!-- <div class="alert alert-success" role="alert">
                                    <strong>Error</strong> {{ Session::get('error') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                @endif -->
                                <div class="alert alert-danger" role="alert">
                                    <strong>Error</strong> {{ Session::get('error') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="form-group mb-3">
                                    <!-- <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> Remember Me
                                    </label>
                                </div> -->
                                </div>
                                <div class="d-grid mx-auto">
                                    <button type="button" onClick="login()" class="btn btn-dark btn-block">Signin</button>
                                </div>
                                <!-- </form> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

<script>
    function login() {
        console.log('username dan password');
        console.log($('#username').val());
        console.log($('#password').val());
        // var form = '#form-login';
        // $.ajax({
        //     type: "POST",
        //     url: "/login",
        //     // The key needs to match your method's input parameter (case-sensitive).
        //     data: JSON.stringify({
        //         username: $('#username').val(),
        //         password: $('#password').val()
        //     }),
        //     contentType: "application/json; charset=utf-8",
        //     dataType: "json",
        //     success: function(data) {
        //         alert(data);
        //     },
        //     error: function(errMsg) {
        //         alert(errMsg);
        //     }
        // });
    }
    // $(document).ready(function() {

    //     var form = '#form-login';

    //     $(form).on('submit', function(event) {
    //         event.preventDefault();

    //         var url = $(this).attr('data-action');
    //         console.log('url');
    //         console.log(url);
    //         console.log('this');
    //         console.log(url);

    //         $.ajax({
    //             url: url,
    //             method: 'POST',
    //             // data: new FormData(this),
    //             data: {
    //                 username: $('#username').val(),
    //                 password: $('#password').val()
    //             },
    //             dataType: 'JSON',
    //             contentType: false,
    //             cache: false,
    //             processData: false,
    //             success: function(response) {
    //                 $(form).trigger("reset");
    //                 alert(response.success)
    //             },
    //             error: function(response) {}
    //         });
    //     });

    // });
</script>

</html>