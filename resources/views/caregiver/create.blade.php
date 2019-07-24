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
                            <div class="tab-pane fade show active" id="tab-2"><!--
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach -->
                                <form action="{{ route('caregiver.store') }}" method="post" class="form-horizontal" enctype="multipart/form-data">
                                @csrf
                                    <div class="row">
                                        <div class="col-sm-6 form-group">
                                            <label>Name</label>
                                            <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" placeholder="Name" value="{{ old('name') }}" />
                                            @if ($errors->has('name'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-6  form-group">
                                            <label>Email</label>
                                            <input type="text" name="email" placeholder="Email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" />
                                            @if ($errors->has('email'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">    
                                        <div class="col-sm-3  form-group">
                                            <label>Mobile Number</label>
                                            <input type="number" class="form-control {{ $errors->has('mobile_number') ? ' is-invalid' : '' }}" placeholder="Mobile Number" name="mobile_number" value="{{ old('mobile_number')}}">
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
                                                <option value="Other" {{ old('gender') == 'Other' ? 'selected':'' }}>Other</option>
                                            </select>
                                            @if ($errors->has('gender'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('gender') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group col-sm-3" >
                                            <label>Date of Birth</label>
                                            <input type="text" class="form-control {{ $errors->has('dob') ? ' is-invalid' : '' }}" name="dob" placeholder="Date of Birth" value="{{ old('dob') }}" id="dob"/>
                                            @if ($errors->has('dob'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('dob')}}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-3  form-group">
                                            <label>Min Price</label>
                                            <input type="number" class="form-control {{ $errors->has('min_price') ? ' is-invalid' : '' }}" placeholder="Minimum" name="min_price" value="{{ old('min_price')}}" min="0" id="min_price">
                                            @if ($errors->has('min_price'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('min_price') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-3  form-group">
                                            <label>Max Price</label>
                                            <input type="number" class="form-control {{ $errors->has('max_price') ? ' is-invalid' : '' }}" placeholder="Price" name="max_price" value="{{ old('max_price')}}" min="0" id="max_price">
                                            @if ($errors->has('max_price'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('max_price') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group col-sm-6" >
                                            <label>Profile Image</label><br/>
                                            <input type="file" class="{{ $errors->has('profile_image') ? ' is-invalid' : '' }}" name="profile_image" placeholder="Profile Image" value="{{ old('profile_image') }}" accept="image/*"/>
                                            @if ($errors->has('profile_image'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('profile_image') }}</strong>
                                                </span>
                                            @endif
                                        </div>                                        
                                    </div>    
                                    <div class="row">
                                        <div class="col-sm-6  form-group">
                                            <label>Qualification</label>
                                            <select name="qualification[]" class="form-control {{ $errors->has('qualification') ? ' is-invalid' : '' }}" multiple="true">
                                                <option disabled="true" > -- Select Qualification --</option>
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
                                            <input type="text" class="form-control {{ $errors->has('location') ? ' is-invalid' : '' }}" name="location" placeholder="Location" value="{{ old('location') }}" />
                                            @if ($errors->has('location'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('location') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group col-sm-2" >
                                            <label>Zip Code </label>
                                            <input type="text" class="form-control {{ $errors->has('zipcode') ? ' is-invalid' : '' }}" name="zipcode" placeholder="Zip code" value="{{ old('zipcode') }}" id="zipcode" />
                                            @if ($errors->has('zipcode'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('zipcode') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group col-sm-2" >
                                            <label>City </label>
                                            <input type="text" class="form-control {{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" placeholder="city" value="{{ old('city') }}" id="city" readonly="true" />
                                            @if ($errors->has('city'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('city') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group col-sm-2" >
                                            <label>State </label>
                                            <input type="text" class="form-control {{ $errors->has('state') ? ' is-invalid' : '' }}" name="state" placeholder="state" value="{{ old('state') }}"  id="state" readonly="true" />
                                            @if ($errors->has('state'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('state') }}</strong>
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
                                            <label>Bio </label>
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
                $.getJSON( "searchzip", {
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
    });    

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
</script>
@endsection
