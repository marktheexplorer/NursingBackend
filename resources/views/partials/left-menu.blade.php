<nav class="page-sidebar" id="sidebar">
    <div id="sidebar-collapse">
        <div class="admin-block d-flex">
            <div>
                <img src="{{ asset('admin/assets/img/admin-avatar.png') }}" width="45px">
            </div>
            <div class="admin-info">
                <div class="font-strong">{{ ucfirst(Auth::user()->name) }}</div><small>Administrator</small>
            </div>
        </div>
        <ul class="side-menu metismenu">
            <li>
                <a class="active" href="{{ route('dashboard') }}"><i class="sidebar-item-icon fa fa-th-large"></i>
                    <span class="nav-label">Dashboard</span>
                </a>
            </li>
            <li class="heading">FEATURES</li>
            <li>
                <a href="javascript:;"><i class="sidebar-item-icon fas fa-users"></i>
                    <span class="nav-label">Users</span><i class="fa fa-angle-left arrow"></i></a>
                <ul class="nav-2-level collapse" aria-expanded="false">
                    <li>
                        <a href="{{ route('users.create') }}"><i class="sidebar-item-icon fas fa-plus"></i>Add User</a>
                    </li>
                    <li>
                        <a href="{{ route('users.index') }}"><i class="sidebar-item-icon fas fa-list-ul"></i>Active Users</a>
                    </li>
                    <li>
                        <a href="{{ route('users.blocklist') }}"><i class="sidebar-item-icon fas fa-list-ul"></i>Block Users</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;"><i class="sidebar-item-icon fas fa-users"></i>
                    <span class="nav-label">Caregiver</span><i class="fa fa-angle-left arrow"></i></a>
                <ul class="nav-2-level collapse" aria-expanded="false">
                    <li>
                        <a href="#"><i class="sidebar-item-icon fas fa-plus"></i>Add Caregiver</a>
                    </li>
                    <li>
                        <a href="#"><i class="sidebar-item-icon fas fa-list-ul"></i>Active Caregiver</a>
                    </li>
                    <li>
                        <a href="#"><i class="sidebar-item-icon fas fa-list-ul"></i>Block Caregiver</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;"><i class="sidebar-item-icon fas fa-users"></i>
                    <span class="nav-label">Services</span><i class="fa fa-angle-left arrow"></i></a>
                <ul class="nav-2-level collapse" aria-expanded="false">
                    <li>
                        <a href="{{ route('services.create') }}"><i class="sidebar-item-icon fas fa-plus"></i>Add Service</a>
                    </li>
                    <li>
                        <a href="{{ route('services.index') }}"><i class="sidebar-item-icon fas fa-list-ul"></i>Services</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;"><i class="sidebar-item-icon fas fa-bullhorn"></i>
                    <span class="nav-label">Enquiry</span><i class="fa fa-angle-left arrow"></i></a>
                    <ul class="nav-2-level collapse" aria-expanded="false">
                        <li>
                            <a href="{{ route('enquiries.index') }}"><i class="sidebar-item-icon fas fa-list"></i>Enquiry</a>
                        </li>
                    </ul>
                </a>
            </li>
            <li>
                <a href="javascript:;"><i class="sidebar-item-icon fas fa-question-circle"></i>
                    <span class="nav-label">FAQs</span><i class="fa fa-angle-left arrow"></i></a>
                <ul class="nav-2-level collapse" aria-expanded="false">
                    <li>
                        <a href="{{ route('faqs.create') }}"><i class="sidebar-item-icon fas fa-plus"></i>Add FAQ</a>
                    </li>
                    <li>
                        <a href="{{ route('faqs.index') }}"><i class="sidebar-item-icon fas fa-list"></i>List FAQs</a>
                    </li>
                    <li>
                        <a href="{{ route('faqs.reorder') }}"><i class="sidebar-item-icon fas fa-sort-amount-up"></i>Reorder FAQs</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;"><i class="sidebar-item-icon fas fa-list-alt"></i>
                    <span class="nav-label">CMS Pages</span><i class="fa fa-angle-left arrow"></i></a>
                <ul class="nav-2-level collapse" aria-expanded="false">
                    <li>
                        <a href="{{ route('cms.create') }}"><i class="sidebar-item-icon fas fa-plus"></i>  Add Page</a>
                    </li>
                    <li>
                        <a href="{{ route('cms.index') }}"><i class="sidebar-item-icon fas fa-list-ul"></i>  List CMS Pages</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ route('enquiries.index') }}"><i class="sidebar-item-icon fas fa-address-card"></i>
                    <span class="nav-label">Enquiry</span>
                </a>
            </li>
            <!-- <li>
                <a href="javascript:;"><i class="sidebar-item-icon fa fa-bar-chart"></i>
                    <span class="nav-label">Charts</span><i class="fa fa-angle-left arrow"></i></a>
                <ul class="nav-2-level collapse" aria-expanded="false">
                    <li>
                        <a href="charts_flot.html">Flot Charts</a>
                    </li>
                    <li>
                        <a href="charts_morris.html">Morris Charts</a>
                    </li>
                    <li>
                        <a href="chartjs.html">Chart.js</a>
                    </li>
                    <li>
                        <a href="charts_sparkline.html">Sparkline Charts</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;"><i class="sidebar-item-icon fa fa-map"></i>
                    <span class="nav-label">Maps</span><i class="fa fa-angle-left arrow"></i></a>
                <ul class="nav-2-level collapse" aria-expanded="false">
                    <li>
                        <a href="maps_vector.html">Vector maps</a>
                    </li>
                </ul>
            </li>
           
            <li class="heading">PAGES</li>
            <li>
                <a href="javascript:;"><i class="sidebar-item-icon fa fa-envelope"></i>
                    <span class="nav-label">Mailbox</span><i class="fa fa-angle-left arrow"></i></a>
                <ul class="nav-2-level collapse" aria-expanded="false">
                    <li>
                        <a href="mailbox.html">Inbox</a>
                    </li>
                    <li>
                        <a href="mail_view.html">Mail view</a>
                    </li>
                    <li>
                        <a href="mail_compose.html">Compose mail</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="calendar.html"><i class="sidebar-item-icon fa fa-calendar"></i>
                    <span class="nav-label">Calendar</span>
                </a>
            </li>
            <li>
                <a href="javascript:;"><i class="sidebar-item-icon fa fa-file-text"></i>
                    <span class="nav-label">Pages</span><i class="fa fa-angle-left arrow"></i></a>
                <ul class="nav-2-level collapse" aria-expanded="false">
                    <li>
                        <a href="invoice.html">Invoice</a>
                    </li>
                    <li>
                        <a href="profile.html">Profile</a>
                    </li>
                    <li>
                        <a href="login.html">Login</a>
                    </li>
                    <li>
                        <a href="register.html">Register</a>
                    </li>
                    <li>
                        <a href="lockscreen.html">Lockscreen</a>
                    </li>
                    <li>
                        <a href="forgot_password.html">Forgot password</a>
                    </li>
                    <li>
                        <a href="error_404.html">404 error</a>
                    </li>
                    <li>
                        <a href="error_500.html">500 error</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;"><i class="sidebar-item-icon fa fa-sitemap"></i>
                    <span class="nav-label">Menu Levels</span><i class="fa fa-angle-left arrow"></i></a>
                <ul class="nav-2-level collapse" aria-expanded="false">
                    <li>
                        <a href="javascript:;">Level 2</a>
                    </li>
                    <li>
                        <a href="javascript:;">
                            <span class="nav-label">Level 2</span><i class="fa fa-angle-left arrow"></i></a>
                        <ul class="nav-3-level collapse" aria-expanded="false">
                            <li>
                                <a href="javascript:;">Level 3</a>
                            </li>
                            <li>
                                <a href="javascript:;">Level 3</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li> -->
        </ul>
    </div>
</nav>
