@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="page-heading">
        <h1 class="page-title">FAQs</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
            </li>
            <li class="breadcrumb-item">Faqs</li>
        </ol>
    </div>
    @include('flash::message')
    <div class="page-content fade-in-up">
        <a href="{{ route('faqs.create') }}"><button class="btn btn-info "><i class="fas fa-plus"></i> Add</button></a>
        <div class="ibox">
           
            <div class="ibox-body">
                <table class="table table-striped table-bordered table-hover" id="data-table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
              				<th>Title</th>
                            <th>Created At</th>
	                  		<th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                 	@foreach($faqs as $key => $faq)
	            		<tr>
	            			<td>{{ ++$key }}</td>
	              			<td>{{ $faq->question }}</td>
                            <td>{{ date_format(date_create($faq->created_at) , 'd M ,y') }}
	              			<td>
	              				<ul class="actions-menu">
                                    <li>
                                        <a href="{{ route('faqs.edit',['id' => $faq->id]) }}">
                                            <button class="btn-sm btn-primary btn-cir" title="Edit"><i class="fas fa-pencil-alt"></i></button>
                                        </a>
                                    </li>
	              					<li>
	              						<a href="{{ route('faqs.show',['id' => $faq->id]) }}">
	              							<button class="btn-sm btn-warning btn-cir" title="View"><i class="fas fa-eye"></i></button>
	              						</a>
	              					</li>
	              					<li>
	              						<form action="{{ url('/admin/faqs/'.$faq->id) }}" method="POST" onsubmit="deleteFaq('{{ $faq->id }}', '{{ $faq->question }}', event,this)">
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

	function deleteFaq(id, name, event,form)
    {
        event.preventDefault();
        swal({
            title: "Are you sure?",
            text: "You want to delete "+name+" faq",
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
                swal("Cancelled", name+"'s faq will not be deleted.", "error");
            }
        });
    }
</script>
@endsection
