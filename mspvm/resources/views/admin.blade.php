
<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{$title}}</title>

    <link href="{{asset('assets/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">

    <link href="{{asset('assets/fa/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">

    <link href="{{asset('assets/mspvm/style.css')}}" rel="stylesheet" type="text/css">

    <link href="{{asset('assets/metisMenu/dist/metisMenu.min.css')}}" rel="stylesheet">

    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700' rel='stylesheet' type='text/css'>
</head>

<body>
<div class="overlay"></div>
<div class="header">

    <div class="logopanel">
        <a href="#">MSPVM</a>
    </div>

    <div class="headerbar">
        <ul class="menu">
            <li>
                <a href="#" class="btn btn-chat">
                    <i class="fa fa-power-off"></i>
                </a>
            </li>
        </ul>
    </div>
</div>
<div id="wrapper">
    <div id="layout">
        <div class="sidebar">
            <div class="sidebar-content">

                <div class="sidebar-profile">
                    <div class="profile">
                        <div class="personal">
                            <h4 class="name">Ervin Czeczi</h4>
                            <span>Customer</span><br />
                            <span><i class="fa fa-lock"></i>&nbsp; 192.168.1.1</span>
                        </div>
                    </div>

                    <div class="actions">
                        <a href="#" class="btn btn-primary">
                            <i class="fa fa-cogs"></i>
                        </a>
                        <a href="#" class="btn btn-primary">
                            <i class="fa fa-plus"></i>
                        </a>
                        <a href="#" class="btn btn-primary">
                            <i class="fa fa-power-off"></i>
                        </a>
                    </div>
                </div>

                <div class="tab-content">
                    <div class="tab-pane active" id="mainmenu">
                        <ul class="nav nav-pills nav-stacked" id="side-menu">
                            <li>
                                <a href="{{route('home')}}">Home</a>
                            </li>
                            <li>
                                <a href="#">Serversx</a>

                                <ul class="nav nav-second-level collapse in">
                                    <li>
                                        <a href="{{route('vms')}}">Servers</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="{{route('vms')}}">VMs</a>
                            </li>
                            <li>
                                <a href="{{route('vms')}}">Users</a>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>



        <div id="content">

            <div class="contentpanel">
                <div id="top">
                    <div class="topbar">
                        <div class="row">
                            <div class="col-md-4 col-sm-7">
                                <h4 class="title" style="margin-top: 0;">{!! $title !!}</h4>
                                <ul class="breadcrumbs hidden-xs hidden-sm">
                                    <li><a href="#"><i class="fa fa-home"></i></a></li>
                                    @yield('navigation')
                                </ul>
                            </div>
                            <div class="col-md-8 col-sm-5">
                                <div class="actionbar">
                                    @yield('actionbar')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="the_content">
                    <div class="alert alert-success">{{ session('message') }}</div>
                    <div class="alert alert-danger">{{ implode("<br />",$errors->all()) }}</div>

                    @yield('content')
                </div>
            </div>

        </div>

    </div>
</div>

@footer()

<script src="{{asset('assets/metisMenu/dist/metisMenu.min.js')}}"></script>


</body>
</html>
