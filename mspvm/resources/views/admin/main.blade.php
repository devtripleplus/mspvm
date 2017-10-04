<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
    <meta name="author" content="Coderthemes">

    <title>{{$title}} - MSPVM</title>

    <!-- Base Css Files -->
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet" />

    <!-- Font Icons -->
    <link href="{{asset('assets/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" />

    <!-- animate css -->
    <link href="{{asset('css/animate.css')}}" rel="stylesheet" />

    <!-- Custom Files -->
    <link href="{{asset('css/helper.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('css/style.css')}}" rel="stylesheet" type="text/css" />

    <link href="{{asset('assets/notifications/notification.css')}}" rel="stylesheet" type="text/css" />


    <!-- Datatables -->
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <script src="{{asset('js/modernizr.min.js')}}"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">


    @yield('head')
</head>
<?php 
use \App\Http\Controllers\AdminSettingsController;
$logo = AdminSettingsController::getLogo();
if(empty($logo)){
 $siteLogo = "logo.png";
}
else{
    $siteLogo = $logo[0]->setting_value;
}
?>


<body class="fixed-left">

<!-- Begin page -->
<div id="wrapper">

    <!-- Top Bar Start -->
    <div class="topbar">
        <!-- LOGO -->
        <div class="topbar-left">
            <a href="{{route('admin.home')}}" class="logo"><span><img width="210px" height="70px" src="/images/{{$siteLogo}}"> </span></a>
        </div>
        <!-- Button mobile view to collapse sidebar menu -->
        <div class="navbar navbar-default" role="navigation">
            <div class="container">
                <div class="">
                    <div class="pull-left">
                        <button class="button-menu-mobile open-left">
                            <i class="fa fa-bars"></i>
                        </button>
                        <span class="clearfix"></span>
                    </div>
                </div>
                <!--/.nav-collapse -->
            </div>
        </div>
    </div>
    <!-- Top Bar End -->


    <!-- ========== Left Sidebar Start ========== -->

    <div class="left side-menu">
        <div class="sidebar-inner slimscrollleft">
            <div class="user-details">
                <div class="user-info">
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">{{$user->username}} <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="javascript:void(0)"><i class="md md-face-unlock"></i> Profile<div class="ripple-wrapper"></div></a></li>
                            <li><a href="javascript:void(0)"><i class="md md-settings"></i> Settings</a></li>
                            <li><a href="javascript:void(0)"><i class="md md-lock"></i> Lock screen</a></li>
                            <li><a href="/logout"><i class="md md-settings-power"></i> Logout</a></li>
                        </ul>
                    </div>

                    <p class="text-muted m-0">Administrator</p>
                </div>
            </div>
            <!--- Divider -->
            <div id="sidebar-menu">
                <ul>
                    <li>
                        <a href="{{route('admin.home')}}"><i class="fa fa-home"></i> <span>Home</span></a>
                    </li>
                    <li class="has_sub">
                        <a href="#"><i class="fa fa-server"></i> <span>Servers</span></a>

                        <ul class="list-unstyled">
                            <li>
                                <a href="{{route('admin.servers')}}">Manage</a>
                            </li>
                            <li>
                                <a href="{{route('admin.server-create')}}">New Server</a>
                            </li>
                        </ul>
                    </li>
                    <li class="has_sub">
                        <a href="#"><i class="fa fa-cubes"></i> <span>Packages</span></a>

                        <ul class="list-unstyled">
                            <li>
                                <a href="{{route('admin.packages')}}">Manage</a>
                            </li>
                            <li>
                                <a href="{{route('admin.package-create')}}">New Package</a>
                            </li>
                        </ul>
                    </li>
                    <li class="has_sub">
                        <a href="#"><i class="fa fa-terminal"></i> <span>VMs</span></a>

                        <ul class="list-unstyled">
                            <li>
                                <a href="{{route('admin.vms')}}">Manage</a>
                            </li>
                            <li>
                                <a href="{{route('admin.vm-create')}}">New VM</a>
                            </li>
                        </ul>
                    </li>
                    <li class="has_sub">
                        <a href="#"><i class="fa fa-users"></i> <span>Users</span></a>

                        <ul class="list-unstyled">
                            <li>
                                <a href="{{route('admin.users')}}">Manage</a>
                            </li>
                            <li>
                                <a href="{{route('admin.users', ['type' => 3])}}">Admins</a>
                            </li>
                            <li>
                                <a href="{{route('admin.user-create')}}">New User</a>
                            </li>
                        </ul>
                    </li>
                    <li class="has_sub">
                        <a href="#"><i class="fa fa-cogs"></i> <span>Settings</span></a>

                        <ul class="list-unstyled">
                            <li>
                                <a href="{{route('admin.settings-general')}}">General</a>
                            </li>
                            <li>
                                <a href="{{route('admin.settings-network')}}">Network</a>
                            </li>
                            <li>
                                <a href="{{route('admin.settings-security')}}">Security</a>
                            </li>
                            <li>
                                <a href="{{route('admin.settings-email')}}">Email</a>
                            </li>
                            <li>
                                <a href="{{route('admin.settings-maintenance')}}">Maintenance</a>
                            </li>
                            <li>
                                <a href="{{route('admin.services')}}">Services (needs work?)</a>
                            </li>
                        </ul>
                    </li>
                    <li class="has_sub">
                        <a href="#"><i class="fa fa-cubes"></i> <span>Templates</span></a>

                        <ul class="list-unstyled">
                            <li>
                                <a href="{{route('admin.templates')}}">Manage</a>
                            </li>
                            <li>
                                <a href="{{route('admin.template-create')}}">New Template</a>
                            </li>
                        </ul>
                    </li>
                    <li class="has_sub">
                        <a href="#"><i class="fa fa-cubes"></i> <span>IPs</span></a>

                        <ul class="list-unstyled">
                            <li>
                                <a href="{{route('admin.ips')}}">Manage</a>
                            </li>
                            <li>
                                <a href="{{route('admin.ip-create')}}">Add IPs</a>
                            </li>
                            <li>
                                <a href="#">Add IPV6 Addresses</a>
                            </li>
                        </ul>
                    </li>
                    <li class="has_sub">
                        <a href="#"><i class="fa fa-cubes"></i> <span>Alerts</span></a>

                        <ul class="list-unstyled">
                            <li>
                                <a href="{{route('admin.notifications')}}">Manage</a>
                            </li>
                            <li>
                                <a href="{{route('admin.notification-create')}}">New alert</a>
                            </li>
                        </ul>
                    </li>
                    <li class="has_sub">
                        <a href="#"><i class="fa fa-cubes"></i> <span>Resource Pools</span></a>

                        <ul class="list-unstyled">
                            <li>
                                <a href="{{route('admin.resourcepools')}}">Manage</a>
                            </li>
                            <li>
                                <a href="{{route('admin.resourcepool-create')}}">New resource pool</a>
                            </li>
                        </ul>
                    </li>
                    <li class="has_sub">
                        <a href="#"><i class="fa fa-cubes"></i> <span>Backup servers</span></a>

                        <ul class="list-unstyled">
                            <li>
                                <a href="{{route('admin.backup-servers')}}">Manage</a>
                            </li>
                            <li>
                                <a href="{{route('admin.backup-server-create')}}">New server</a>
                            </li>
                        </ul>
                    </li>
                    <li class="has_sub">
                        <a href="#"><i class="fa fa-cubes"></i> <span>Maintenance</span></a>

                        <ul class="list-unstyled">
                            <li>
                                <a href="{{route('admin.backups')}}">Backups</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="{{route('logout')}}"><i class="fa fa-power-off"></i> <span>Log out</span></a>
                    </li>

                </ul>


                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <!-- Left Sidebar End -->



    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">

                <!-- Page-Title -->
                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="pull-left page-title">{{$title}}</h4>
                        <ol class="breadcrumb pull-right">
                            <li><a href="{{route('admin.home')}}">MSPVM</a></li>
                            @yield('navigation')
                        </ol>
                    </div>
                </div>

                <div class="actionbar">@yield('actionbar')</div>

                <div class="alert alert-success">{{ session('message') }}</div>
                <div class="alert alert-danger">{{ implode("<br />",$errors->all()) }}</div>

                @yield('content')
                </div> <!-- end row -->

            </div> <!-- container -->

        </div> <!-- content -->

        <footer class="footer text-right">
            2015 © MSPVM.
        </footer>

    </div>
    <!-- ============================================================== -->
    <!-- End Right content here -->
    <!-- ============================================================== -->


</div>
<!-- END wrapper -->



<script>
    var resizefunc = [];
</script>

<!-- jQuery  -->
<script src="{{asset('js/jquery.min.js')}}"></script>
<script src="{{asset('js/bootstrap.min.js')}}"></script>
<script src="{{asset('js/waves.js')}}"></script>
<script src="{{asset('js/wow.min.js')}}"></script>
<script src="{{asset('js/jquery.nicescroll.js')}}" type="text/javascript"></script>
<script src="{{asset('js/jquery.scrollTo.min.js')}}"></script>
<script src="{{asset('assets/chat/moment-2.2.1.js')}}"></script>
<script src="{{asset('assets/jquery-sparkline/jquery.sparkline.min.js')}}"></script>
<script src="{{asset('assets/jquery-detectmobile/detect.js')}}"></script>
<script src="{{asset('assets/fastclick/fastclick.js')}}"></script>
<script src="{{asset('assets/jquery-slimscroll/jquery.slimscroll.js')}}"></script>
<script src="{{asset('assets/jquery-blockui/jquery.blockUI.js')}}"></script>

<script src="{{asset('assets/notifications/notify.min.js')}}"></script>
<script src="{{asset('assets/notifications/notify-metro.js')}}"></script>
<script src="{{asset('assets/notifications/notifications.js')}}"></script>


<!-- DataTables -->
<script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>

<!-- CUSTOM JS -->
<script src="{{asset('js/jquery.app.js')}}"></script>



@yield('footer')

</body>
</html>