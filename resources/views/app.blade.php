<!DOCTYPE html>
<html lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Materialize Bootstrap Admin panel</title>
    <!-- Favicon and touch icons -->
    <link rel="shortcut icon" href="assets/dist/img/ico/fav.png">
    <!-- Start Global Mandatory Style
         =====================================================================-->
    <!-- jquery-ui css -->
    <link href="{{asset('assets/plugins/jquery-ui-1.12.1/jquery-ui.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- materialize css -->
    <link href="{{asset('assets/plugins/materialize/css/materialize.min.css')}}" rel="stylesheet">
    <!-- Bootstrap css-->
    <link href="{{asset('assets/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Animation Css -->
    <link href="{{asset('assets/plugins/animate/animate.css')}}" rel="stylesheet" />
    <!-- Material Icons CSS -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{asset('assets/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Monthly css -->
    <link href="{{asset('assets/plugins/monthly/monthly.css')}}" rel="stylesheet" type="text/css" />
    <!-- simplebar scroll css -->
    <link href="{{asset('assets/plugins/simplebar/dist/simplebar.css')}}" rel="stylesheet" type="text/css" />
    <!-- mCustomScrollbar css -->
    <link href="{{asset('assets/plugins/malihu-custom-scrollbar/jquery.mCustomScrollbar.css')}}" rel="stylesheet" type="text/css" />
    <!-- custom CSS -->
    <link href="{{asset('assets/dist/css/stylematerial.css')}}" rel="stylesheet">
    <link href="{{ asset('css/selectize.css') }}" rel="stylesheet">
    <link href="{{asset('css/custom.css')}}" rel="stylesheet">
</head>

<style>
    .fa-external-link-square{
        cursor: pointer;
    }
    .fa-check{
        cursor: pointer;
    }
    .fa-times{
        cursor: pointer;
    }
</style>
<body>
<div id="wrapper">
    <!--navbar top-->
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <!-- Logo -->
        <a class="navbar-brand pull-left" href="kontrolna-ploča.html">
            <img src="{{asset('assets/dist/img/logo3.png')}}" alt="logo" width="205" height="60">
        </a>
        <a id="menu-toggle">
            <i class="material-icons">apps</i>
        </a>
        <div class="navbar-custom-menu hidden-xs">
            <ul class="navbar navbar-right">
                <!--Notification-->
                <li class="dropdown">
                    <!--user profile-->
                <li class="dropdown">
                    <a class='dropdown-button user-pro' href='#' data-activates='dropdown-user'>
                        <img src="{{asset('assets/dist/img/avatar5.png')}}" class="img-circle" height="45" width="50" alt="User Image">
                    </a>
                    <ul id='dropdown-user' class='dropdown-content'>
                        <li>
                            <a href="#!"><i class="material-icons">lock</i> Odjava</a>
                        </li>
                    </ul>
                </li>
                <!-- /.user profile -->
            </ul>
        </div>
    </nav>
    <!-- Sidebar -->
    <div id="sidebar-wrapper" class="waves-effect" data-simplebar>
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse">
                <ul class="nav" id="side-menu">
                    <li class="list-header"></li>
                    <li class="active-link"><a href="{{url('/dashboard')}}"><i class="material-icons">dashboard</i>Kontrolna Ploča</a></li>
                    <li class="list-divider"></li>
                    <li class="list-header">Menu ---</li>
                    <li>
                        <a><i class="fa fa-users"></i>Korisnici<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="{{url('/users')}}">Pregled svih korisnika</a></li>
                        </ul>
                    </li>
                    <li>
                        <a><i class="fa fa-soccer-ball-o"></i>Sportisti<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="{{url('/sportisti')}}">Pregled svih sportista</a></li>
                            <li><a href="{{url('/unauth/sportisti')}}">Pregled neodobrenih sportista</a></li>
                        </ul>
                    </li>
                    <li>
                        <a><i class="fa fa-building"></i>Objekti<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="{{url('/objekti')}}">Pregled svih objekata</a></li>
                            <li><a href="{{url('/unauth/objekti')}}">Pregled neodobrenih objekata</a></li>
                        </ul>
                    </li>
                    <li>
                        <a><i class="fa fa-id-card"></i>Klubovi<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="{{url('/klubovi')}}">Pregled svih klubova</a></li>
                            <li><a href="{{url('/unauth/klubovi')}}">Pregled neodobrenih klubova</a></li>
                        </ul>
                    </li>
                    <li>
                        <a><i class="fa fa-university"></i>Škole sporta<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="{{url('/skole')}}">Pregled svih škola</a></li>
                            <li><a href="{{url('/unauth/skole')}}">Pregled neodobrenih škola</a></li>
                        </ul>
                    </li>
                    <li>
                        <a><i class="fa fa-user-secret"></i>Stručni kadrovi<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="{{url('/kadrovi')}}">Pregled svih kadrova</a></li>
                            <li><a href="{{url('/unauth/kadrovi')}}">Pregled neodobrenih kadrova</a></li>
                        </ul>
                    </li>
                    <li>
                        <a><i class="fa fa-newspaper-o"></i>Vijesti<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="{{url('/news')}}">Pregled svih vijesti</a></li>
                            <li><a href="{{url('/unauth/news')}}">Pregled neodobrenih vijesti</a></li>
                        </ul>
                    </li>
                    <li>
                        <a><i class="fa fa-comment"></i>Prijave<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="{{url('/prijave')}}">Pregled svih prijava</a></li>
                            <li><a href="{{url('/unauth/prijave')}}">Pregled nerješenih prijava</a></li>
                        </ul>
                    </li>
                    <li class="side-last"></li>
                </ul>
                <!-- ./sidebar-nav -->
            </div>
            <!-- ./sidebar -->
        </div>
        <!-- ./sidebar-nav -->
    </div>
    <!-- ./sidebar-wrapper -->
    <!-- Page content -->
    <div id="page-content-wrapper">
        @yield('content')
    </div>
    <!-- ./page-wrapper -->
</div>
<!-- ./page-wrapper -->
<!-- Preloader -->
<div id="preloader">
    <div class="preloader-position">
        <div class="preloader-wrapper big active">
            <div class="spinner-layer spinner-teal">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div>
                <div class="gap-patch">
                    <div class="circle"></div>
                </div>
                <div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- End Preloader -->

<!-- Start Core Plugins
     =====================================================================-->
<!-- jQuery -->
<script src="{{asset('assets/plugins/jQuery/jquery-3.2.1.min.js')}}" type="text/javascript"></script>
<!-- jquery-ui -->
<script src="{{asset('assets/plugins/jquery-ui-1.12.1/jquery-ui.min.js')}}" type="text/javascript"></script>
<!-- Bootstrap -->
<script src="{{asset('assets/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
<!-- materialize  -->
<script src="{{asset('assets/plugins/materialize/js/materialize.min.js')}}" type="text/javascript"></script>
<!-- metismenu-master -->
<script src="{{asset('assets/plugins/metismenu-master/dist/metisMenu.min.js')}}" type="text/javascript"></script>
<!-- SlimScroll -->
<script src="{{asset('assets/plugins/slimScroll/jquery.slimscroll.min.js')}}" type="text/javascript"></script>
<!-- m-custom-scrollbar -->
<script src="{{asset('assets/plugins/malihu-custom-scrollbar/jquery.mCustomScrollbar.concat.min.js')}}" type="text/javascript"></script>
<!-- scroll -->
<script src="{{asset('assets/plugins/simplebar/dist/simplebar.js')}}" type="text/javascript"></script>
<!-- custom js -->
<script src="{{asset('assets/dist/js/custom.js')}}" type="text/javascript"></script>

<!-- dataTables js -->
<script src="{{asset('assets/plugins/datatables/dataTables.min.js')}}" type="text/javascript"></script>
<!-- End Core Plugins
     =====================================================================-->
<!-- Start Page Lavel Plugins
     =====================================================================-->
<!-- Sparkline js -->
<script src="{{asset('assets/plugins/sparkline/sparkline.min.js')}}" type="text/javascript"></script>
<!-- Counter js -->
<script src="{{asset('assets/plugins/counterup/jquery.counterup.min.js')}}" type="text/javascript"></script>
<!-- Flot Charts js -->
<script src="{{asset('assets/plugins/flot/jquery.flot.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/plugins/flot/jquery.flot.pie.min.js')}}" type="text/javascript"></script>
<!-- End Page Lavel Plugins-->

<script src="{{asset('js/vendor/validation/jquery.validate.min.js')}}" type="text/javascript"></script>
<script src="{{asset('js/vendor/validation/additional-methods.min.js')}}" type="text/javascript"></script>
<!-- End Page Lavel Plugins
     =====================================================================-->
<!-- Start Theme label Script
     =====================================================================-->
<!-- main js-->
<script type="text/javascript" src={{asset('js/validate.js')}}></script>
<script type="text/javascript" src="http://cdn.jsdelivr.net/jquery.validation/1.13.1/additional-methods.js"></script>
<script src="{{asset('assets/dist/js/main.js')}}" type="text/javascript"></script>
<script src="{{ asset('js/selectize.js') }}"></script>
<script src="{{ asset('js/xregexp-all.js') }}"></script>
<script src="https://cdn.ckeditor.com/4.7.3/standard/ckeditor.js"></script>
<script src="{{asset('js/custom.js')}}" type="text/javascript"></script>
<!-- End Theme label Script
     =====================================================================-->
<script>
    // Start of jquery datatable
    $('#dataTableExample1').DataTable({
        "dom": "<'row'<'col-sm-6'l><'col-sm-6'f>>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
        "lengthMenu": [
            [6, 25, 50, -1],
            [6, 25, 50, "All"]
        ],
        "iDisplayLength": 6
    });
</script>
</body>
</html>