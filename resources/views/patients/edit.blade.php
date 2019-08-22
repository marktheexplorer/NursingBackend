@extends('layouts.app')

@section('content')
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
                                            <label>Name</label>
                                            <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" placeholder="Title" value="{{ old('name', $user->name) }}"/>
                                            @if ($errors->has('name'))
                                                <span class="text-danger">
                                                    <strong>{{ $errors->first('name') }}</strong>
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
                                           <input type="text" class="form-control {{ $errors->has('dob') ? ' is-invalid' : '' }}" name="dob" id="dob" placeholder="DOB" value="{{ old('dob', date('d/m/Y', strtotime($user->dob))) }}"/>
                                           <div class="input-group-addon">
                                              <span class="glyphicon glyphicon-th"></span>
                                           </div>
                                           @if ($errors->has('dob'))
                                           <span class="text-danger">
                                           <strong>{{ $errors->first('dob') }}</strong>
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
                                        <div class="col-sm-4 form-group">
                                            <label>Expected cost</label>
                                            <span class="price">
                                              <input type="text" class="form-control {{ $errors->has('range') ? ' is-invalid' : '' }} " name="range" placeholder="Range" value="{{ old('range', $user->patient?$user->patient->range:'') }}" onkeypress="return validateFloatKeyPress(this,event);" />
                                            </span>
                                            @if ($errors->has('range'))
                                                <span class="text-danger">
                                                    <strong>{{ $errors->first('range') }}</strong>
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
                                            <label>City</label>
                                            <input type="text" class="form-control {{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" placeholder="City" value="{{ old('city', $user->city) }}" id="city" readonly/>
                                            @if ($errors->has('city'))
                                                <span class="text-danger">
                                                    <strong>{{ $errors->first('city') }}</strong>
                                                </span>
                                            @endif
                                        </div> 
                                        <div class="col-sm-4 form-group">
                                            <label>State</label>
                                            <input type="text" class="form-control {{ $errors->has('state') ? ' is-invalid' : '' }}" name="state" placeholder="State" value="{{ old('state', $user->state) }}" id="state" readonly />
                                            @if ($errors->has('state'))
                                                <span class="text-danger">
                                                    <strong>{{ $errors->first('state') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label>Country</label>
                                            <input type="text" class="form-control {{ $errors->has('country') ? ' is-invalid' : '' }}" name="country" placeholder="Country" value="{{ old('country', $user->country) }}" id="country" readonly />
                                            @if ($errors->has('country'))
                                                <span class="text-danger">
                                                    <strong>{{ $errors->first('country') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label>Health Conditions</label>
                                             <select class="form-control" name="diagnose_id" multiple="true">
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
                                        <div class="col-sm-6  form-group">
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
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('qualification') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-6 form-group">
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
                                        <div class="col-sm-4 form-group">
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
                                        <div class="form-group col-md-8 yes describe">
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
                                    <input type="file" name="profile_image" class="" onchange="readURL(this);"><br/><br/>
                                    <img  id="preview" src="{{ asset(config('image.user_image_url').$user->profile_image) }}" alt="your image">
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

    $('#pin_code').blur(function(){
        pin = $(this).val();
        $.ajax({
            url: '{{ route("locationfromzip") }}',
            type: 'GET',
            dataType: 'json',
            data:{pin_code:pin},
            success: function (res) {
                if(res['error']){
                    $("#city").val('');
                    $("#state").val('');
                    $("#country").val('');
                    $('#pin_code').val('');
                    swal("Oops", "Invalid Zip Code", "error");
                    //$('#zipcode').focus();
                }else{
                    $("#city").val(res['city']);
                    $("#state").val(res['state']);
                    $("#country").val('USA');
                }
            }
        });
    });
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