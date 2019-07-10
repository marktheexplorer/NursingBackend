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
                <a href="javascript:;"><i class="sidebar-item-icon fas fa-user-nurse"></i>
                    <span class="nav-label">Caregiver</span><i class="fa fa-angle-left arrow"></i></a>
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

                <a href="{{ route('patients.index') }}"><i class="sidebar-item-icon fas fa fa-wheelchair"></i>
                    <span class="nav-label">Patient Management</span></a>
            </li>
            <li>
                <a href="javascript:;"><i class="sidebar-item-icon fas fa-book-medical"></i>
                    <span class="nav-label">Service Request</span><i class="fa fa-angle-left arrow"></i></a>
                <ul class="nav-2-level collapse" aria-expanded="false">
                    <li>
                        <a href="{{ route('service_request.index') }}"><i class="sidebar-item-icon fas fa-list-ul"></i>Service Request List</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;"><i class="sidebar-item-icon fas fa-list"></i><span class="nav-label">Master List</span><i class="fa fa-angle-left arrow"></i></a>
                <ul class="nav-2-level collapse" aria-expanded="false">
                    <li>
                        <a href="{{ route('services.index') }}"><i class="sidebar-item-icon fas fa fa-user"></i>Services</a>
                    </li>
                    <li>
                        <a href="{{ route('qualifications.index') }}"><i class="sidebar-item-icon fa fa-graduation-cap"></i>Qualifications</a>
                    </li>
                    <li>
                        <a href="{{ route('diagnosis.index') }}"><i class="sidebar-item-icon fas fa-diagnoses"></i>Diagnosis</a>
                    </li>
                    
                </ul>  
            </li>
            <li>
                <a href="javascript:;"><i class="sidebar-item-icon fas fa-question"></i>
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
                <a href="javascript:;"><i class="sidebar-item-icon far fa-file-alt"></i>
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
                <a href="{{ route('enquiries.index') }}"><i class="sidebar-item-icon fas fa-mail-bulk"></i>
                    <span class="nav-label">Enquiry</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
