@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- START PAGE CONTENT-->
    <div class="page-heading">
        <h1 class="page-title">Message History</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('patients.index')}}" >Client</a></li>
        </ol>
    </div>
    @include('flash::message')
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Client Data</div>
                <div>
                    <a href="{{ route('sendmsg.create') }}"><button class="btn btn-info"><i class="fas fa-plus"></i> Send Message</button></a>
                </div>
            </div>
            <div class="ibox-body">
                <table class="table table-striped table-bordered table-hover" id="data-table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
	                  		<th>Mobile Number</th>
                            <th>Message</th>
	                  		<th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                 	@foreach($messages as $key => $message)
	            		<tr>
	            			<td>{{ ++$key }}</td>
	              			<td>{{ $message->user->mobile_number != '' ? '+'.$message->user->country_code.' '.substr_replace(substr_replace($message->user->mobile_number, '-', '3','0'), '-', '7','0') : '' }}</td>
                            <td>{{ $message->msg }}</td>
	              			<td>{{ date_format(date_create($message->created_at), 'd M, y')}}</td>
	            		</tr>
            		@endforeach
                    </tbody>
                </table>
            </div>
        </div>
	</div>
</div>
@endsection
@section('footer-scripts')
<script type="text/javascript">
    $(document).ready( function () {
        $('#data-table').DataTable();
    });
</script>
@endsection
