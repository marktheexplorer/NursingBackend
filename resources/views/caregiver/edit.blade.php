@extends('layouts.app')
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
                            <div class="tab-pane fade show active" id="tab-2"><!--
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach -->
                                <form action="{{ route('caregiver.update', ['id' => $user->id]) }}" method="post" class="form-horizontal" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                    <div class="row">
                                        <div class="col-sm-12 form-group center" style="text-align: center;"><?php
                                            if(empty($user->profile_image)){ ?>
                                                <img class="img-circle" src="{{ asset('admin/assets/img/admin-avatar.png') }}" /><?php
                                            }else{ ?>
                                                <img class="img-circle" style="height:150px;width: 150;" src="<?php echo asset($user->profile_image); ?>" /><?php
                                            }   ?> 
                                        </div>                                        
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6" >
                                            <label>Change Profile Image</label><br/>
                                            <input type="file" class=" {{ $errors->has('profile_image') ? ' is-invalid' : '' }}" name="profile_image" placeholder="Profile Image" value="{{ old('profile_image') }}" accept="image/*" style="padding-left:0px;"/>
                                            @if ($errors->has('profile_image'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('profile_image') }}</strong>
                                                </span>
                                            @endif
                                        </div>   
                                        <div class="col-sm-2 form-group">
                                            <label>First Name</label>
                                            <input type="text" class="form-control {{ $errors->has('first_name') ? ' is-invalid' : '' }}" name="first_name" placeholder="First Name" value="{{ $user->first_name }}" />
                                            @if ($errors->has('first_name'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('first_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-2 form-group">
                                            <label>Middle Name</label>
                                            <input type="text" class="form-control {{ $errors->has('middle_name') ? ' is-invalid' : '' }}" name="middle_name" placeholder="Middle Name" value="{{ $user->middle_name }}" />
                                            @if ($errors->has('middle_name'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('middle_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-2 form-group">
                                            <label>Last Name</label>
                                            <input type="text" class="form-control {{ $errors->has('last_name') ? ' is-invalid' : '' }}" name="last_name" placeholder="Last Name" value="{{ $user->last_name }}" />
                                            @if ($errors->has('last_name'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('last_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">    
                                        <div class="col-sm-3  form-group">
                                            <label>Email</label>
                                            <input type="text" name="email" placeholder="Email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ $user->email }}" />
                                            @if ($errors->has('email'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-3  form-group">
                                            <label>Mobile Number</label>
                                            <input type="text" class="form-control {{ $errors->has('mobile_number') ? ' is-invalid' : '' }}" placeholder="Mobile Number" name="mobile_number" value="{{ $user->mobile_number }}" id="mobile_number">
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
                                                <option value="Male" {{ $user->gender == 'Male' ? 'selected':'' }} >Male</option>
                                                <option value="Female" {{ $user->gender == 'Female' ? 'selected':'' }}>Female</option>
                                            </select>
                                            @if ($errors->has('gender'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('gender') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">    
                                        <div class="form-group col-sm-4" >
                                            <label>Date of Birth</label>
                                            <input type="text" class="form-control {{ $errors->has('dob') ? ' is-invalid' : '' }}" name="dob" placeholder="Date of Birth" value="{{ date('d/m/Y', strtotime($user->dob)) }}" id="dob"/>
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
                                                    <option value="{{ $val }}" <?php if($user->height == $val){ echo 'selected'; } ?>>{{$val}}</option>
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
                                                    <option value="{{ $val }}" <?php if($user->weight == $val){ echo 'selected'; } ?>>{{$val}}</option>
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
                                            <span class="price">
                                                <input type="text" class="form-control {{ $errors->has('min_price') ? ' is-invalid' : '' }}" placeholder="Minimum" name="min_price" value="{{ $user->min_price }}" min="0" id="min_price" onkeypress="return validateFloatKeyPress(this,event);">
                                            </span>
                                            @if ($errors->has('min_price'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('min_price') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-2  form-group">
                                            <label>Max Price</label>
                                            <span class="price">
                                                <input type="text" class="form-control {{ $errors->has('max_price') ? ' is-invalid' : '' }} " placeholder="Price" name="max_price" value="{{ $user->max_price }}" min="0" id="max_price" onkeypress="return validateFloatKeyPress(this,event);">
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
                                            <label>Discipline</label><?php
                                                $qualification_array = array();
                                                foreach($user->qualification as $qlf){
                                                    $qualification_array[] = $qlf->id;
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
                                                foreach($user->services as $qlf){
                                                    $service_array[] = $qlf->id;
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
                                        <div class="form-group col-sm-6" >
                                            <label>Street </label>
                                            <input type="text" class="form-control {{ $errors->has('location') ? ' is-invalid' : '' }}" name="location" placeholder="Location" value="{{ $user->location }}" />
                                            @if ($errors->has('location'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('location') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group col-sm-2" >
                                            <label>City </label>
                                            <input type="text" class="form-control {{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" placeholder="city" value="{{ $user->city }}"  id="citysuggest" autocomplete="off"/>
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
                                                    <option <?php if($row->state_code == $user->state){ echo 'selected'; } ?> >{{ $row->state_code }}</option>
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
                                            <input type="text" class="form-control {{ $errors->has('zipcode') ? ' is-invalid' : '' }}" name="zipcode" placeholder="Zip code" value="{{ $user->zipcode }}" id="zipcode" />
                                            @if ($errors->has('zipcode'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('zipcode') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-12" >
                                            <label>Servicable ZipCode </label><?php
                                            $szip = '';
                                            foreach($user->service_zipcodes as $zip){
                                                $szip .= $zip->zip.", ";
                                            }?>
                                            <input type="text" class="form-control {{ $errors->has('service_zipcode') ? ' is-invalid' : '' }} zipcodesuggest" name="service_zipcode" placeholder="Service Zip code" value="{{ $szip }}" id="service_zipcode"/>
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
                                            <label>Non Servicable ZipCode </label><?php
                                            $nszip = '';
                                            foreach($user->non_service_zipcodes as $zip){
                                                $nszip .= $zip->zip.", ";
                                            }?>
                                            <input type="text" class="form-control {{ $errors->has('non_service_zipcode') ? ' is-invalid' : '' }} zipcodesuggest" name="non_service_zipcode" placeholder="Non-Service Zip code" value="{{ $nszip }}" id="non_service_zipcode"/>
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
                                            <textarea class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}" rows="5" name="description" id="description" placeholder="Description">{{$user->description}}</textarea>
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
</script>
@endsection