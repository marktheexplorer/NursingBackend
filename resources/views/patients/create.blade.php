@extends('layouts.app')
@section('content')
<div class="content-wrapper">
   <!-- START PAGE CONTENT-->
   <div class="page-heading">
      <h1 class="page-title">Add Patient</h1>
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
                        <a class="nav-link active" href="#tab-2" data-toggle="tab"><i class="fas fa-plus"></i> Add Patient</a>
                     </li>
                  </ul>
                  <div class="tab-content">
                     <div class="tab-pane fade show active" id="tab-2">
                        <form action="{{ route('patients.store') }}" enctype = 'multipart/form-data' method="post" class="form-horizontal">
                           @csrf
                           <div class="tab-content row">
                              <div class="tab-pane fade show active col-md-9" id="tab-2">
                                 <div class="row">
                                    <div class="col-sm-4 form-group">
                                       <label>Name</label>
                                       <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" placeholder="Name" value="{{ old('name') }}"/>
                                       @if ($errors->has('name'))
                                       <span class="text-danger">
                                       <strong>{{ $errors->first('name') }}</strong>
                                       </span>
                                       @endif
                                    </div>
                                    <div class="col-sm-4 form-group">
                                       <label>Email</label>
                                       <input type="text" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" placeholder="Email" value="{{ old('email') }}"/>
                                       @if ($errors->has('email'))
                                       <span class="text-danger">
                                       <strong>{{ $errors->first('email') }}</strong>
                                       </span>
                                       @endif
                                    </div>
                                    <div class="col-sm-4 form-group">
                                       <label>Mobile number</label>
                                       <input type="text" class="form-control {{ $errors->has('mobile_number') ? ' is-invalid' : '' }}" name="mobile_number" placeholder="Mobile Number" value="{{ old('mobile_number') }}" id="mobile_number" />
                                       @if ($errors->has('mobile_number'))
                                       <span class="text-danger">
                                       <strong>{{ $errors->first('mobile_number') }}</strong>
                                       </span>
                                       @endif
                                    </div>
                                    <div class="col-sm-4 form-group date">
                                       <label>Date of Birth</label>
                                       <input type="text" class="form-control {{ $errors->has('dob') ? ' is-invalid' : '' }}" name="dob" id="dob" placeholder="DOB" value="{{ old('dob') }}"/>
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
                                          <option value="Male">Male</option>
                                          <option value="Female">Female</option>
                                       </select>
                                       @if ($errors->has('gender'))
                                       <span class="text-danger">
                                       <strong>{{ $errors->first('gender') }}</strong>
                                       </span>
                                       @endif
                                    </div>
                                    <div class="col-sm-4 form-group">
                                       <label>Expected cost</label>
                                       <input type="text" class="form-control {{ $errors->has('range') ? ' is-invalid' : '' }}" name="range" placeholder="Range" value="{{ old('range') }}"/>
                                       @if ($errors->has('range'))
                                       <span class="text-danger">
                                       <strong>{{ $errors->first('range') }}</strong>
                                       </span>
                                       @endif
                                    </div>
                                    <div class="col-sm-4 form-group">
                                       <label>Pin Code</label>
                                       <input type="text" class="form-control {{ $errors->has('pin_code') ? ' is-invalid' : '' }}" id="pin_code" name="pin_code" placeholder="Pin Code" value="{{ old('pin_code') }}"/>
                                       @if ($errors->has('pin_code'))
                                       <span class="text-danger">
                                       <strong>{{ $errors->first('pin_code') }}</strong>
                                       </span>
                                       @endif
                                    </div>
                                    <div class="col-sm-4 form-group">
                                       <label>City</label>
                                       <input type="text" class="form-control {{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" id="city" placeholder="City" value="{{ old('city') }}" readonly />
                                       @if ($errors->has('city'))
                                       <span class="text-danger">
                                       <strong>{{ $errors->first('city') }}</strong>
                                       </span>
                                       @endif
                                    </div>
                                    <div class="col-sm-4 form-group">
                                       <label>State</label>
                                       <input type="text" class="form-control {{ $errors->has('state') ? ' is-invalid' : '' }}" name="state" id="state" placeholder="State" value="{{ old('state') }}" readonly />
                                       @if ($errors->has('state'))
                                       <span class="text-danger">
                                       <strong>{{ $errors->first('state') }}</strong>
                                       </span>
                                       @endif
                                    </div>
                                    <div class="col-sm-4 form-group">
                                       <label>Country</label>
                                       <input type="text" class="form-control {{ $errors->has('country') ? ' is-invalid' : '' }}" name="country" id="country" placeholder="Country" value="{{ old('country') }}" readonly />
                                       @if ($errors->has('country'))
                                       <span class="text-danger">
                                       <strong>{{ $errors->first('country') }}</strong>
                                       </span>
                                       @endif
                                    </div>
                                    <div class="col-sm-4 form-group">
                                       <label>Diagnose</label>
                                       <select class="form-control" name="diagnose_id">
                                          @foreach($diagnosis as $diagnose)
                                          <option value="{{ $diagnose->id }}">{{ $diagnose->title }}</option>
                                          @endforeach
                                       </select>
                                       @if ($errors->has('diagnose_id'))
                                       <span class="text-danger">
                                       <strong>{{ $errors->first('diagnose_id') }}</strong>
                                       </span>
                                       @endif
                                    </div>
                                    <div class="col-sm-4 form-group">
                                       <label>Availability</label>
                                        <select class="form-control" name="availability">
                                          <option value="24-hours">24-hours </option>
                                          <option value="12-hours(Day shift)">12-hours(Day shift)</option>
                                          <option value="12-hours(Night shift)">12-hours(Night shift)</option>
                                       </select>
                                       @if ($errors->has('availability'))
                                       <span class="text-danger">
                                       <strong>{{ $errors->first('availability') }}</strong>
                                       </span>
                                       @endif
                                    </div>
                                    <div class="col-sm-4 form-group">
                                       <label>Pets</label>
                                       <div>
                                        <input type="radio" id="yes"
                                         name="pets" value="yes" {{ old('pets') == 'yes' ? 'checked' : '' }}>
                                        <label for="yes">Yes</label>

                                        <input type="radio" id="no"
                                         name="pets" value="no" {{ old('pets') == 'no' ? 'checked' : '' }}>
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
                                        <textarea class="form-control" name="pets_description" rows="3">{{ old('pets_description') }}</textarea>
                                         @if ($errors->has('pets_description'))
                                         <span class="text-danger">
                                         <strong>{{ $errors->first('pets_description') }}</strong>
                                         </span>
                                         @endif
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Additional Information</label>
                                        <textarea class="form-control" name="additional_info" rows="5">{{ old('additional_info') }}</textarea>
                                         @if ($errors->has('additional_info'))
                                         <span class="text-danger">
                                         <strong>{{ $errors->first('additional_info') }}</strong>
                                         </span>
                                         @endif
                                    </div>
                                 </div>
                              </div>
                              <div class="col-md-3">
                                 <label>  Select Image:</label>
                                 <div class="col-sm-12 form-group">
                                    <input type="file" name="profile_image" class="" onchange="readURL(this);"><br/><br/>
                                    <img  id="preview" alt="No Image Selected" style="display:none;">
                                    @if ($errors->has('profile_image'))
                                    <span class="text-danger">
                                    <strong>{{ $errors->first('profile_image') }}</strong>
                                    </span>
                                    @endif
                                 </div>
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
   </div>
</div>
@endsection
@section('footer-scripts')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.1.62/jquery.inputmask.bundle.js"></script>
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
                $('#preview').css('display', 'block');
                $('#preview').attr('src', e.target.result);
            };
   
            reader.readAsDataURL(input.files[0]);
        }
    }

    $('#pin_code').blur(function(){
        pin = $(this).val();
        $.ajax({
            url: 'locationfromzip',
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

    
    if($('input[name=pets]:checked').val() == 'no' || (!$('input[name=pets]:checked').val()))
        {  
            $('.describe').hide();
        }
    $('input[type="radio"]').click(function(){

      var inputValue = $(this).attr("value");
      var targetBox = $("." + inputValue);
      $(".describe").not(targetBox).hide();
      $(targetBox).show();

    });

    var phones = [{ "mask": "(###) ###-####"}];
    $('#mobile_number').inputmask({ 
        mask: phones, 
        greedy: false, 
        definitions: { '#': { validator: "[0-9]", cardinality: 1}}
    });


</script>
@endsection
