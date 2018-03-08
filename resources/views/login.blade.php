<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Materialize Bootstrap Admin panel</title>
    <!-- Favicon and touch icons -->
    <link rel="shortcut icon" href="assets/img/ico/favicon.png">
    <!-- Start Global Mandatory Style
         =====================================================================-->
    <!-- Material Icons CSS -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{asset('assets/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Animation Css -->
    <link href="{{asset('assets/plugins/animate/animate.css')}}" rel="stylesheet" />
    <!-- materialize css -->
    <link href="{{asset('assets/plugins/materialize/css/materialize.min.css')}}" rel="stylesheet">
    <!-- custom CSS -->
    <link href="{{asset('assets/dist/css/stylematerial.css')}}" rel="stylesheet">
</head>

<body class="sign-section">
    <div class="container sign-cont animated zoomIn">
        <div class="row sign-row">
            <div class="sign-title">
                <h2 class="teal-text"><i class="fa fa-user-circle-o"></i></h2>
                <h2 class="teal-text">Prijava</h2>
            </div>
           <form role="form" action="{{ url('/login') }}" method="POST" class="col s12" id="reg-form">
            {!! csrf_field() !!}
                <div class="row sign-row">
                    <div class="input-field col s12">
                        <input id="u_name" type="text" name="email" class="validate" required>
                        <label for="u_name">Email</label>
                    </div>
                </div>
                <div class="row sign-row">
                    <div class="input-field col s12">
                        <input id="password" type="password" name="password" class="validate" required>
                        <label for="password">Šifra</label>
                    </div>
                </div>
                <div class="row sign-row">
                    <div class="input-field col s6">
                        <div class="sign-confirm">
                            <input type="checkbox" id="sign-confirm" />
                            <label for="sign-confirm">Zapamti me!</label>
                        </div>
                    </div>
                    <div class="input-field col s6">
                        <button class="btn btn-large btn-register waves-effect waves-light green" type="submit" name="action">Prijava
                            <i class="material-icons right">done_all</i>
                        </button>
                    </div>
                </div>
            </form>

        </div>
        <a title="Login" class="sign-btn btn-floating btn-large waves-effect waves-light green">
            <i class="material-icons">perm_identity</i></a>
    </div>

    <!-- Start Core Plugins
         =====================================================================-->
    <!-- jQuery -->
    <script src="{{asset('assets/plugins/jQuery/jquery-3.2.1.min.js')}}" type="text/javascript"></script>
    <!-- materialize  -->
    <script src="{{asset('assets/plugins/materialize/js/materialize.min.js')}}" type="text/javascript"></script>
    <!-- End Core Plugins
         =====================================================================-->
    <script>
        $(document).ready(function() {
            $('select').material_select();
        });
    </script>
</body>

</html>