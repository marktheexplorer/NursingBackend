@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- START PAGE CONTENT-->
    <div class="page-heading">
        <h1 class="page-title">Patients Management</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('patients.index')}}" >Patients</a></li>
        </ol>
    </div>
    @include('flash::message')
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Patient Data</div>
                <div>                    
                    <a href="{{ route('patients.active') }}"><button class="btn btn-primary">Active Patients</button></a>
                    <a href="{{ route('patients.inactive') }}"><button class="btn btn-primary">Inactive Patients</button></a>
                </div>
                <div>
                    <a href="{{ route('patients.create') }}"><button class="btn btn-info"><i class="fas fa-plus"></i> Add</button></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="{{ route('patients.download_excel') }}" style="" target="_blank"><button class="btn btn-info "><i class="fas fa-download"></i> Download</button></a>
                </div>
            </div>
            <div class="ibox-body">
                <table class="table table-striped table-bordered table-hover" id="data-table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Id</th>
              				<th>Name</th>
	                  		<th>Email</th>
	                  		<th>Mobile no</th>
	                  		<th>Created At</th>
	                  		<th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                 	@foreach($patients as $key => $patient)
	            		<tr>
	            			<td>{{ ++$key }}</td>
	              			<td>{{ ucfirst($patient->name) }}</td>
	              			<td>{{ $patient->email }}</td>
	              			<td>{{ $patient->mobile_number }}</td>
	              			<td>{{ date_format(date_create($patient->created_at), 'd M, y')}}</td>
	              			<td>
	              				<ul class="actions-menu">
	              					<li>
	              						<a href="{{ url('admin/patients/blocked/'.$patient->id) }}">
					                    @if($patient->is_blocked)
					                    	<button type="button" class="btn-sm btn-danger btn-cir" title="Unblock"><i class="fas fa-lock-open"></i></i></button>
					                	@else
					                		<button type="button" class="btn-sm btn-success btn-cir" title="Block"><i class="fas fa-lock"></i></button>
					            		@endif
                                        </a>
	              					</li>
	              					<li>
	              						<a href="{{ route('patients.edit',['id' => $patient->id]) }}">
	              							<button class="btn-sm btn-primary btn-cir" title="Edit"><i class="fas fa-pencil-alt"></i></button>
	              						</a>
	              					</li>
	              					<li>
	              						<a href="{{ route('patients.show',['id' => $patient->id]) }}">
	              							<button class="btn-sm btn-warning btn-cir" title="View"><i class="fas fa-eye"></i></button>
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

