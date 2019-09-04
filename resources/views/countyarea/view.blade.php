@extends('layouts.app')

@section('content')
<style type="text/css">table.dataTable tfoot th{border-top: #9e9e9e00;}</style>
<div class="content-wrapper">
    <!-- START PAGE CONTENT-->
    <div class="page-heading">
        <h1 class="page-title">{{$county->county }} Management</h1>
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
                <div class="ibox-title">{{$county->county }}</div>
            </div>
            <div class="row">
                <div class="col-md-12" style="text-align: right;">
                    <form action="{{ route('county.store_area') }}" enctype = 'multipart/form-data' method="post" class="form-horizontal">
                    @csrf
                        <input type="button" class="btn btn-info pull-right" style="float: right;margin-right: 20px;" onclick="$('#area').val('');$('#areaid').val(0);" value="Reset" />
                        <button class="btn btn-info pull-right" type="submit" style="margin-left:30px;float: right;margin-right: 20px;" id="addbutton"> Submit</button>

                        <input type="hidden" name="countyid" id="countyid" value="{{ $county->id }}"/>
                        <input type="hidden" name="areaid" id="areaid" value="0"/>
                        <input type="text" class="form-control {{ $errors->has('area') ? ' is-invalid' : '' }}" name="area" placeholder="Add Area" value="{{ old('area') }}" style="width:30%;float:right;" id="area"/>
                        @if ($errors->has('area'))
                            <span class="text-danger">
                                <strong style="line-height: 2.5;margin-right:20px;">{{ $errors->first('area') }}</strong>
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
                            <th>Area</th>
                            <th>Status</th>
                            <th style="max-width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(count($countyareas) > 0)
                        @foreach($countyareas as $key => $row)
                            <tr>
                                <td>{{ ++$key }}.
                                <td>{{ ucfirst($row->area) }}</td>
                                <td>
                                    @if($row->is_area_blocked == 0)
                                        Blocked
                                    @else
                                        Active
                                    @endif
                                </td>
                                <td style="max-width: 150px;">
                                    <ul class="actions-menu">
                                        <li>
                                            <button class="btn-sm btn-primary btn-cir" title="Edit" onclick="editcarea({{$row->id}}, '{{$row->area}}' ); "><i class="fas fa-pencil-alt"></i></button>
                                        </li>
                                        <li>
                                            <a href="{{ url('admin/county/delete_area/'.$row->id) }}">
                                            @if($row->is_area_blocked)
                                                <button type="button" class="btn-sm btn-danger btn-cir" title="Un-block"><i class="fa fa-unlock"></i></button>
                                            @else
                                                <button type="button" class="btn-sm btn-success btn-cir" title="Block"><i class="fa fa-ban"></i></button>
                                            @endif
                                            </a>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3" style="text-align: center;"><strong>No record found.</strong></td>
                        </tr>
                    @endif
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

    function editcarea(id, area){
        $("#area").val(area);
        $("#areaid").val(id);
        $("#area").focus();
    }
</script>
@endsection
