@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="page-heading">
        <h1 class="page-title">Cms Pages</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
            </li>
            <li class="breadcrumb-item">Cms Pages</li>
        </ol>
    </div>
    @include('flash::message')
    <div class="page-content fade-in-up">
        <a href="{{ route('cms.create') }}"><button class="btn btn-info "><i class="fas fa-plus"></i> Add</button></a>
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Cms Page Data</div>
            </div>
            <div class="ibox-body">
                <table class="table table-striped table-bordered table-hover" id="data-table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Title</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Id</th>
                            <th>Title</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </tfoot>
                    <tbody>
                    @foreach($cms as $key => $page)
                        <tr>
                            <td>{{ ++$key }}
                            <td>{{ $page->title }}</td>
                            <td>{{ date_format(date_create($page->created_at) , 'd M ,y') }}
                            <td>
                                <ul class="actions-menu">
                                    <li>
                                        <a href="{{ route('cms.edit',['id' => $page->id]) }}">
                                            <button class="btn-sm btn-primary btn-cir" title="Edit"><i class="fas fa-pencil-alt"></i></button>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('cms.show',['id' => $page->id]) }}">
                                            <button class="btn-sm btn-warning btn-cir" title="View"><i class="fas fa-eye"></i></button>
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ url('/admin/cms/'.$page->id) }}" method="POST" onsubmit="deleteCmsPage('{{ $page->id }}', '{{ $page->title }}', event,this)">
                                        @csrf
                                            <button class="btn-sm btn-danger btn-cir" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                        </form>
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

    function deleteCmsPage(id, name, event,form)
    {
        event.preventDefault();
        swal({
            title: "Are you sure?",
            text: "You want to delete "+name+" page",
            icon: "warning",
            buttons: {
                cancel: true,
                confirm: true,
            },
            closeModal: false,
            closeModal: false,
            closeOnEsc: false,
        })
       .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                url: $(form).attr('action'),
                data: $(form).serialize(),
                type: 'DELETE',
                success: function(data) {
                    data = JSON.parse(data);
                    if(data['status']) {
                        swal({
                            title: data['message'],
                            text: "Press ok to continue",
                            icon: "success",
                            buttons: {
                                cancel: true,
                                confirm: true,
                            },
                            closeOnConfirm: false,
                            closeOnEsc: false,
                        })
                        .then((willDelete) => {
                            if (willDelete) {
                                window.location.reload();
                            }
                            });
                        } else {
                             swal("Error", data['message'], "error");
                        }
                    }
                });
            } else {
                swal("Cancelled", name+"'s page will not be deleted.", "error");
            }
        });
    }
</script>
@endsection
