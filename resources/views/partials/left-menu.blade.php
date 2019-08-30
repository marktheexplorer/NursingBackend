<nav class="page-sidebar" id="sidebar">
    <div id="sidebar-collapse">
        <div class="admin-block d-flex">
            <div>
                @if(empty(Auth::user()->profile_image))
                    <img src="{{ asset('admin/assets/img/admin-avatar.png') }}" width="45px">
                @else
                    <img class="img-circle" src="{{ asset(Auth::user()->profile_image) }}" style="width:40px;height:40px;" />
                @endif
            </div>
            <div class="admin-info">
                <div class="font-strong">{{ ucfirst(Auth::user()->name) }}</div><small>Administrator</small>
            </div>
        </div>
        <ul class="side-menu metismenu">
            <li>
                <a class="active" href="{{ route('dashboard') }}" style="{{ (request()->is('admin/dashboard*')) ? 'background-color:#3498db;color:#fff;' : '' }}"><i class="sidebar-item-icon fa fa-th-large"></i>
                    <span class="nav-label">Dashboard</span>
                </a>
            </li>
            <li class="heading">FEATURES</li>
            <li>
                <a href="javascript:;" style="{{ (request()->is('admin/caregiver*')) ? 'background-color:#3498db;color:#fff;' : '' }}">
                    <i class="sidebar-item-icon fas fa-user-nurse"></i>
                    <span class="nav-label">Caregiver</span>
                    <i class="fa fa-angle-left arrow"></i>
                </a>
                <ul class="nav-2-level collapse" aria-expanded="false">
                    <li>
                        <a href="{{ route('caregiver.create') }}"><i class="sidebar-item-icon fas fa-plus"></i>Add Caregiver</a>
                    </li>
                    <li>
                        <a href="{{ route('caregiver.index') }}"><i class="sidebar-item-icon fas fa-list-ul"></i>Caregiver List</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ route('patients.index') }}" style="{{ (request()->is('admin/patients*')) ? 'background-color:#3498db;color:#fff;' : '' }}"><i class="sidebar-item-icon fas fa fa-wheelchair"></i>
                    <span class="nav-label">Patient</span></a>
            </li>
            <li>
                <a href="{{ route('service_request.index') }}" style="{{ (request()->is('admin/service_request*')) ? 'background-color:#3498db;color:#fff;' : '' }}"><i class="sidebar-item-icon fas fa-book-medical"></i>
                    <span class="nav-label">Requests</span></a>
            </li>
            <li>
                @if((request()->is('admin/county*')) || (request()->is('admin/services*')) || (request()->is('admin/qualifications*')) || (request()->is('admin/diagnosis*')))
                    <a href="javascript:;"  style="background-color:#3498db;color:#fff"><i class="sidebar-item-icon fas fa-list"></i><span class="nav-label">Master List</span><i class="fa fa-angle-left arrow"></i></a>
                @else
                    <a href="javascript:;"><i class="sidebar-item-icon fas fa-list"></i><span class="nav-label">Master List</span><i class="fa fa-angle-left arrow"></i></a>
                @endif    
                <ul class="nav-2-level collapse" aria-expanded="false">
                    <li>
                        <a href="{{ route('county.index') }}"><i class="sidebar-item-icon fas fa-map-signs"></i>County</a>
                    </li>
                    <li>
                        <a href="{{ route('services.index') }}"><i class="sidebar-item-icon fas fa fa-user"></i>Services</a>
                    </li>
                    <li>
                        <a href="{{ route('qualifications.index') }}"><i class="sidebar-item-icon fa fa-graduation-cap"></i>Disciplines</a>
                    </li>
                    <li>
                        <a href="{{ route('diagnosis.index') }}"><i class="sidebar-item-icon fas fa-diagnoses"></i>Diagnosis</a>
                    </li>
                    
                </ul>  
            </li>
            <li>
                <a href="javascript:;" style="{{ (request()->is('admin/faqs*')) ? 'background-color:#3498db;color:#fff;' : '' }}"><i class="sidebar-item-icon fas fa-question"></i>
                    <span class="nav-label">FAQs</span><i class="fa fa-angle-left arrow"></i>
                </a>
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
                <a href="javascript:;" style="{{ (request()->is('admin/cms*')) ? 'background-color:#3498db;color:#fff;' : '' }}"><i class="sidebar-item-icon far fa-file-alt"></i>
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
                <a href="{{ route('enquiries.index') }}" style="{{ (request()->is('admin/enquiries*')) ? 'background-color:#3498db;color:#fff;' : '' }}"><i class="sidebar-item-icon fas fa-mail-bulk"></i>         
                    <span class="nav-label">Inquiry</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
