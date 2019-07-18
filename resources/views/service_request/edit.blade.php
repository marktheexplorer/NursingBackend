@extends('layouts.app')<?php
$timeslot = array(
        "0000" => "00:00",
        "0030" => "00:30",
        "0100" => "01:00",
        "0130" => "01:30",
        "0200" => "02:00",
        "0230" => "02:30",
        "0300" => "03:00",
        "0330" => "03:30",
        "0400" => "04:00",
        "0430" => "04:30",
        "0500" => "05:00",
        "0530" => "05:30",
        "0600" => "06:00",
        "0630" => "06:30",
        "0700" => "07:00",
        "0730" => "07:30",
        "0800" => "08:00",
        "0830" => "08:30",
        "0900" => "09:00",
        "0930" => "09:30",
        "1000" => "10:00",
        "1030" => "10:30",
        "1100" => "11:00",
        "1130" => "11:30",
        "1200" => "12:00",
        "1230" => "12:30",
        "1300" => "13:00",
        "1330" => "13:30",
        "1400" => "14:00",
        "1430" => "14:30",
        "1500" => "15:00",
        "1530" => "15:30",
        "1600" => "16:00",
        "1630" => "16:30",
        "1700" => "17:00",
        "1730" => "17:30",
        "1800" => "18:00",
        "1830" => "18:30",
        "1900" => "19:00",
        "1930" => "19:30",
        "2000" => "20:00",
        "2030" => "20:30",
        "2100" => "21:00",
        "2130" => "21:30",
        "2200" => "22:00",
        "2230" => "22:30",
        "2300" => "23:00",
        "2330" => "23:30"  
    );?>
<style> 
    /*
Theme Name: jqueryui-com
Template: jquery
*/

a,
.title {
    color: #b24926;
}

#content a:hover {
    color: #333;
}

#banner-secondary p.intro {
    padding: 0;
    float: left;
    width: 50%;
}

#banner-secondary .download-box {
    border: 1px solid #aaa;
    background: #333;
    background: -webkit-linear-gradient(left, #333 0%, #444 100%);
    background: linear-gradient(to right, #333 0%, #444 100%);
    float: right;
    width: 40%;
    text-align: center;
    font-size: 20px;
    padding: 10px;
    border-radius: 5px;
    box-shadow: 0 0 8px rgba(0, 0, 0, 0.8);
}

#banner-secondary .download-box h2 {
    color: #71d1ff;
    font-size: 26px;
}

#banner-secondary .download-box .button {
    float: none;
    display: block;
    margin-top: 15px;
}

#banner-secondary .download-box p {
    margin: 15px 0 5px;
}

#banner-secondary .download-option {
    width: 45%;
    float: left;
    font-size: 16px;
}

#banner-secondary .download-legacy {
    float: right;
}

#banner-secondary .download-option span {
    display: block;
    font-size: 14px;
    color: #71d1ff;
}

#content .dev-links {
    float: right;
    width: 30%;
    margin: -15px -25px .5em 1em;
    padding: 1em;
    border: 1px solid #666;
    border-width: 0 0 1px 1px;
    border-radius: 0 0 0 5px;
    box-shadow: -2px 2px 10px -2px #666;
}

#content .dev-links ul {
    margin: 0;
}

#content .dev-links li {
    padding: 0;
    margin: .25em 0 .25em 1em;
    background-image: none;
}

.demo-list {
    float: right;
    width: 25%;
}

.demo-list h2 {
    font-weight: normal;
    margin-bottom: 0;
}

#content .demo-list ul {
    width: 100%;
    border-top: 1px solid #ccc;
    margin: 0;
}

#content .demo-list li {
    border-bottom: 1px solid #ccc;
    margin: 0;
    padding: 0;
    background: #eee;
}

#content .demo-list .active {
    background: #fff;
}

#content .demo-list a {
    text-decoration: none;
    display: block;
    font-weight: bold;
    font-size: 13px;
    color: #3f3f3f;
    text-shadow: 1px 1px #fff;
    padding: 2% 4%;
}

.demo-frame {
    width: 70%;
    height: 420px;
}

.view-source a {
    cursor: pointer;
}

.view-source > div {
    overflow: hidden;
    display: none;
}

@media all and (max-width: 600px) {
    #banner-secondary p.intro,
    #banner-secondary .download-box {
        float: none;
        width: auto;
    }

    #banner-secondary .download-box {
        overflow: auto;
    }
}

@media only screen and (max-width: 480px) {
    #content .dev-links {
        width: 55%;
        margin: -15px -29px .5em 1em;
        overflow: hidden;
    }
}

.ui-autocomplete-loading {background: white url("images/ui-anim_basic_16x16.gif") right center no-repeat;}
</style>
@section('content')
<div class="content-wrapper">
    <!-- START PAGE CONTENT-->
    <div class="page-heading">
        <h1 class="page-title">Edit Request</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('service_request.index') }}">Request</a></li>
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
                                <a class="nav-link active" href="#tab-2" data-toggle="tab"><i class="fas fa-plus"></i> Edit Request</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="tab-2">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach 
                                <form action="{{ route('service_request.update', ['id' => $services->id]) }}" method="post" class="form-horizontal" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                    <div class="row">
                                        <div class="col-sm-3 form-group">
                                            <label>Patient</label>
                                            <input type="text" class="form-control {{ $errors->has('user_id') ? ' is-invalid' : '' }}" name="user_id" placeholder="Patient" value="{{ $services->name }}" readonly="true" />
                                            @if ($errors->has('user_id'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('user_id') }}</strong>
                                                </span>
                                            @endif
                                        </div>   
                                        <div class="col-sm-3 form-group">
                                            <label>Caregiver</label>
                                            <input type="text" class="form-control {{ $errors->has('caregiver_id') ? ' is-invalid' : '' }}" name="caregiver_id" placeholder="caregiver_id" value="{{ $services->caregiver_id }}" readonly="true" />
                                            @if ($errors->has('caregiver_id'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('caregiver_id') }}</strong>
                                                </span>
                                            @endif
                                        </div>             
                                        <div class="col-sm-3  form-group">
                                            <label>Min Expected Bill ($)</label>
                                            <input type="number" class="form-control {{ $errors->has('min_expected_bill') ? ' is-invalid' : '' }}" placeholder="Minimum Expected Bill" name="min_expected_bill" value="{{ $services->min_expected_bill }}" min="0" id="min_price">
                                            @if ($errors->has('min_expected_bill'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('min_expected_bill') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-3  form-group">
                                            <label>Max Expected Bill ($)</label>
                                            <input type="number" class="form-control {{ $errors->has('max_expected_bill') ? ' is-invalid' : '' }}" placeholder="Max Expected Bill" name="max_expected_bill" value="{{ $services->max_expected_bill }}" min="0" id="max_price">
                                            @if ($errors->has('max_expected_bill'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('max_expected_bill') }}</strong>
                                                </span>
                                            @endif
                                        </div> 
                                    </div>
                                    <div class="row">    
                                        <div class="form-group col-sm-3" >
                                            <label>Duration</label>
                                            <input type="text" class="form-control {{ $errors->has('start_date') ? ' is-invalid' : '' }}" name="start_date" placeholder="Start from" value="{{ date('d/m/Y', strtotime($services->start_date)) }}" id="start_date" readonly="true" />
                                        </div>  
                                        <div class="form-group col-sm-3" >
                                            <label>&nbsp;</label>
                                            <input type="text" class="form-control {{ $errors->has('end_date') ? ' is-invalid' : '' }}" name="end_date" placeholder="End from" value="{{ date('d/m/Y', strtotime($services->end_date)) }}" id="end_date" readonly="true" />
                                            @if ($errors->has('end_date'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('end_date')}}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group col-sm-3" >
                                            <label>Shift Timing</label>
                                            <select name="start_time" class="form-control {{ $errors->has('start_time') ? ' is-invalid' : '' }}" >
                                                <option disabled="true" > -- Select Start time --</option>
                                                @foreach($timeslot as $key => $slot)
                                                    <option value="{{ $key }}" <?php if($services->start_time ==  $key){ echo 'selected'; } ?> >{{ $slot }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('start_time'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('start_time')}}</strong>
                                                </span>
                                            @endif
                                        </div>  
                                        <div class="form-group col-sm-3" >
                                            <label>&nbsp;</label>
                                            <select name="end_time" class="form-control {{ $errors->has('end_time') ? ' is-invalid' : '' }}" >
                                                <option disabled="true" > -- Select End time --</option>
                                                @foreach($timeslot as $key => $slot)
                                                    <option value="{{ $key }}" <?php if($services->end_time ==  $key){ echo 'selected'; } ?> >{{ $slot }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('end_time'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('end_time')}}</strong>
                                                </span>
                                            @endif
                                        </div>                                        
                                    </div>    
                                    <div class="row">    
                                        <div class="form-group col-sm-6" >
                                            <label>Street </label>
                                            <input type="text" class="form-control {{ $errors->has('location') ? ' is-invalid' : '' }}" name="location" placeholder="Location" value="{{ $services->location }}" />
                                            @if ($errors->has('location'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('location') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group col-sm-2" >
                                            <label>Zip Code </label>
                                            <input type="text" class="form-control {{ $errors->has('zipcode') ? ' is-invalid' : '' }}" name="zipcode" placeholder="Zip code" value="{{ $services->zip }}" id="zipcode" />
                                            @if ($errors->has('zipcode'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('zipcode') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group col-sm-2" >
                                            <label>City </label>
                                            <input type="text" class="form-control {{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" placeholder="city" value="{{ $services->city }}" id="city" readonly="true" />
                                            @if ($errors->has('city'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('city') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group col-sm-2" >
                                            <label>State </label>
                                            <input type="text" class="form-control {{ $errors->has('state') ? ' is-invalid' : '' }}" name="state" placeholder="state" value="{{ $services->state }}"  id="state" readonly="true" />
                                            @if ($errors->has('state'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('state') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>                                    
                                    <div class="row">
                                        <div class="col-sm-6  form-group">
                                            <label>Service</label>
                                            <select name="service" class="form-control {{ $errors->has('service') ? ' is-invalid' : '' }}" multiple="true">
                                                <option disabled="true" > -- Select Service --</option>
                                                @foreach($service_list as $srvc)
                                                    <option value="{{ $srvc->id }}" <?php if($services->service ==  $srvc->id){ echo 'selected'; } ?> >{{ $srvc->title }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('service'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('service') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group col-sm-6" >
                                            <label>Description </label>
                                            <textarea class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}" rows="4" name="description" id="description" placeholder="Description">{{$services->description}}</textarea>
                                            @if ($errors->has('description'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('description') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <input type="hidden" name="status" value="2" />
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-5 pull-right"></div>
                                        <div class="form-group col-sm-2 pull-right">
                                            <button class="btn btn-default pull-right" type="submit">Submit</button>
                                        </div>
                                        <div class="form-group col-sm-5 pull-right"></div>
                                    </div>        
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"><!--
<link rel="stylesheet" href="/resources/demos/style.css">-->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
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
        $( ".zipcodesuggest" ).on( "keydown", function( event ) {
            if(event.keyCode === $.ui.keyCode.TAB && $( this ).autocomplete( "instance" ).menu.active ) {
                event.preventDefault();
            }
        }).autocomplete({
            source: function( request, response ) {
                $.getJSON( "http://localhost/careservice/public/index.php/admin/caregiver/searchzip", {
                    term: extractLast( request.term)
                }, response );
            },
            
            search: function() {
                // custom minLength
                var term = extractLast( this.value );
                if ( term.length < 4 ) {
                    return false;
                }
            },

            focus: function() {
                // prevent value inserted on focus
                return false;
            },

            select: function( event, ui ) {
                var terms = split( this.value );
                // remove the current input
                terms.pop();
                // add the selected item
                terms.push( ui.item.value );
                // add placeholder to get the comma-and-space at the end
                terms.push( "" );
                this.value = terms.join( ", " );
                return false;
            }
        });
    }); 

    $('#zipcode').blur(function(){
        zip = $(this).val();
        $.ajax({
            url: 'locationfromzip',
            type: 'GET',
            dataType: 'json',
            data:{zipcode:zip},
            success: function (res) {
                if(res['error']){
                    $("#city").val('');
                    $("#state").val('');
                    $('#zipcode').val('');
                    swal("Oops", "Invalid Zip Code", "error");
                    //$('#zipcode').focus();
                }else{
                    $("#city").val(res['city']);
                    $("#state").val(res['state']);
                }
            }
        });
    });    

    $('#max_price').blur(function(){
        minprice = $("#min_price").val();
        maxprice = $("#max_price").val();
        if(minprice >= maxprice){
            $("#max_price").val(minprice);
        }
    });

    //date picker field
    /*$( function() {
        var dateFormat = "mm/dd/yy",
        from = $( "#start_date").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 3
        }).on( "change", function() {
            to.datepicker( "option", "minDate", getDate( this ) );
        }),
        to = $( "#end_date" ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 3
        }).on( "change", function() {
            from.datepicker( "option", "maxDate", getDate( this ) );
        });

        function getDate( element ) {
            var date;
            try {
                date = $.datepicker.parseDate( dateFormat, element.value );
            } catch( error ) {
                date = null;
            } 
            return date;
        }
    });*/
</script>
@endsection