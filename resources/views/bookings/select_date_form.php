@extends('layouts.app')

@section('content')

<!-- start library for date and time picker -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.5.10/css/bootstrap-material-design.min.css"/>
<link rel="stylesheet" href="{{ asset('admin/assets/material_datetimepicker/css/bootstrap-material-datetimepicker.css') }}" />
<link href='http://fonts.googleapis.com/css?family=Roboto:400,500' rel='stylesheet' type='text/css'>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.5.10/js/ripples.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.5.10/js/material.min.js"></script>
<script type="text/javascript" src="http://momentjs.com/downloads/moment-with-locales.min.js"></script>
<script type="text/javascript" src="{{ asset('admin/assets/material_datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>
<!-- end library for date and time picker -->

<div class="content-wrapper">
    <!-- START PAGE CONTENT-->
    <div class="page-heading">
        <h1 class="page-title">Booking Edit</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('bookings.index') }}">Bookings</a></li>
        </ol>
    </div>
    <div class="page-content fade-in-up">
        @include('flash::message')
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="ibox">
                    <div class="ibox-body">
                        <ul class="nav nav-tabs tabs-line">
                            <li class="nav-item">
                                <a class="nav-link active" href="#tab-1" data-toggle="tab"><i class="ti-bar-chart"></i> Edit</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="tab-1">
                                <form action="{{ route('bookings.today_update') }}" method="post">
                                @csrf
                                    <ul class="media-list media-list-divider m-0">
                                        <li class="media">
                                            <div class="media-img col-md-3">Client Name</div>
                                            <div class="media-body">
                                                <div class="media-heading">{{ ucfirst($booking->user->name) }}</div>
                                            </div>
                                        </li>
                                        <li class="media">
                                            <div class="media-img col-md-3">Booking For</div>
                                            <div class="media-body">
                                                <div class="media-heading">{{ $booking->relation_id == '' ? 'Myself' :  $booking->relation->name .' - '. $booking->relation->relation->title }}</div>
                                            </div>
                                        </li>
                                        <li class="media">
                                            <div class="media-img col-md-3">Booking Type</div>
                                            <div class="media-body">
                                                <div class="media-heading">{{ $booking->booking_type }}</div>
                                            </div>
                                        </li>
                                        <li class="media">
                                            <div class="media-img col-md-3">Duration</div>
                                            <div class="media-body">
                                                <div class="media-heading">{{ $booking->start_date . ' - ' .$booking->end_date }}</div>
                                            </div>
                                        </li>
                                        <li class="media">
                                            <div class="media-img col-md-3">Timings</div>
                                            <div class="media-body">
                                                <div class="media-heading">
                                                    <input type="hidden" value="{{ $booking->id }}" name="booking_id" /> 
                                                    <input type="text" id="todaystarttime" class="form-control floating-label" placeholder="Start Time" style="max-width: 120px;float: left; margin-right: 90px" name="todaystarttime" value="{{ $booking->start_time }}">
                                                    <span style="display: inline;float: left;margin-right: 90px;">To </span>
                                                    <input type="text" id="todayendtime" class="form-control floating-label" placeholder="End Time" style="max-width: 120px;float: left; margin-right: 90px" name="todayendtime" value="{{ $booking->end_time }}">
                                                    <script>
                                                        $('#todaystarttime').bootstrapMaterialDatePicker({ 
                                                            date: false,
                                                            format : 'HH:mm:ss'
                                                        }).on('change', function(e, date){
                                                            $('#todayendtime').bootstrapMaterialDatePicker('setMinDate', date);
                                                            $("#todayendtime").removeAttr("disabled");
                                                            //$("#today_submit").css('display', 'none');
                                                        });

                                                        $('#todayendtime').bootstrapMaterialDatePicker({ 
                                                            date: false,
                                                            format : 'HH:mm:ss'
                                                        }).on('change', function(e, date){
                                                            //$("#today_submit").css('display', 'inline');
                                                        });
                                                    </script>
                                                    <br/>
                                                    @if ($errors->has('todaystarttime'))
                                                        <span class="invalid-feedback" role="alert" style="display:inline;">
                                                            <strong>{{ $errors->first('todaystarttime') }}</strong>
                                                        </span>
                                                    @elseif($errors->has('todayendtime'))
                                                        <span class="invalid-feedback" role="alert" style="display:inline;">
                                                            <strong>{{ $errors->first('todayendtime') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </li>
                                        <li class="media">
                                            <div class="media-img col-md-3">Address</div>
                                            <div class="media-body">
                                                <div class="media-heading">
                                                    <input type="text" value="{{ $booking->address }}" name="address" class="form-control" style="max-width: 270px;" />
                                                    @if ($errors->has('address'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('address') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </li>
                                        <li class="media">
                                            <div class="media-img col-md-3">Service Location</div>
                                            <div class="media-body">
                                                <div class="media-heading">
                                                    <input type="text" class="form-control {{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" placeholder="city" value="{{ old('city', $booking->service_location->area) }}"  id="citysuggest" autocomplete="off"/ style="max-width: 270px;">
                                                    @if ($errors->has('city'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('city') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </li>
                                        <li class="media">
                                            <div class="media-img col-md-3">State</div>
                                            <div class="media-body">
                                                <div class="media-heading">
                                                    <select name="state" class="form-control {{ $errors->has('state') ? ' is-invalid' : '' }}" readonly="true" id="state" style="max-width:270px;">
                                                        <option disabled="true" selected=""> -- Select State --</option>
                                                        @foreach($us_state as $key => $state_code)
                                                            <option  value="{{ old('state', $booking->state) }}"    >{{ ucwords($state_code)}}</option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('state'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('state') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </li>
                                        <li class="media">
                                            <div class="media-img col-md-3">Zipcode</div>
                                            <div class="media-body">
                                                <div class="media-heading">
                                                    <input type="text" class="form-control {{ $errors->has('zipcode') ? ' is-invalid' : '' }}" name="zipcode" placeholder="Zip code" value="{{ old('zipcode', $booking->zipcode) }}" id="zipcode" style="max-width: 270px;" />
                                                    @if ($errors->has('zipcode'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('zipcode') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </li>        
                                        <li>
                                            <div class="media-img col-md-12" style="text-align: center;">
                                                <input class="form-control btn-sm btn-primary" style="display:inline; max-width: 70px; padding:0px;background-color: #3498db; background-image: none; " type="submit" value="Submit" id="today_submit"/>&nbsp;&nbsp;&nbsp;&nbsp;<!--
                                                <button onclick="$('#today_div').toggle();$('#editbtn').css('display', 'inline');" class="form-control btn-sm btn-primary" style="max-width: 70px;padding:0px;display: inline;background-color: #3498db;background-image: none;" type="button">Cancel</button> -->
                                            </div>    
                                        </li>
                                    </ul>
                                </form>    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer-scripts')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.1.62/jquery.inputmask.bundle.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>
<script type="text/javascript">
    $(document).ready( function () {
        $('#data-table').DataTable();
    });
</script>
<script>
    $(function(){
        function split( val ) {
            return val.split( /,\s*/ );
        }

        function extractLast( term ) {
            //return split( term ).pop();
            temp = $.trim($("#service_zipcode").val());
            fnd = ','
            if(temp.indexOf(fnd) != -1){
                term =  temp+" "+term;
            }
            console.log(term);
            return term;
        }

        // don't navigate away from the field on tab when selecting an item
        $( "#citysuggest" ).on( "keydown", function( event ) {
            if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active){
                event.preventDefault();
            }
        }).autocomplete({
            source: function( request, response ) {
                $.getJSON( "{{ env('APP_URL') }}admin/bookings/search_service_location", {
                    term: request.term
                }, response );
            },

            search: function() {
                // custom minLength
                var term = this.value;
                if ( term.length < 2){
                    return false;
                }
            },

            focus: function() {
                // prevent value inserted on focus
                return false;
            },

            select: function( event, ui ) {
                $( "#citysuggest" ).val(ui.item.value)
                $( "#citysuggest" ).autocomplete("close");

                //remove all options from select box
                $("#state").find("option:gt(0)").remove();
                $("#state").prop("selectedIndex", 0);
                setstateoptions();
                return false;
            }
        });
    });

    function setstateoptions(){
        zip = $("#citysuggest").val();
        $.ajax({
            url: '{{ env('APP_URL') }}admin/caregiver/statefromcity',
            type: 'GET',
            dataType: 'json',
            data:{term:zip},
            success: function (res) {
                if(res['error']){
                    //swal("Oops", "Invalid City", "error");
                    $("#citysuggest").val('');
                    $("#citysuggest").focus();
                }else{
                    /*$.each(res['list'], function( index, value ) {
                        //alert( index + ": " + value );
                        $('#state').append($("<option></option>").attr(value, value).text(value));
                    });*/
                }
            }
        });
    }
</script>
@endsection