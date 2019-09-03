@extends('layouts.app')

@section('content')
<style type="text/css">
    .ui-autocomplete{max-height: 300px !important;overflow-y: scroll !important;overflow-x: hidden !important;}
</style>
<div class="content-wrapper">
    <!-- START PAGE CONTENT-->
    <div class="page-heading">
        <h1 class="page-title">Edit Patient Details</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('patients.index') }}">Patients</a></li>
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
                                <a class="nav-link active" href="#tab-2" data-toggle="tab"><i class="fas fa-pencil-alt"></i>Edit Patient</a>
                            </li>
                        </ul>
                        <form action="{{ route('patients.update', ['id' => $user->id]) }}" enctype = 'multipart/form-data' method="post" class="form-horizontal">
                        @csrf
                        @method('put')
                            <div class="tab-content row">
                                <div class="tab-pane fade show active col-md-9" id="tab-2">
                                    <div class="row">
                                       <div class="col-sm-4 form-group">
                                            <label>First Name</label>
                                            <input type="text" class="form-control {{ $errors->has('f_name') ? ' is-invalid' : '' }}" name="f_name" placeholder="First Name" value="{{ old('f_name', $user->patient?$user->patient->f_name:'') }}"/>
                                            @if ($errors->has('f_name'))
                                                <span class="text-danger">
                                                    <strong>{{ $errors->first('f_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label>Middle Name</label>
                                            <input type="text" class="form-control {{ $errors->has('m_name') ? ' is-invalid' : '' }}" name="m_name" placeholder="Middle Name" value="{{ old('m_name', $user->patient?$user->patient->m_name:'') }}"/>
                                            @if ($errors->has('m_name'))
                                                <span class="text-danger">
                                                    <strong>{{ $errors->first('m_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label>Last Name</label>
                                            <input type="text" class="form-control {{ $errors->has('l_name') ? ' is-invalid' : '' }}" name="l_name" placeholder="Last Name" value="{{ old('l_name', $user->patient?$user->patient->l_name:'') }}"/>
                                            @if ($errors->has('l_name'))
                                                <span class="text-danger">
                                                    <strong>{{ $errors->first('l_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label>Email</label>
                                            <input type="text" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" placeholder="Email" value="{{ old('email', $user->email) }}" readonly />
                                            @if ($errors->has('email'))
                                               <span class="text-danger">
                                               <strong>{{ $errors->first('email') }}</strong>
                                               </span>
                                           @endif
                                        </div>
                                        <div class="col-sm-4 form-group">
                                           <label>Mobile number</label>
                                           <input type="text" class="form-control {{ $errors->has('mobile_number') ? ' is-invalid' : '' }}" name="mobile_number" placeholder="Mobile Number" value="{{ old('mobile_number', $user->mobile_number) }}" id="mobile_number" readonly />
                                            @if ($errors->has('mobile_number'))
                                               <span class="text-danger">
                                               <strong>{{ $errors->first('mobile_number') }}</strong>
                                               </span>
                                           @endif
                                        </div>
                                        <div class="col-sm-4 form-group date">
                                           <label>Date of Birth</label>
                                           <input type="text" class="form-control {{ $errors->has('dob') ? ' is-invalid' : '' }}" name="dob" id="dob" placeholder="Date Of Birth" value="{{ old('dob', date('d/m/Y', strtotime($user->dob))) }}"/>
                                           <div class="input-group-addon">
                                              <span class="glyphicon glyphicon-th"></span>
                                           </div>
                                           @if ($errors->has('dob'))
                                           <span class="text-danger">
                                           <strong>{{ $errors->first('dob') }}</strong>
                                           </span>
                                           @endif
                                        </div>
                                        <div class="col-sm-4  form-group">
                                            <label>Height</label>
                                            <select name="height" class="form-control {{ $errors->has('height') ? ' is-invalid' : '' }}">
                                                <option disabled="true" selected="true"> -- Select Height --</option>
                                                @foreach(PROFILE_HEIGHT as $val)
                                                    <option value="{{ $val }}" {{ ($user->patient?$user->patient->height:old('height')) == $val ? 'selected' : '' }}>{{$val}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('height'))
                                                <span class="text-danger">
                                                    <strong>{{ $errors->first('height') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label>Weight</label>
                                            <select name="weight" class="form-control {{ $errors->has('weight') ? ' is-invalid' : '' }}">
                                                <option disabled="true" selected="true"> -- Select Weight --</option>
                                                @foreach(PROFILE_WEIGHT as $val)
                                                    <option value="{{ $val }}" {{ ($user->patient?$user->patient->weight:old('weight')) == $val ? 'selected' : '' }}>{{$val}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('weight'))
                                                <span class="text-danger">
                                                    <strong>{{ $errors->first('weight') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label>Gender</label>
                                            <select class="form-control" name="gender">
                                               <option value="Male" {{ ($user->gender?$user->gender:old('gender')) == 'Male' ? 'selected' : '' }}>Male</option>
                                               <option value="Female" {{ ($user->gender?$user->gender:old('gender')) == 'Female' ? 'selected' : '' }}>Female</option>
                                            </select>
                                            @if ($errors->has('gender'))
                                                <span class="text-danger">
                                                    <strong>{{ $errors->first('gender') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-4  form-group">
                                            <label>Language</label>
                                            <select name="language" class="form-control {{ $errors->has('language') ? ' is-invalid' : '' }}">
                                                <option disabled="true" selected="true"> -- Select Language --</option>
                                                @foreach(LANGUAGES as $val)
                                                    <option value="{{ $val }}" {{ ($user->patient?$user->patient->language:old('language')) == $val ? 'selected' : '' }}>{{$val}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('language'))
                                                <span class="text-danger">
                                                    <strong>{{ $errors->first('language') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label>Expected cost</label>
                                            <span class="patient_price">
                                              <input type="text" class="form-control {{ $errors->has('range') ? ' is-invalid' : '' }} " name="range" placeholder="Expected Cost" value="{{ old('range', $user->patient?$user->patient->range:'') }}" onkeypress="return validateFloatKeyPress(this,event);" />
                                            </span>
                                            @if ($errors->has('range'))
                                                <span class="text-danger">
                                                    <strong>{{ $errors->first('range') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label>Street</label>
                                            <input type="text" class="form-control {{ $errors->has('street') ? ' is-invalid' : '' }}" name="street" placeholder="street" value="{{ old('street', $user->street) }}" id="street" />
                                            @if ($errors->has('street'))
                                                <span class="text-danger">
                                                    <strong>{{ $errors->first('street') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label>City</label>
                                            <input type="text" class="form-control {{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" placeholder="City" value="{{ old('city', $user->city) }}" id="citysuggest"/>
                                            @if ($errors->has('city'))
                                                <span class="text-danger">
                                                    <strong>{{ $errors->first('city') }}</strong>
                                                </span>
                                            @endif
                                        </div> 
                                        <div class="col-sm-4 form-group">
                                            <label>State</label>
                                            <select name="state" class="form-control {{ $errors->has('state') ? ' is-invalid' : '' }}" readonly="true" id="state">
                                                <option disabled="true" selected=""> -- Select State --</option>
                                                @foreach($city_state as $row)
                                                    <option <?php if($row->state_code == $user->state){ echo 'selected'; } ?> >{{ $row->state_code }}</option>
                                                @endforeach
                                            </select>  
                                            @if ($errors->has('state'))
                                                <span class="text-danger">
                                                    <strong>{{ $errors->first('state') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-4 form-group">
                                           <label>Pin Code</label>
                                           <input type="text" class="form-control {{ $errors->has('pin_code') ? ' is-invalid' : '' }}" name="pin_code" placeholder="Pin Code" value="{{ old('pin_code' ,$user->patient ?$user->patient->pin_code:'') }}" id="pin_code" />
                                           @if ($errors->has('pin_code'))
                                           <span class="text-danger">
                                           <strong>{{ $errors->first('pin_code') }}</strong>
                                           </span>
                                           @endif
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label>Health Conditions</label>
                                             <select class="form-control" name="diagnose_id" >
                                                @foreach($diagnosis as $diagnose)
                                                <option value="{{ $diagnose->id }}" {{ ($user->patient?$user->patient->diagnose_id:old('diagnose')) == $diagnose->id ? 'selected' : '' }}>{{ $diagnose->title }}</option>
                                                @endforeach
                                             </select>
                                            @if ($errors->has('diagnose'))
                                                <span class="text-danger">
                                                    <strong>{{ $errors->first('diagnose') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label>Availability</label>
                                            <select class="form-control" name="availability">
                                               <option value="24-hours" {{ ($user->patient?$user->patient->availability:old('availability')) == '24-hours' ? 'selected' : '' }}>24-hours</option>
                                               <option value="12-hours(Day shift)" {{ ($user->patient?$user->patient->availability:old('availability')) == '12-hours(Day shift)' ? 'selected' : '' }}>12-hours(Day shift)</option>
                                               <option value="12-hours(Night shift)" {{ ($user->patient?$user->patient->availability:old('availability')) == '12-hours(Night shift)' ? 'selected' : '' }}>12-hours(Night shift)</option>
                                            </select>
                                            @if ($errors->has('availability'))
                                                <span class="text-danger">
                                                    <strong>{{ $errors->first('availability') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-4  form-group">
                                            <label>Discipline</label>
                                            <select name="qualification[]" class="form-control {{ $errors->has('qualification') ? ' is-invalid' : '' }} multiple" multiple="multiple">
                                                <option disabled="true" > -- Select Discipline --</option>
                                                @foreach($qualifications as $qualification)
                                                  <option value="{{ $qualification->id }}" <?php if (in_array($qualification->id, $selected_disciplines)) {
                                                    echo 'selected';
                                                  } ?> >{{ $qualification->name }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('qualification'))
                                                <span class="text-danger">
                                                    <strong>{{ $errors->first('qualification') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-4 form-group">
                                           <label>Long Term Care insuranc</label>
                                           <div>
                                            <input type="radio" name="long_term" value="yes" {{ ($user->patient?$user->patient->long_term:old('long_term')) == '1' ? 'checked' : '' }}>
                                            <label for="yes">Yes</label>

                                            <input type="radio" name="long_term" value="no" {{ ($user->patient?$user->patient->long_term:old('long_term')) == '0' ? 'checked' : '' }}>
                                            <label for="no">No</label>
                                          </div>
                                           @if ($errors->has('long_term'))
                                           <span class="text-danger">
                                           <strong>{{ $errors->first('long_term') }}</strong>
                                           </span>
                                           @endif
                                        </div>
                                        <div class="col-sm-2 form-group">
                                           <label>Pets</label>
                                           <div>
                                            <input type="radio" id="yes"
                                             name="pets" value="yes" {{ ($user->patient?$user->patient->pets:old('pets')) == '1' ? 'checked' : '' }}>
                                            <label for="yes">Yes</label>

                                            <input type="radio" id="no"
                                             name="pets" value="no" {{ ($user->patient?$user->patient->pets:old('pets')) == '0' ? 'checked' : '' }}>
                                            <label for="no">No</label>
                                          </div>
                                           @if ($errors->has('pets'))
                                           <span class="text-danger">
                                           <strong>{{ $errors->first('pets') }}</strong>
                                           </span>
                                           @endif
                                        </div>
                                        <div class="form-group col-md-6 yes describe">
                                            <label>Please Describe</label>
                                            <textarea class="form-control" name="pets_description" rows="3">{{ old('pets_description', $user->patient? $user->patient->pets_description:'') }}</textarea>
                                             @if ($errors->has('pets_description'))
                                             <span class="text-danger">
                                             <strong>{{ $errors->first('pets_description') }}</strong>
                                             </span>
                                             @endif
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>Additional Information</label>
                                            <textarea class="form-control" name="additional_info" rows="5">{{ old('additional_info', $user->patient? $user->patient->additional_info:'') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 form-group">
                                    <label>Select Image:</label>
                                    <input type="file" name="profile_image" class="{{ $errors->has('profile_image') ? ' is-invalid' : '' }} form-control" value="{{ old('profile_image') }}" onchange="readURL(this);" accept="image/*"/ style="padding-left:0px;padding:0px;border:0px;"><br/><br/>
                                    <img  id="preview" src="{{ asset(config('image.user_image_url').$user->profile_image) }}" alt="Your image">
                                    @if ($errors->has('profile_image'))
                                        <span class="text-danger">
                                            <strong>{{ $errors->first('profile_image') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-default" type="submit">Submit</button>
                            </div>
                        </form>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.1.62/jquery.inputmask.bundle.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>
<script>
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
    $("#dob").keydown(function(e){
        //make non edidatble field
        e.preventDefault();
    });
   
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
   
            reader.onload = function (e) {
                $('#preview')
                    .attr('src', e.target.result);
            };
   
            reader.readAsDataURL(input.files[0]);
        }
    }
 $(function(){
    function split( val ) {
        return val.split( /,\s*/ );
    }

    function extractLast( term ) {
        //return split( term ).pop();  
        temp = $.trim($("#pin_code").val());
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
            $.getJSON( "{{ env('APP_URL') }}admin/patients/searchcity", {
                term: request.term
            }, response );
        },
        search: function() {
            // custom minLength
            var term = this.value;
            if ( term.length < 1){
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
        url: "{{ env('APP_URL') }}admin/patients/statefromcity",
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
        url: "{{ env('APP_URL') }}admin/patients/getzip",
        type: 'GET',
        data:{city:cityoption, state:stateoption},
        success: function (res) {
            $("#pin_code").val(res);
        }
    });
})
    if($('input[name=pets]:checked').val() == 'no')
        {  
            $('.describe').hide();
        }
    $('input[name=pets]').click(function(){

      var inputValue = $(this).attr("value");
      var targetBox = $("." + inputValue);
      $(".describe").not(targetBox).hide();
      $(targetBox).show();

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

    $('.multiple').select2();
</script>
@endsection