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
                                            <h5>Personal Information</h5>
                                        </div>
                                        <div class="card-body">
                                              <div class="row">
                                                <div class="form-group col-sm-12 center" style="text-align: center;">
                                                   <span style="text-align: center;position: absolute;top: 120px;margin-left: 101px;" id="upload_image_icon" onclick="event.preventDefault();">
                                                     <button class="btn-sm btn-primary btn-cir" title="Edit"><i class="fas fa-pencil-alt"></i></button>
                                                   </span>
                                                     <img class="img-circle" src="<?php if($user->profile_image){ echo asset(config('image.user_image_url').$user->profile_image); }else{ echo asset('admin/assets/img/admin-avatar.png') ;} ?>" style="width:150px;height:150px;"/>
                                                       <input type="file" id="upload_image" name="profile_image" value="{{ old('profile_image') }}" onchange="readURL(this);" accept="image/*"/ style="display:none;"><br/><br/>
                                                       <span class="text-danger image_error">
                                                       <strong>{{ $errors->has('profile_image')?$errors->first('profile_image'):'' }}</strong>
                                                       </span>
                                                 </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-3 form-group">
                                                    <label>First Name</label>
                                                    <input type="text" class="form-control {{ $errors->has('f_name') ? ' is-invalid' : '' }}" name="f_name" placeholder="First Name" value="{{ old('f_name', $user->f_name) }}" />
                                                    @if ($errors->has('f_name'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('f_name') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>Middle Name</label>
                                                    <input type="text" class="form-control {{ $errors->has('m_name') ? ' is-invalid' : '' }}" name="m_name" placeholder="Middle Name" value="{{ old('m_name', $user->m_name) }}" />
                                                    @if ($errors->has('m_name'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('m_name') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>Last Name</label>
                                                    <input type="text" class="form-control {{ $errors->has('l_name') ? ' is-invalid' : '' }}" name="l_name" placeholder="Last Name" value="{{ old('l_name', $user->l_name) }}" />
                                                    @if ($errors->has('l_name'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('l_name') }}</strong>
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
                                                <div class="col-sm-3 form-group">
                                                    <label>Email</label>
                                                    <input type="text" name="email" placeholder="Email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email', $user->email) }}" />
                                                    @if ($errors->has('email'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('email') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>     
                                                 <div class="col-sm-3  form-group">
                                                    <label>Country Code</label>
                                                    <select name="country_code" class="form-control {{ $errors->has('country_code') ? ' is-invalid' : '' }}">
                                                        <option disabled="true" selected="true"> -- Select --</option>
                                                        <option value="1" @php if(old('country_code', $user->country_code) == '1'){ echo 'selected'; } @endphp >+1</option>
                                                        <option value="91" @php if(old('country_code', $user->country_code) == '91'){ echo 'selected'; } @endphp >+91</option>
                                                    </select>
                                                    @if ($errors->has('country_code'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('country_code') }}</strong>
                                                         </span>
                                                    @endif
                                                </div>
                                                <div class="col-sm-3  form-group">
                                                    <label>Mobile Number</label>
                                                    <input type="text" class="form-control {{ $errors->has('mobile_number') ? ' is-invalid' : '' }}" placeholder="Mobile Number" name="mobile_number" value="{{ old('mobile_number', $user->mobile_number) }}" id="mobile_number">
                                                    @if ($errors->has('mobile_number'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('mobile_number') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="form-group col-sm-3" >
                                                    <label>Date of Birth</label>
                                                    <input type="text" class="form-control {{ $errors->has('dob') ? ' is-invalid' : '' }}" name="dob" placeholder="Date of Birth" value="{{ old('dob', $user->dob) }}" id="dob"/>
                                                    @if ($errors->has('dob'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('dob')}}</strong>
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="col-sm-3 form-group">
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
                                                <div class="col-sm-3 form-group">
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
                                                 <div class="col-sm-3  form-group">
                                                    <label>Language</label>
                                                    <select name="language[]" class="form-control {{ $errors->has('language') ? ' is-invalid' : '' }} multiple" multiple="multiple">
                                                        @foreach(PROFILE_LANGUAGE as $val)
                                                            <option value="{{ $val }}" {{ ($user->language !== NULL)?(in_array($val, unserialize($user->language))) ? 'selected' :'' : '' }}>{{$val}}</option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('language'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('language') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>  
                                                <div class="form-group col-sm-3" >
                                                    <label>Street </label>
                                                    <input type="text" class="mapControls form-control {{ $errors->has('street') ? ' is-invalid' : '' }}" name="street" placeholder="Street" value="{{ old('street', $user->street) }}" id="searchMapInput" />
                                                    @if ($errors->has('street'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('street') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="form-group col-sm-4" >
                                                    <label>County </label>
                                                    <input type="text" class="form-control {{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" placeholder="County" value="{{ old('city', $user->city) }}"  id="city-span" readonly/>
                                                    @if ($errors->has('city'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('city') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="form-group col-sm-4" >
                                                    <label>state </label>
                                                    <input type="text" class="form-control {{ $errors->has('state') ? ' is-invalid' : '' }}" name="state" placeholder="State" value="{{ old('state', $user->state) }}"  id="state-span" readonly/>
                                                    @if ($errors->has('state'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('state') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="form-group col-sm-4" >
                                                    <label>Zip Code </label>
                                                    <input type="text" class="form-control {{ $errors->has('zipcode') ? ' is-invalid' : '' }}" name="zipcode" placeholder="Zip Code" value="{{ old('zipcode', $user->zipcode) }}" id="zipcode-span" readonly />
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
                                            <h5>Service Information</h5>
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
                                                    <select name="qualification[]" class="form-control {{ $errors->has('qualification') ? ' is-invalid' : '' }} select2" id="discipline"  multiple="true">
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
                                                    <select name="service[]" class="form-control {{ $errors->has('service') ? ' is-invalid' : '' }} select2" id="service" multiple="true">
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
                                                            @if(count($errors) > 0)
                                                                @if(empty(old('service_area')))
                                                                    @if(empty(old('non_service_area')))
                                                                        <option value="{{ $row->id }}" >
                                                                            {{ $row->area }}
                                                                        </option>
                                                                     @else
                                                                         <option value="{{ $row->id }}" <?php if(in_array($row->id, old('non_service_area', $user->non_service_area))){ echo 'disabled'; } ?>>
                                                                            {{ $row->area }}
                                                                        </option>
                                                                     @endif   
                                                                @else
                                                                    @if(empty(old('non_service_area')))
                                                                        <option value="{{ $row->id }}" <?php if(in_array($row->id, old('service_area', $user->service_area))){ echo 'selected'; } ?> >
                                                                            {{ $row->area }}
                                                                        </option>
                                                                    @else
                                                                        <option value="{{ $row->id }}" <?php if(in_array($row->id, old('service_area', $user->service_area))){ echo 'selected'; } ?> <?php if(in_array($row->id, old('non_service_area', $user->non_service_area))){ echo 'disabled'; } ?>>
                                                                            {{ $row->area }}
                                                                        </option>
                                                                    @endif    

                                                                @endif    
                                                            @else
                                                                <option value="{{ $row->id }}" <?php if(in_array($row->id, $user->service_area)){ echo 'selected'; } ?> >
                                                                    {{ $row->area }}
                                                                </option>
                                                            @endif    
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
                                                    <select name="non_service_area[]" class="form-control {{ $errors->has('non_service_area') ? ' is-invalid' : '' }} select2" multiple="multiple" id="nonservicearea" placeholder="dddddd">
                                                        @foreach($service_area_list as $row)
                                                            @if(count($errors) > 0)
                                                                @if(empty(old('non_service_area')))
                                                                    @if(empty(old('service_area')))
                                                                        <option value="{{ $row->id }}" >
                                                                            {{ $row->area }}1
                                                                        </option>
                                                                    @else
                                                                        <option value="{{ $row->id }}" <?php if(in_array($row->id, old('service_area', $user->service_area))){ echo 'disabled'; } ?>>
                                                                            {{ $row->area }}
                                                                        </option>
                                                                    @endif    
                                                                @else
                                                                    @if(empty(old('service_area')))
                                                                        <option value="{{ $row->id }}" <?php if(in_array($row->id, old('non_service_area', $user->non_service_area))){ echo 'selected'; }?> >
                                                                            {{ $row->area }}
                                                                        </option>
                                                                    @else
                                                                        <option value="{{ $row->id }}" <?php if(in_array($row->id, old('non_service_area', $user->non_service_area))){ echo 'selected'; } ?>  <?php if(in_array($row->id, old('service_area', $user->service_area))){ echo 'disabled'; } ?> >
                                                                        {{ $row->area }}
                                                                    </option>
                                                                    @endif 
                                                                @endif 
                                                            @else
                                                                <option value="{{ $row->id }}" <?php if(in_array($row->id, $user->non_service_area)){ echo 'selected'; } ?> >
                                                                    {{ $row->area }}
                                                                </option>
                                                            @endif    
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
                                                    <textarea class="form-control {{ $errors->has('additional_info') ? ' is-invalid' : '' }}" rows="5" name="additional_info" id="additional_info" placeholder="Description">{{ old('additional_info', $user->additional_info) }}</textarea>
                                                    @if ($errors->has('additional_info'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('additional_info') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-5 pull-right"></div>
                                        <div class="form-group col-sm-3 pull-right"><br/>
                                            <button class="btn btn-primary pull-right" type="submit">Submit</button>
                                            <input type="reset" value="Cancel" class="btn btn-danger" onclick="window.location.reload()">
                                        </div>
                                        <div class="form-group col-sm-4 pull-right"></div>
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
<script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_API_KEY')}}&libraries=places&callback=initMap" async defer></script>
<script>
    function initMap() {
        var options = {
          componentRestrictions: {country: "us"}
        };
        var input = document.getElementById('searchMapInput');
        var autocomplete = new google.maps.places.Autocomplete(input,options);

        autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();
            address_components = place.address_components.reverse();
            $('#searchMapInput').val(place.name);

            for (let i = 0; i < address_components.length; i++) {
                if (address_components[i].types[0] == 'postal_code') {
                    $('#zipcode-span').val(address_components[i].long_name);
                }
                else if (address_components[i].types[0] == 'administrative_area_level_1') {
                    $('#state-span').val(address_components[i].long_name);
                }
                else if (address_components[i].types[0] == 'administrative_area_level_2') {
                    $('#city-span').val(address_components[i].long_name);
                }
            }
        });
    }
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

        $("#discipline").select2({
            placeholder: {
                id: '-1', // the value of the option
                text: ' -- Select Discipline --'
            }
        });
        
        $(".multiple").select2({
            placeholder: {
                id: '-1', // the value of the option
                text: ' -- Select --'
            }
        });

        $("#service").select2({
            placeholder: {
                id: '-1', // the value of the option
                text: ' -- Select Service --'
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
    });


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

    (function($){
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
        allowedExtensions: ['jpg', 'jpeg', 'png'],
        success: function() {
            $('.image_error').text('');
        },
        error: function() {
          $('#upload_image').val('')
          $('.image_error').text('Please upload a jpg, jpeg or png image only');
        }
    });

});
</script>
@endsection
