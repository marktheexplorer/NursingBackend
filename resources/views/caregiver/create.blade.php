@extends('layouts.app')
@section('content')
<div class="content-wrapper">
    <!-- START PAGE CONTENT-->
    <div class="page-heading">
        <h1 class="page-title">Add Caregiver</h1>
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
                                <a class="nav-link active" href="#tab-2" data-toggle="tab"><i class="fas fa-plus"></i> Add Caregiver</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="tab-2">
                                <form action="{{ route('caregiver.store') }}" method="post" class="form-horizontal" enctype="multipart/form-data">
                                @csrf
                                    <div class="row">
                                        <div class="form-group col-sm-6" >
                                            <label>Profile Image</label><br/>
                                            <input type="file" class="{{ $errors->has('profile_image') ? ' is-invalid' : '' }}" name="profile_image" placeholder="Profile Image" value="{{ old('profile_image') }}" accept="image/*"/ style="padding-left:0px;">
                                            @if ($errors->has('profile_image'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('profile_image') }}</strong>
                                                </span>
                                            @endif
                                        </div>  
                                        <div class="col-sm-2 form-group">
                                            <label>First Name</label>
                                            <input type="text" class="form-control {{ $errors->has('fname') ? ' is-invalid' : '' }}" name="fname" placeholder="First Name" value="{{ old('fname') }}" />
                                            @if ($errors->has('fname'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('fname') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-2 form-group">
                                            <label>Middle Name</label>
                                            <input type="text" class="form-control {{ $errors->has('mname') ? ' is-invalid' : '' }}" name="mname" placeholder="Middle Name" value="{{ old('mname') }}" />
                                            @if ($errors->has('mname'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('mname') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-2 form-group">
                                            <label>Last Name</label>
                                            <input type="text" class="form-control {{ $errors->has('lname') ? ' is-invalid' : '' }}" name="lname" placeholder="Last Name" value="{{ old('lname') }}" />
                                            @if ($errors->has('lname'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('lname') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">    
                                        <div class="col-sm-3  form-group">
                                            <label>Email</label>
                                            <input type="text" name="email" placeholder="Email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" />
                                            @if ($errors->has('email'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-3  form-group">
                                            <label>Mobile Number</label>
                                            <input type="text" class="form-control {{ $errors->has('mobile_number') ? ' is-invalid' : '' }}" placeholder="Mobile Number" name="mobile_number" value="{{ old('mobile_number')}}" id="mobile_number">
                                            @if ($errors->has('mobile_number'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('mobile_number') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group col-sm-3" >
                                            <label>Password</label>
                                            <input type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="Password" value="{{ old('password') }}"/>
                                            @if ($errors->has('password'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-3  form-group">
                                            <label>Gender</label>
                                            <select name="gender" class="form-control {{ $errors->has('gender') ? ' is-invalid' : '' }}">
                                                <option disabled="true" selected="true"> -- Select Gender --</option>
                                                <option value="Male" {{ old('gender') == 'Male' ? 'selected':'' }}>Male</option>
                                                <option value="Female" {{ old('gender') == 'Female' ? 'selected':'' }}>Female</option>
                                            </select>
                                            @if ($errors->has('gender'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('gender') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group col-sm-4" >
                                            <label>Date of Birth</label>
                                            <input type="text" class="form-control {{ $errors->has('dob') ? ' is-invalid' : '' }}" name="dob" placeholder="Date of Birth" value="{{ old('dob') }}" id="dob" autocomplete="off" />
                                            @if ($errors->has('dob'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('dob')}}</strong>
                                                </span>
                                            @endif
                                        </div>
                                         <div class="col-sm-2  form-group">
                                            <label>Height</label>
                                            <select name="height" class="form-control {{ $errors->has('height') ? ' is-invalid' : '' }}">
                                                <option disabled="true" selected="true"> -- Select Height --</option>
                                                @foreach(PROFILE_HEIGHT as $val)
                                                    <option value="{{ $val }}">{{$val}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('height'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('height') }}</strong>
                                                </span>
                                            @endif
                                        </div>

                                        <div class="col-sm-2 form-group">
                                            <label>Weight</label>
                                            <select name="weight" class="form-control {{ $errors->has('weight') ? ' is-invalid' : '' }}">
                                                <option disabled="true" selected="true"> -- Select Weight --</option>
                                                @foreach(PROFILE_WEIGHT as $val)
                                                    <option value="{{ $val }}">{{$val}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('weight'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('weight') }}</strong>
                                                </span>
                                            @endif
                                        </div>

                                        <div class="col-sm-2  form-group">
                                            <label>Min Price</label>
                                            <input type="number" class="form-control {{ $errors->has('min_price') ? ' is-invalid' : '' }}" placeholder="Minimum" name="min_price" value="{{ old('min_price')}}" min="0" id="min_price">
                                            @if ($errors->has('min_price'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('min_price') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-2  form-group">
                                            <label>Max Price</label>
                                            <span class="price">
                                                <input type="text" class="form-control {{ $errors->has('max_price') ? ' is-invalid' : '' }}" placeholder="Price" name="max_price" value="{{ old('max_price')}}" min="0" id="max_price" onkeypress="return validateFloatKeyPress(this,event);">
                                            </span>
                                            @if ($errors->has('max_price'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('max_price') }}</strong>
                                                </span>
                                            @endif
                                        </div>                                                                              
                                    </div>    
                                    <div class="row">
                                        <div class="col-sm-6  form-group">
                                            <label>Discipline</label>
                                            <select name="qualification[]" class="form-control {{ $errors->has('qualification') ? ' is-invalid' : '' }}" multiple="true">
                                                <option disabled="true" > -- Select Discipline --</option>
                                                @foreach($qualification as $qlf)
                                                    <option value="{{ $qlf->id }}" {{ (collect(old('qualification'))->contains($qlf->id)) ? 'selected':'' }} >{{ $qlf->name }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('qualification'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('qualification') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-6  form-group">
                                            <label>Service</label>
                                            <select name="service[]" class="form-control {{ $errors->has('service') ? ' is-invalid' : '' }}" multiple="true">
                                                <option disabled="true" > -- Select Service --</option>
                                                @foreach($service_list as $srvc)
                                                    <option value="{{ $srvc->id }}" {{ (collect(old('service'))->contains($srvc->id)) ? 'selected':'' }}>{{ $srvc->title }}</option>
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
                                        <div class="form-group col-sm-6" >
                                            <label>Street </label>
                                            <input type="text" class="form-control {{ $errors->has('location') ? ' is-invalid' : '' }}" name="location" placeholder="Location" value="{{ old('location') }}" autocomplete="off" />
                                            @if ($errors->has('location'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('location') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group col-sm-2" >
                                            <label>City</label>
                                            <input type="text" class="form-control {{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" placeholder="city" value="{{ old('city') }}" id="citysuggest" autocomplete="off" />
                                            @if ($errors->has('city'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('city') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group col-sm-2" >
                                            <label>state </label>
                                            <select name="state" class="form-control {{ $errors->has('state') ? ' is-invalid' : '' }}" readonly="true" id="state">
                                                <option disabled="true" selected=""> -- Select State --</option>
                                            </select>    
                                            @if ($errors->has('state'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('state') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group col-sm-2" >
                                            <label>Zip Code </label>
                                            <input type="text" class="form-control {{ $errors->has('zipcode') ? ' is-invalid' : '' }}" name="zipcode" placeholder="Zip code" value="{{ old('zipcode') }}" id="zipcode" readonly="true"  />
                                            @if ($errors->has('zipcode'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('zipcode') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-12" >
                                            <label>Servicable ZipCode </label>
                                            <input type="text" class="form-control {{ $errors->has('service_zipcode') ? ' is-invalid' : '' }} zipcodesuggest" name="service_zipcode" placeholder="Service Zip code" value="{{ old('service_zipcode') }}" id="service_zipcode"/>
                                            @if ($errors->has('service_zipcode'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong id="service_zipcode_msg">{{ $errors->first('service_zipcode') }}</strong>
                                                </span>
                                            @else 
                                                <span class="invalid-feedback" role="alert" style="display: inline;">
                                                    <strong id="service_zipcode_msg"></strong>
                                                </span>
                                            @endif
                                        </div>    
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-12" >
                                            <label>Non Servicable ZipCode </label>
                                            <input type="text" class="form-control {{ $errors->has('non_service_zipcode') ? ' is-invalid' : '' }} zipcodesuggest" name="non_service_zipcode" placeholder="Non-Service Zip code" value="{{ old('non_service_zipcode') }}" id="non_service_zipcode"/>
                                            @if ($errors->has('non_service_zipcode'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong id="non_service_zipcode_msg">{{ $errors->first('non_service_zipcode') }}</strong>
                                                </span>
                                            @else 
                                                <span class="invalid-feedback" role="alert" style="display: inline;">
                                                    <strong id="non_service_zipcode_msg"></strong>
                                                </span>
                                            @endif
                                        </div>    
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-12" >
                                            <label>Additional Information </label>
                                            <textarea class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}" rows="5" name="description" id="description" placeholder="Description"></textarea>
                                            @if ($errors->has('description'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('description') }}</strong>
                                                </span>
                                            @endif
                                        </div>
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

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.1.62/jquery.inputmask.bundle.js"></script>
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
                $.getJSON( "searchcity", {
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
            url: 'statefromcity',
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
            url: 'getzip',
            type: 'GET',
            data:{city:cityoption, state:stateoption},
            success: function (res) {
                $("#zipcode").val(res);
            }
        });
    })

    /*$('#zipcode').blur(function(){
        zip = $(this).val();
        $.ajax({
            url: '{{ env("APP_URL") }}admin/caregiver/locationfromzip',
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
    });    */

    $('#max_price').blur(function(){
        minprice = $("#min_price").val();
        maxprice = $("#max_price").val();
        if(minprice >= maxprice){
            $("#max_price").val(minprice);
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
    var phones = [{ "mask": "(###) ###-####"}];
    $('#mobile_number').inputmask({ 
        mask: phones, 
        greedy: false, 
        definitions: { '#': { validator: "[0-9]", cardinality: 1}}
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
</script>
@endsection
