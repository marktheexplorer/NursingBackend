@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="page-content fade-in-up">
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <a href="{{ route('caregiver.index') }}">
                    <div class="ibox bg-info color-white widget-stat">
                        <div class="ibox-body">
                            <h2 class="m-b-5 font-strong">{{ $users['caregivers'] }}</h2>
                            <div class="m-b-5">Total Caregiver</div><i class="fas fa-users widget-stat-icon"></i>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6">
                <a href="{{ route('patients.index') }}">
                    <div class="ibox bg-success color-white widget-stat">
                        <div class="ibox-body">
                            <h2 class="m-b-5 font-strong">{{ $users['patients'] }}</h2>
                            <div class="m-b-5">Total Clients</div><i class="fas fa fa-wheelchair widget-stat-icon"></i>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6">
                <a href="{{ route('bookings.index') }}">
                    <div class="ibox bg-danger color-white widget-stat">
                        <div class="ibox-body">
                            <h2 class="m-b-5 font-strong">{{ $bookings }}</h2>
                            <div class="m-b-5">Total Bookings</div><i class="fas fa-calendar-week widget-stat-icon"></i>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6">
                <a href="{{ route('services.index') }}">
                    <div class="ibox bg-warning color-white widget-stat">
                        <div class="ibox-body">
                            <h2 class="m-b-5 font-strong">{{ $services }}</h2>
                            <div class="m-b-5">Total Services</div><i class="fas fa-calendar-week widget-stat-icon"></i>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <a href="{{ route('diagnosis.index') }}">
                    <div class="ibox bg-info color-white widget-stat">
                        <div class="ibox-body">
                            <h2 class="m-b-5 font-strong">{{ $diagnosis }}</h2>
                            <div class="m-b-5">Diagnoses</div><i class="fas fa-diagnoses widget-stat-icon"></i>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6">
                <a href="{{ route('faqs.index') }}">
                    <div class="ibox bg-success color-white widget-stat">
                        <div class="ibox-body">
                            <h2 class="m-b-5 font-strong">{{ $faqs }}</h2>
                            <div class="m-b-5">Total FAQs</div><i class="fas fa-question widget-stat-icon"></i>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6">
                <a href="{{ route('contactUs.index') }}">
                    <div class="ibox bg-danger color-white widget-stat">
                        <div class="ibox-body">
                            <h2 class="m-b-5 font-strong">{{ $contactUs }}</h2>
                            <div class="m-b-5">Total Contact Us</div><i class="fas fa-clipboard-list widget-stat-icon"></i>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-body">
                        <div class="flexbox mb-12">
                            <div>
                                <h3 class="m-0">Users Data</h3>
                                <div>Users analytics (monthly)</div>
                            </div>
                        </div>
                        <div>{!! $chart->html() !!}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer-scripts')
    {!! Charts::scripts() !!}
    {!! $chart->script() !!}
    <script type="text/javascript">
        $(".sidebar-toggler").click(function(){
            $(".fixed-navbar.has-animation").toggleClass("sidebar-mini");
        });
    </script>
@endsection
