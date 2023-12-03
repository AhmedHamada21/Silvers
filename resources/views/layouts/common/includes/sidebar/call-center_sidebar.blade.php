<div class="row">
    <!-- Left Sidebar start-->
    <div class="side-menu-fixed">
        <div class="scrollbar side-menu-bg">
            <ul class="nav navbar-nav side-menu" id="sidebarnav">
                <!-- menu item Dashboard-->
                <li>
                    <a href="{{ route('callCenter.dashboard') }}">Call-Center Managment</a>
                </li>
                <!-- Start Admin Managment Menu-->
                <li class="pl-4 mt-10 mb-10 font-medium text-muted menu-title">Call-Center Managment</li>
                <li>
                    <a href="javascript:void(0);" data-toggle="collapse" data-target="#call-centers_managment">
                        <div class="pull-left">
                            <i class="ti-palette"></i>
                            <span class="right-nav-text">Call-Center</span>
                        </div>
                        <div class="pull-right"><i class="ti-plus"></i></div>
                        <div class="clearfix"></div>
                    </a>
                    <ul id="call-centers_managment" class="collapse" data-parent="#sidebarnav">
                        <li><a href="{{route('CallCenterCaptains.index')}}">Captain</a></li>
                    </ul>
                </li>
                <!-- End Admin Managment Menu-->
            </ul>
        </div>
    </div>

    <!-- Left Sidebar End-->
</div>