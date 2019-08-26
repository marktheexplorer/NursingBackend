@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- START PAGE CONTENT-->
    <div class="page-heading">
        <h1 class="page-title">County Management</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
            </li>
            <li class="breadcrumb-item">County</li>
        </ol>
    </div>
    @include('flash::message')
    <div class="page-content fade-in-up">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">County Data</div>
            </div>
            <div class="row">
                <div class="col-md-12" style="text-align: right;">
                    <form action="{{ route('county.store') }}" enctype = 'multipart/form-data' method="post" class="form-horizontal">
                    @csrf
                        <input type="button" class="btn btn-info pull-right" style="float: right;margin-right: 20px;" onclick="$('#county').val('');$('#countyid').val(0);" value="Reset" />
                        <button class="btn btn-info pull-right" type="submit" style="margin-left:30px;float: right;margin-right: 20px;" id="addbutton"> Submit</button>

                        <input type="hidden" name="countyid" id="countyid" value="0"/>
                        <input type="text" class="form-control {{ $errors->has('county') ? ' is-invalid' : '' }}" name="county" placeholder="Add County" value="{{ old('county') }}" style="width:30%;float:right;" id="county"/>
                        @if ($errors->has('county'))
                            <span class="text-danger">
                                <strong>{{ $errors->first('county') }}</strong>
                            </span>
                        @endif
                    </form>
                </div>
            </div>
            <div class="ibox-body">
                <table class="table table-striped table-bordered table-hover" id="data-table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
              				<th>County</th>
                            <th>Status</th>
	                  		<th style="min-width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>County</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </tfoot>
                    <tbody>
                 	@foreach($county as $key => $row)
	            		<tr>
	            			<td>{{ ++$key }}.
	              			<td>{{ ucfirst($row->county) }}</td>
                            <td>
                                @if($row->is_blocked == 0)
                                    Blocked
                                @else
                                    Active
                                @endif
                            </td>
	              			<td>
	              				<ul class="actions-menu">
	              					<li>
	              					    <button class="btn-sm btn-primary btn-cir" title="Edit" onclick="editcountry({{$row->id}}, '{{$row->county}}' ); "><i class="fas fa-pencil-alt"></i></button>
	              					</li>
	              					<li>
	              						<a href="{{ route('county.show',['id' => $row->id]) }}">
	              							<button class="btn-sm btn-warning btn-cir" title="View"><i class="fas fa-eye"></i></button>
	              						</a>
	              					</li>
	              					<li>
                                        <a href="{{ url('admin/county/blocked/'.$row->id) }}">
                                        @if($row->is_blocked)
                                            <button type="button" class="btn-sm btn-danger btn-cir" title="Unblock"><i class="fa fa-unlock"></i></button>
                                        @else
                                            <button type="button" class="btn-sm btn-success btn-cir" title="Block"><i class="fa fa-ban"></i></button>
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

    function editcountry(id, county){
        $("#county").val(county);
        $("#countyid").val(id);
    }
</script>
@endsection
