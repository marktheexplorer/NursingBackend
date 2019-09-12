@extends('layouts.app')
@section('content')
<style type="text/css">
    .ui-autocomplete{max-height: 300px !important;overflow-y: scroll !important;overflow-x: hidden !important;}
</style>
<div class="content-wrapper">
    <!-- START PAGE CONTENT-->
    <div class="page-heading">
        <h1 class="page-title">Edit Caregiver</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('caregiver.index') }}">Caregiver</a></li>
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
                                <a class="nav-link active" href="#tab-2" data-toggle="tab"><i class="fas fa-plus"></i> Edit Caregiver</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="tab-2">
                                <form action="{{ route('caregiver.update', ['id' => $user->id]) }}" method="post" class="form-horizontal" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                    <div class="card">
                                        <div class="card-header" style="background-color: #ddd;">
                                            <h5>Personal Info</h5>
                                        </div>
                                        <div class="card-body">
                                              <div class="row">
                                                <div class="form-group col-sm-12 center" style="text-align: center;">
                                                   <span style="text-align: center;position: absolute;top: 120px;margin-left: 101px;" id="upload_image_icon" onclick="event.preventDefault();">
                                                     <button class="btn-sm btn-primary btn-cir" title="Edit"><i class="fas fa-pencil-alt"></i></button>
                                                   </span>
                                                     <img class="img-circle" src="<?php if($user->profile_image){ echo asset(config($user->profile_image)); }else{ echo asset('admin/assets/img/admin-avatar.png') ;} ?>" style="width:150px;height:150px;"/>
                                                       <input type="file" id="upload_image" name="profile_image" value="{{ old('profile_image') }}" onchange="readURL(this);" accept="image/*"/ style="display:none;"><br/><br/>
                                                       <span class="text-danger image_error">
                                                       <strong>{{ $errors->has('profile_image')?$errors->first('profile_image'):'' }}</strong>
                                                       </span>
                                                 </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-3 form-group">
                                                    <label>First Name</label>
                                                    <input type="text" class="form-control {{ $errors->has('first_name') ? ' is-invalid' : '' }}" name="first_name" placeholder="First Name" value="{{ old('first_name', $user->first_name) }}" />
                                                    @if ($errors->has('first_name'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('first_name') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>Middle Name</label>
                                                    <input type="text" class="form-control {{ $errors->has('middle_name') ? ' is-invalid' : '' }}" name="middle_name" placeholder="Middle Name" value="{{ old('middle_name', $user->middle_name) }}" />
                                                    @if ($errors->has('middle_name'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('middle_name') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>Last Name</label>
                                                    <input type="text" class="form-control {{ $errors->has('last_name') ? ' is-invalid' : '' }}" name="last_name" placeholder="Last Name" value="{{ old('last_name', $user->last_name) }}" />
                                                    @if ($errors->has('last_name'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('last_name') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="col-sm-3  form-group">
                                                    <label>Gender</label>
                                                    <select name="gender" class="form-control {{ $errors->has('gender') ? ' is-invalid' : '' }}">
                                                        <option disabled="true" selected="true"> -- Select Gender --</option>
                                                        <option value="Male" {{ old('gender', $user->gender) == 'Male' ? 'selected':'' }} >Male</option>
                                                        <option value="Female" {{ old('gender', $user->gender) == 'Female' ? 'selected':'' }}>Female</option>
                                                    </select>
                                                    @if ($errors->has('gender'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('gender') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-4 form-group">
                                                    <label>Email</label>
                                                    <input type="text" name="email" placeholder="Email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email', $user->email) }}" readonly/>
                                                    @if ($errors->has('email'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('email') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="col-sm-4  form-group">
                                                    <label>Mobile Number</label>
                                                    <input type="text" class="form-control {{ $errors->has('mobile_number') ? ' is-invalid' : '' }}" placeholder="Mobile Number" name="mobile_number" value="{{ old('mobile_number', $user->mobile_number) }}" id="mobile_number">
                                                    @if ($errors->has('mobile_number'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('mobile_number') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="form-group col-sm-4" >
                                                    <label>Date of Birth</label>
                                                    <input type="text" class="form-control {{ $errors->has('dob') ? ' is-invalid' : '' }}" name="dob" placeholder="Date of Birth" value="{{ old('dob', date('d/m/Y', strtotime($user->dob))) }}" id="dob"/>
                                                    @if ($errors->has('dob'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('dob')}}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row">

                                                <div class="col-sm-4 form-group">
                                                    <label>Height</label>
                                                    <select name="height" class="form-control {{ $errors->has('height') ? ' is-invalid' : '' }}">
                                                        <option disabled="true" selected="true"> -- Select Height --</option>
                                                        @foreach(PROFILE_HEIGHT as $val)
                                                            <option value="{{ $val }}" <?php if(old('height', $user->height) == $val){ echo 'selected'; } ?>>{{$val}}</option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('height'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('height') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="col-sm-4 form-group">
                                                    <label>Weight</label>
                                                    <select name="weight" class="form-control {{ $errors->has('weight') ? ' is-invalid' : '' }}">
                                                        <option disabled="true" selected="true"> -- Select Weight --</option>
                                                        @foreach(PROFILE_WEIGHT as $val)
                                                            <option value="{{ $val }}" <?php if(old('weight', $user->weight) == $val){ echo 'selected'; } ?>>{{$val}}</option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('weight'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('weight') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                                 <div class="col-sm-4  form-group">
                                                    <label>Language</label>
                                                    <select name="language" class="form-control {{ $errors->has('language') ? ' is-invalid' : '' }}">
                                                        <option disabled="true" selected="true"> -- Select Language --</option>
                                                        @foreach(PROFILE_LANGUAGE as $val)
                                                            <option value="{{ $val }}" <?php if($val == old('language', $user->language)){ echo 'selected'; } ?>>{{$val}}</option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('language'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('language') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-sm-6" >
                                                    <label>Street </label>
                                                    <input type="text" class="form-control {{ $errors->has('location') ? ' is-invalid' : '' }}" name="location" placeholder="Location" value="{{ old('location', $user->location) }}" />
                                                    @if ($errors->has('location'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('location') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="form-group col-sm-2" >
                                                    <label>City </label>
                                                    <input type="text" class="form-control {{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" placeholder="city" value="{{ old('city', $user->city) }}"  id="citysuggest" autocomplete="off"/>
                                                    @if ($errors->has('city'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('city') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="form-group col-sm-2" >
                                                    <label>state </label>
                                                    <select name="state" class="form-control {{ $errors->has('state') ? ' is-invalid' : '' }}" readonly="true" id="state"">
                                                        <option disabled="true" selected=""> -- Select State --</option>
                                                        @foreach($city_state as $row)
                                                            <option  value="{{ $row->state_code }}" <?php if($row->state_code == old('state', $user->state)){ echo 'selected'; } ?> >{{ $row->state_code }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('state'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('state') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="form-group col-sm-2" >
                                                    <label>Zip Code </label>
                                                    <input type="text" class="form-control {{ $errors->has('zipcode') ? ' is-invalid' : '' }}" name="zipcode" placeholder="Zip code" value="{{ old('zipcode', $user->zipcode) }}" id="zipcode" readonly />
                                                    @if ($errors->has('zipcode'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('zipcode') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div><br/>
                                    <div class="card">
                                        <div class="card-header" style="background-color: #ddd;">
                                            <h5>Service Info</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-6  form-group">
                                                    <label>Min Price</label>
                                                    <input type="text" class="form-control {{ $errors->has('min_price') ? ' is-invalid' : '' }}" placeholder="Minimum" name="min_price" value="{{ old('min_price', $user->min_price) }}" min="0" id="min_price" onkeypress="return validateFloatKeyPress(this,event);">
                                                    @if ($errors->has('min_price'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('min_price') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="col-sm-6  form-group">
                                                    <label>Max Price</label>
                                                    <input type="text" class="form-control {{ $errors->has('max_price') ? ' is-invalid' : '' }} " placeholder="Price" name="max_price" value="{{ old('max_price', $user->max_price) }}" min="0" id="max_price" onkeypress="return validateFloatKeyPress(this,event);">
                                                    @if ($errors->has('max_price'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('max_price') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6  form-group">
                                                    <label>Discipline</label><?php
                                                        $qualification_array = array();
                                                        if(!empty(old('qualification'))){
                                                            $qualification_array = old('qualification');
                                                        }else{
                                                            foreach($user->qualification as $qlf){
                                                                $qualification_array[] = $qlf->id;
                                                            }
                                                        } ?>
                                                    <select name="qualification[]" class="form-control {{ $errors->has('qualification') ? ' is-invalid' : '' }}" multiple="true">
                                                        <option disabled="true" > -- Select Discipline --</option>
                                                        @foreach($qualification as $qlf)
                                                            <option value="{{ $qlf->id }}" <?php if(in_array($qlf->id, $qualification_array)){ echo 'selected'; } ?> >{{ $qlf->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('qualification'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('qualification') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="col-sm-6  form-group">
                                                    <label>Service</label><?php
                                                        $service_array = array();
                                                        if(!empty(old('service'))){
                                                            $service_array = old('service');
                                                        }else{
                                                            foreach($user->services as $qlf){
                                                                $service_array[] = $qlf->id;
                                                            }
                                                        } ?>
                                                    <select name="service[]" class="form-control {{ $errors->has('service') ? ' is-invalid' : '' }}" multiple="true">
                                                        <option disabled="true" > -- Select Service --</option>
                                                        @foreach($service_list as $srvc)
                                                            <option value="{{ $srvc->id }}" <?php if(in_array($srvc->id, $service_array)){ echo 'selected'; } ?> >{{ $srvc->title }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('service'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('service') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-sm-12" >
                                                    <label>Service Area </label>
                                                    <select name="service_area[]" class="form-control {{ $errors->has('service_area') ? ' is-invalid' : '' }} select2" multiple="multiple" id="servicearea">
                                                        @foreach($service_area_list as $row)
                                                            <option value="{{ $row->id }}" <?php if(in_array($row->id, old('service_area', $user->service_area)) ){ echo 'selected'; } ?>>
                                                                {{ $row->area }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('service_area'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('service_area') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-sm-12" >
                                                    <label>Non Service Area </label>
                                                    <select name="non_service_area[]" class="form-control {{ $errors->has('non_service_area') ? ' is-invalid' : '' }} select2" multiple="multiple" id="nonservicearea">
                                                        @foreach($service_area_list as $row)
                                                            <option value="{{ $row->id }}" <?php if(in_array($row->id, old('non_service_area', $user->non_service_area))){ echo 'selected'; } ?>>
                                                                {{ $row->area }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('non_service_area'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('non_service_area') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-sm-12" >
                                                    <label>Additional Information </label>
                                                    <textarea class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}" rows="5" name="description" id="description" placeholder="Description">{{ old('description', $user->description) }}</textarea>
                                                    @if ($errors->has('description'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('description') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-5 pull-right"></div>
                                        <div class="form-group col-sm-2 pull-right"><br/>
                                            <button class="btn btn-primary pull-right" type="submit">Submit</button>
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

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.1.62/jquery.inputmask.bundle.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>
<script>
    $(function(){
        $("#servicearea").select2({
            placeholder: {
                id: '-1', // the value of the option
                text: ' -- Select Service Area --'
            }
        }).on("change", function (e) {
            // show data in separate div when item is selected
            $("#nonservicearea").select2('destroy').val("").select2();
            $('#nonservicearea option').removeAttr('disabled').removeProp('disabled');

            var myTest = new Array();
            myTest = $("#servicearea").val();
            $.each(myTest, function( key, value){
                $("#nonservicearea option[value="+value+"]").attr('disabled',true);
            });
        });

        $("#nonservicearea").select2({
            placeholder: {
                id: '-1', // the value of the option
                text: '-- Select Non Service Area --'
            }
        });

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
                $.getJSON( "{{ env('APP_URL') }}admin/caregiver/searchcity", {
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
                    $.each(res['list'], function( index, value ) {
                        //alert( index + ": " + value );
                        $('#state').append($("<option></option>").attr(value, value).text(value));
                    });
                }
            }
        });
        $("#state").attr("readonly", false);
    }

    $("#state").change(function () {
        stateoption = $("#state option:selected").val();
        cityoption = $("#citysuggest").val();
        $.ajax({
            url: '{{ env('APP_URL') }}admin/caregiver/getzip',
            type: 'GET',
            data:{city:cityoption, state:stateoption},
            success: function (res) {
                $("#zipcode").val(res);
            }
        });
    })

    $('#max_price').blur(function(){
        minprice = $("#min_price").val();
        maxprice = $("#max_price").val();
        if(minprice >= maxprice){
            $("#max_price").val(minprice);
        }
    });

    $("#dob").keydown(function(e){
        var key = event.keyCode;
        if(key != 9){
            e.preventDefault();
        }
    });

    //date picker field
    $( function(){
        var maxBirthdayDate = new Date();
        maxBirthdayDate.setFullYear( maxBirthdayDate.getFullYear() - 18,11,31);
        $( "#dob" ).datepicker({
            changeMonth: true,
            changeYear: true,
            maxDate: maxBirthdayDate,
            yearRange: '1919:'+maxBirthdayDate.getFullYear(),
        });
    });

    /*Validation for mobile number format*/
    var phones = [{ "mask": "###-###-####"}];
    $('#mobile_number').inputmask({
        mask: phones,
        greedy: false,
        definitions: { '#': { validator: "[0-9]", cardinality: 1}},
    });

    /*Validation for $sign in price field*/
    $('input.price').keyup(function() {
       $(this).val(function(i,v) {
         return '$' + v.replace('$',''); //remove exisiting, add back.
       });
     });

    /*Validation for price field for 2 decimal places*/
    function validateFloatKeyPress(el, evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        var number = el.value.split('.');
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        //just one dot
        if(number.length>1 && charCode == 46){
             return false;
        }
        //get the carat position
        var caratPos = getSelectionStart(el);
        var dotPos = el.value.indexOf(".");
        if( caratPos > dotPos && dotPos>-1 && (number[1].length > 1)){
            return false;
        }
        return true;
    }
    function getSelectionStart(o) {
      if (o.createTextRange) {
        var r = document.selection.createRange().duplicate()
        r.moveEnd('character', o.value.length)
        if (r.text == '') return o.value.length
        return o.value.lastIndexOf(r.text)
      } else return o.selectionStart
    }

    function generatepassword(){
        $("#issentmail").val('0');
        newpassword = Math.random().toString(36).substr(2, 14);
        $("#newpassword").val(newpassword);
        $("#newpassword").attr("readonly", false);
    }

    function setmail(){
        newpassword = Math.random().toString(36).substr(2, 14);
        $("#newpassword").val(newpassword);
        $("#newpassword").attr("readonly", true);
        $("#issentmail").val('1');
    }

    $("#newpassword").keydown(function(e){
        var key = event.keyCode;
        if(key != 9){
            e.preventDefault();
        }
    });

    $("#upload_image_icon").click(function(){
        $("#upload_image").click();
        //e.preventDefault();
    });
    function readURL(input) {
      if (input.files && input.files[0]) {
        if($.inArray(input.files[0].type, ['image/png','image/jpg','image/jpeg']) == 0){
          console.log(input.files[0].type);
            var reader = new FileReader();
            reader.onload = function (e) {
              $('.img-circle').attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
      }
    }

    (function($) {
    $.fn.checkFileType = function(options) {
        var defaults = {
            allowedExtensions: [],
            success: function() {},
            error: function() {}
        };
        options = $.extend(defaults, options);

        return this.each(function() {

            $(this).on('change', function() {
                var value = $(this).val(),
                    file = value.toLowerCase(),
                    extension = file.substring(file.lastIndexOf('.') + 1);

                if ($.inArray(extension, options.allowedExtensions) == -1) {
                    options.error();
                    $(this).focus();
                } else {
                    options.success();

                }

            });

        });
    };

})(jQuery);

$(function() {
    $('#upload_image').checkFileType({
        allowedExtensions: ['jpg', 'jpeg','png'],
        success: function() {
            $('.image_error').text('');
        },
        error: function() {
          $('#upload_image').val('')
          $('.image_error').text('Please upload a jpg , jpeg or png image .');
        }
    });

});
</script>
@endsection
