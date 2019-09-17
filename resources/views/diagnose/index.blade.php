@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- START PAGE CONTENT-->
    <div class="page-heading">
        <h1 class="page-title">Diagnose Management</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
            </li>
            <li class="breadcrumb-item">Diagnosis</li>
        </ol>
    </div>
    @include('flash::message')
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Diagnosis Data</div>
                <a href="{{ route('diagnosis.create') }}"><button class="btn btn-info pull-right"><i class="fas fa-plus"></i> Add</button></a>
            </div>
            <div class="ibox-body">
                <table class="table table-striped table-bordered table-hover" id="data-table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
              				<th>Title</th>
	                  		<th>Created At</th>
	                  		<th style="min-width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                 	@foreach($diagnosis as $key => $diagnose)
	            		<tr>
	            			<td>{{ ++$key }}</td>
	              			<td>{{ ucfirst($diagnose->title) }}</td>
	              			<td>{{ date_format(date_create($diagnose->created_at), 'd M, y')}}</td>
	              			<td>
	              				<ul class="actions-menu">
	              					<li>
	              						<a href="{{ route('diagnosis.edit',['id' => $diagnose->id]) }}">
	              							<button class="btn-sm btn-primary btn-cir" title="Edit"><i class="fas fa-pencil-alt"></i></button>
	              						</a>
	              					</li>
	              					<li>
                                        <a href="{{ url('admin/diagnosis/blocked/'.$diagnose->id) }}">
                                        @if($diagnose->is_blocked)
                                            <button type="button" class="btn-sm btn-danger btn-cir" title="Block"><i class="fas fa-lock"></i></button>
                                        @else
                                            <button type="button" class="btn-sm btn-success btn-cir" title="Un-Block"><i class="fas fa-lock-open"></i></button>
                                        @endif
                                        </a>
                                    </li>
	              				</ul>
	              			</td>
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