@extends('layouts.app')
@section('content')
<style type="text/css">
    .ui-autocomplete{max-height: 300px !important;overflow-y: scroll !important;overflow-x: hidden !important;}
</style>
<div class="content-wrapper">
   <!-- START PAGE CONTENT-->
   <div class="page-heading">
      <h1 class="page-title">Add Client</h1>
      <ol class="breadcrumb">
         <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
         </li>
         <li class="breadcrumb-item"><a href="{{ route('patients.index') }}">Client</a></li>
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
                        <a class="nav-link active" href="#tab-2" data-toggle="tab"><i class="fas fa-plus"></i> Add Client</a>
                     </li>
                  </ul>
                  <div class="tab-content">
                     <div class="tab-pane fade show active" id="tab-2">
                        <form action="{{ route('patients.store') }}" enctype = 'multipart/form-data' method="post" class="form-horizontal patientForm">
                           @csrf
                           <div class="card">
                             <div class="card-header" style="background-color: #ddd;">
                                <h5>Personal Information</h5>
                             </div>
                             <div class="tab-content row">
                                <div class="tab-pane fade show active col-md-12" id="tab-2">
                                  <div class="card-body">
                                    <div class="row">
                                      <div class="form-group col-sm-12 center" style="text-align: center;">
                                        <span style="text-align: center;position: absolute;top: 120px;margin-left: 101px;" id="upload_image" onclick="event.preventDefault();">
                                          <button class="btn-sm btn-primary btn-cir" title="Edit"><i class="fas fa-pencil-alt"></i></button>
                                        </span>
                                          <img class="img-circle" src="{{ asset('admin/assets/img/admin-avatar.png') }}" style="width:150px;height:150px;"/>
                                            <input type="file" id="profile_image" name="profile_image" value="{{ old('profile_image') }}" onchange="readURL(this);" accept="image/*"/ style="display:none;"><br/><br/>

                                            <span class="text-danger image_error">
                                            <strong>{{ $errors->has('profile_image')?$errors->first('profile_image'):'' }}</strong>
                                            </span>
                                      </div>
                                    </div>
                                   <div class="row">
                                      <div class="col-sm-3 form-group">
                                         <label>First Name</label>
                                         <input type="text" class="form-control {{ $errors->has('f_name') ? ' is-invalid' : '' }}" name="f_name" placeholder="First Name" value="{{ old('f_name') }}"/>
                                         @if ($errors->has('f_name'))
                                         <span class="text-danger">
                                         <strong>{{ $errors->first('f_name') }}</strong>
                                         </span>
                                         @endif
                                      </div>
                                      <div class="col-sm-3 form-group">
                                         <label>Middle Name</label>
                                         <input type="text" class="form-control {{ $errors->has('m_name') ? ' is-invalid' : '' }}" name="m_name" placeholder="Middle Name" value="{{ old('m_name') }}"/>
                                         @if ($errors->has('m_name'))
                                         <span class="text-danger">
                                         <strong>{{ $errors->first('m_name') }}</strong>
                                         </span>
                                         @endif
                                      </div>
                                      <div class="col-sm-3 form-group">
                                         <label>Last Name</label>
                                         <input type="text" class="form-control {{ $errors->has('l_name') ? ' is-invalid' : '' }}" name="l_name" placeholder="Last Name" value="{{ old('l_name') }}"/>
                                         @if ($errors->has('l_name'))
                                         <span class="text-danger">
                                         <strong>{{ $errors->first('l_name') }}</strong>
                                         </span>
                                         @endif
                                      </div>          
                                      <div class="col-sm-3 form-group">
                                         <label>Email</label>
                                         <input type="text" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" placeholder="Email" value="{{ old('email') }}"/>
                                         @if ($errors->has('email'))
                                         <span class="text-danger">
                                         <strong>{{ $errors->first('email') }}</strong>
                                         </span>
                                         @endif
                                      </div>                                               
                                       <div class="col-sm-3  form-group">
                                          <label>Country Code</label>
                                          <select name="country_code" class="form-control {{ $errors->has('country_code') ? ' is-invalid' : '' }}">
                                              <option disabled="true" selected="true"> -- Select --</option>
                                              <option value="1" {{ old('country_code') == 1 ? 'selected':'' }}>+1</option>
                                              <option value="91" {{ old('country_code') == 91 ? 'selected':'' }}>+91</option>
                                          </select>
                                          @if ($errors->has('country_code'))
                                              <span class="text-danger" >
                                                  <strong>{{ $errors->first('country_code') }}</strong>
                                               </span>
                                          @endif
                                      </div>
                                      <div class="col-sm-3 form-group">
                                         <label>Mobile Number</label>
                                         <input type="text" class="form-control {{ $errors->has('mobile_number') ? ' is-invalid' : '' }}" name="mobile_number" placeholder="Mobile Number" value="{{ old('mobile_number') }}" id="mobile_number" />
                                         @if ($errors->has('mobile_number'))
                                         <span class="text-danger">
                                         <strong>{{ $errors->first('mobile_number') }}</strong>
                                         </span>
                                         @endif
                                      </div>
                                      <div class="col-sm-3 form-group date">
                                         <label>Date of Birth</label>
                                         <input type="text" class="form-control {{ $errors->has('dob') ? ' is-invalid' : '' }}" name="dob" id="dob" placeholder="Date Of Birth" value="{{ old('dob') }}"/>
                                         <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                         </div>
                                         @if ($errors->has('dob'))
                                         <span class="text-danger">
                                         <strong>{{ $errors->first('dob') }}</strong>
                                         </span>
                                         @endif
                                      </div>
                                       <div class="col-sm-3  form-group">
                                          <label>Height</label>
                                          <select name="height" class="form-control {{ $errors->has('height') ? ' is-invalid' : '' }} select2">
                                              <option disabled="true" selected="true"> -- Select Height --</option>
                                              @foreach(PROFILE_HEIGHT as $val)
                                                   <option value="{{ $val }}" {{ old('height') == $val ? 'selected':'' }}>{{$val}}</option>
                                              @endforeach
                                          </select>
                                          @if ($errors->has('height'))
                                              <span class="text-danger">
                                                  <strong>{{ $errors->first('height') }}</strong>
                                              </span>
                                          @endif
                                      </div>
                                      <div class="col-sm-3 form-group">
                                          <label>Weight</label>
                                          <select name="weight" class="form-control {{ $errors->has('weight') ? ' is-invalid' : '' }} select2">
                                              <option disabled="true" selected="true"> -- Select Weight --</option>
                                              @foreach(PROFILE_WEIGHT as $val)
                                                  <option value="{{ $val }}" {{ old('weight') == $val ? 'selected':'' }}>{{  $val }}</option>
                                              @endforeach
                                          </select>
                                          @if ($errors->has('weight'))
                                              <span class="text-danger">
                                                  <strong>{{ $errors->first('weight') }}</strong>
                                              </span>
                                          @endif
                                      </div>
                                      <div class="col-sm-3 form-group">
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
                                      <div class="col-sm-3 form-group">
                                          <label>Language</label>
                                          <select name="language" class="form-control {{ $errors->has('language') ? ' is-invalid' : '' }} select2">
                                              <option disabled="true" selected="true"> -- Select Language --</option>
                                              @foreach(PROFILE_LANGUAGE as $val)
                                              <option value="{{ $val }}" {{ old('language') == $val ? 'selected':'' }}>{{ $val }}</option>
                                              @endforeach
                                          </select>
                                          @if ($errors->has('language'))
                                              <span class="text-danger">
                                                  <strong>{{ $errors->first('language') }}</strong>
                                              </span>
                                          @endif
                                      </div> 
                                      <div class="col-sm-3 form-group">
                                         <label>Street</label>
                                         <input type="text" class="form-control {{ $errors->has('street') ? ' is-invalid' : '' }}" name="street" id="street" placeholder="street" value="{{ old('street') }}" />
                                         @if ($errors->has('street'))
                                         <span class="text-danger">
                                         <strong>{{ $errors->first('street') }}</strong>
                                         </span>
                                         @endif
                                      </div>
                                      <div class="col-sm-4 form-group">
                                         <label>City</label>
                                         <input type="text" class="form-control {{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" id="citysuggest" placeholder="City" value="{{ old('city') }}" />
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
                                          @if (!empty(old('state')))
                                              <option selected="" value="{{ old('state') }}">
                                                  {{ old('state') }}
                                              </option>
                                          @endif
                                          </select>
                                         @if ($errors->has('state'))
                                         <span class="text-danger">
                                         <strong>{{ $errors->first('state') }}</strong>
                                         </span>
                                         @endif
                                      </div>
                                      <div class="col-sm-4 form-group">
                                         <label>Zip Code</label>
                                         <input type="text" class="form-control {{ $errors->has('pin_code') ? ' is-invalid' : '' }}" id="pin_code" name="pin_code" placeholder="Zip Code" value="{{ old('pin_code') }}" readonly/>
                                         @if ($errors->has('pin_code'))
                                         <span class="text-danger">
                                         <strong>{{ $errors->first('pin_code') }}</strong>
                                         </span>
                                         @endif
                                      </div>
                                   </div>
                                 </div>
                                </div>
                             </div>
                           </div>
                           <br>
                           <div class="card">
                             <div class="card-header" style="background-color: #ddd;">
                                <h5>Service Information</h5>
                             </div>
                             <div class="tab-content row">
                                <div class="tab-pane fade show active col-md-12" id="tab-2">
                                  <div class="card-body">
                                   <div class="row">
                                      <div class="col-sm-4 form-group">
                                         <label>Diagnosis</label>
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
                                      <div class="col-sm-4  form-group">
                                          <label>Discipline</label>
                                          <select name="qualification[]" class="form-control {{ $errors->has('qualification') ? ' is-invalid' : '' }} select2" multiple="multiple">
                                            @foreach($qualifications as $qualification)
                                              <option value="{{ $qualification->id }}" {{ (collect(old('qualification'))->contains($qualification->id)) ? 'selected':'' }} >{{ $qualification->name }}</option>
                                              @endforeach
                                          </select>
                                          @if ($errors->has('qualification'))
                                              <span class="text-danger">
                                                  <strong>{{ $errors->first('qualification') }}</strong>
                                              </span>
                                          @endif
                                      </div>
                                      <div class="col-sm-4 form-group">
                                         <label>Long Term Care insurance</label>
                                         <div>
                                          <input type="radio" name="long_term" value="yes" {{ old('long_term') == 'yes' ? 'checked' : '' }}>
                                          <label for="yes">Yes</label>

                                          <input type="radio" name="long_term" value="no" {{ old('long_term') == 'no' ? 'checked' : '' }}>
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
                                      <div class="form-group col-md-6 yes describe">
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
                                          <textarea class="form-control" name="additional_info" rows="5" placeholder="Description">{{ old('additional_info') }}</textarea>
                                           @if ($errors->has('additional_info'))
                                           <span class="text-danger">
                                           <strong>{{ $errors->first('additional_info') }}</strong>
                                           </span>
                                           @endif
                                      </div>
                                   </div>
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

 $(function(){

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
            $("#pin_code").val(res);
        }
    });
})

if($('input[name=pets]:checked').val() == 'no' || (!$('input[name=pets]:checked').val()))
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
var phones = [{ "mask": "###-###-####"}];
$('#mobile_number').inputmask({
    mask: phones,
    greedy: false,
    definitions: { '#': { validator: "[0-9]", cardinality: 1}}
});

$('.select2').select2({
   placeholder: {
    id: '-1',
    text: '-- Select Discipline --'
  }
});
$("#upload_image").click(function(){
    $("#profile_image").click();
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
  $('#profile_image').checkFileType({
      allowedExtensions: ['jpg', 'jpeg','png'],
      success: function() {
          $('.image_error').text('');
      },
      error: function() {
        $('#profile_image').val('')
        $('.image_error').text('Please upload a jpg , jpeg or png image .');
      }
  });
});
</script>
@endsection
