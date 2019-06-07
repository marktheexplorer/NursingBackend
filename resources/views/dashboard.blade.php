@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="page-content fade-in-up">
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="ibox bg-info color-white widget-stat">
                    <div class="ibox-body">
                        <h2 class="m-b-5 font-strong">{{ $users['total_users'] }}</h2>
                        <div class="m-b-5">Total Users</div><i class="fas fa-users widget-stat-icon"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="ibox bg-success color-white widget-stat">
                    <div class="ibox-body">
                        <h2 class="m-b-5 font-strong">{{ $users['active_users'] }}</h2>
                        <div class="m-b-5">Active Users</div><i class="fas fa-user-check widget-stat-icon"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="ibox bg-danger color-white widget-stat">
                    <div class="ibox-body">
                        <h2 class="m-b-5 font-strong">{{ $users['blocked_users'] }}</h2>
                        <div class="m-b-5">Blocked Users</div><i class="fas fa-user-times widget-stat-icon"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="ibox bg-warning color-white widget-stat">
                    <div class="ibox-body">
                        <h2 class="m-b-5 font-strong">{{ $enquiries }}</h2>
                        <div class="m-b-5">Total Enquiries</div><i class="fas fa-clipboard-list widget-stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="ibox bg-info color-white widget-stat">
                    <div class="ibox-body">
                        <h2 class="m-b-5 font-strong">{{ $users['total_users'] }}</h2>
                        <div class="m-b-5">Total Caregiver</div><i class="fas fa-users widget-stat-icon"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="ibox bg-success color-white widget-stat">
                    <div class="ibox-body">
                        <h2 class="m-b-5 font-strong">{{ $users['active_users'] }}</h2>
                        <div class="m-b-5">Active Caregiver</div><i class="fas fa-user-check widget-stat-icon"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="ibox bg-danger color-white widget-stat">
                    <div class="ibox-body">
                        <h2 class="m-b-5 font-strong">{{ $users['blocked_users'] }}</h2>
                        <div class="m-b-5">Blocked Caregiver</div><i class="fas fa-user-times widget-stat-icon"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="ibox bg-warning color-white widget-stat">
                    <div class="ibox-body">
                        <h2 class="m-b-5 font-strong">{{ $faqs }}</h2>
                        <div class="m-b-5">Total FAQs</div><i class="fas fa-question widget-stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <div class="ibox">
                    <div class="ibox-body">
                        <div class="flexbox mb-4">
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
@endsection
