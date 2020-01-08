@extends('layouts.app')

@section('top-css')
<style>
    .managebookingCard {
        padding: 20px;
    }
    .managebookingWrapItem {
        padding-bottom: 5px;
        margin-bottom: 20px;
        border-bottom: 1px solid #ddd;
    }
    .managebookingWrapItem label, .managebookingInfoWrap label {
        font-weight: 600;
    }
    .actionWrap, .addBtnWrap {
        margin-bottom: 10px;
        text-align: right;
    }
</style>
@endsection

@section('content')
<div class="content-wrapper">
   <div class="page-content fade-in-up">
        <div class="row">
         <div class="col-lg-12 col-md-12"> 
         @include('flash::message')
            <div class="ibox">
               <div class="ibox-body">
                  <div class="tab-content">
                     <div class="tab-pane fade show active" id="tab-2">
                        <form action="{{ route('bookings.shiftSave') }}" enctype = 'multipart/form-data' method="post" class="form-horizontal patientForm">
                           @csrf                           
                           <div class="card managebookingCard">
                                <div class="col-xs-12 managebookingInfoWrap">                               
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>Booking ID</label>
                                        </div>
                                        <div class="col-md-9">
                                            <p><u>{{ '#NUR'.$booking->id }}</u></p>
                                        </div>
                                    </div>                            
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>Username</label>
                                        </div>
                                        <div class="col-md-9">
                                            <p>{{ $booking->user->name }}</p>
                                        </div>
                                    </div>                            
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>Selected Caregivers</label>
                                        </div>
                                        <div class="col-md-9">
                                            <p> @foreach($assignedCaregivers as $key => $caregiver) {{ $key+1 }}.  {{ $caregiver['name'] }}  ({{ $caregiver['email'] }})  <br> @endforeach</p>
                                        </div>
                                    </div>                              
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>Schedule Date/Time</label>
                                        </div>
                                        <div class="col-md-9">
                                            <p>{{ $booking->start_date .' - '. $booking->end_date .' | '. $booking->start_time . ' - ' .$booking->end_time   }}</p>
                                        </div>
                                    </div>
                                    @if( $booking->booking_type == 'Select from week' )                          
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>WeekDays</label>
                                        </div>
                                        <div class="col-md-9">
                                            <p>{!! json_encode( implode(',' , unserialize($booking->weekdays))) !!}</p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                           <div class="card">
                             <div class="card-header" style="background-color: #ddd;">
                                <h5>Manage Booking</h5>
                             </div>
                             <div class="tab-content row">
                                <div class="tab-pane fade show active col-md-12" id="tab-2">
                                  <div class="card-body">
                                    <div class="managebookingWrap">
                                        <div class="col-xs-12 managebookingWrapItem">
                                            <input type="hidden" name="booking_id" value="{{$booking->id}}">
                                            <div class="col-xs-12 actionWrap">
                                                <button class="btn-sm btn-primary btn-cir" title="Edit"><i class="fas fa-pencil-alt"></i></button>
                                                <a class="btn-sm btn-danger btn-cir delBtn" title="Delete"><i class="fas fa-trash-alt"></i></a>
                                            </div>
                                            <div class="row form-group">
                                                <label class="col-md-3">Choose CareGiver</label>
                                                <div class="col-md-9">                                           
                                                    <select name="caregivers[]" class="form-control select2" id="caregiver_0">
                                                        @foreach($caregivers as $caregiver)
                                                            <option value="{{ $caregiver->id }}" >{{ $caregiver->user->name .' ('. $caregiver->user->email . ')'}}</option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('caregivers.*'))
                                                        <span class="invalid-feedback" role="alert" style="display:inline;">
                                                            <strong>{{ $errors->first('caregivers') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <label class="col-md-3"> Date</label>
                                                <div class="col-md-9">                                           
                                                    <input type="text" id="startDate_0" class="form-control floating-label" placeholder="Start Date" style="max-width: 220px;float: left;"  name="start_date[]" >  
                                                    @if ($errors->has('start_date'))
                                                        <span class="invalid-feedback" role="alert" style="display:inline;">
                                                            <strong>{{ $errors->first('start_date.*') }}</strong>
                                                        </span>
                                                    @endif    
                                                    <span style="display: inline;float: left;margin: 7px 30px;font-weight: 600;">To </span>                                 
                                                    <input type="text" id="endDate_0" class="form-control floating-label" placeholder="End Date" style="max-width: 220px;float: left; " name="end_date[]" > 
                                                    @if ($errors->has('end_date'))
                                                        <span class="invalid-feedback" role="alert" style="display:inline;">
                                                            <strong>{{ $errors->first('end_date.0') }}</strong>
                                                        </span>
                                                    @endif          
                                                </div>
                                            </div>                                         
                                            <div class="row form-group">
                                                <label class="col-md-3">Time</label>
                                                <div class="col-md-9">     
                                                    <input type="text" id="startTime_0" class="form-control floating-label" placeholder="Start Time" style="max-width: 220px;float: left;" name="start_time[]">
                                                    @if ($errors->has('start_time'))
                                                        <span class="invalid-feedback" role="alert" style="display:inline;">
                                                            <strong>{{ $errors->first('start_time') }}</strong>
                                                        </span>
                                                    @endif
                                                    <span style="display: inline;float: left;margin: 7px 30px;font-weight: 600;">To </span>
                                                    <input type="text" id="endTime_0" class="form-control floating-label" placeholder="End Time" style="max-width: 220px;float: left;" name="end_time[]">  
                                                    @if ($errors->has('end_time'))
                                                        <span class="invalid-feedback" role="alert" style="display:inline;">
                                                            <strong>{{ $errors->first('end_time') }}</strong>
                                                        </span>
                                                    @endif 
                                                </div>
                                            </div>
                                        </div>  
                                    </div>                                 
                                   <div class="col-xs-12 addBtnWrap">
                                        <a class="btn btn-info"><i class="fas fa-plus"></i> Add</a>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>

<script>
                                                        
    $("[id^=startDate]").bootstrapMaterialDatePicker({
        format : 'MM/DD/YYYY',
        time: false ,
        minDate : new Date(),
    }).on('change', function(e, date){ 
        
    });

     $('[id^=endDate]').bootstrapMaterialDatePicker({
        format : 'MM/DD/YYYY',
        time: false ,
        minDate : new Date(),
    }).on('change', function(e, date){ 
        
    });

    $('#todaystarttime').bootstrapMaterialDatePicker({ 
        date: false,
        format : 'hh:mm A',
    }).on('change', function(e, date){
        $('#todayendtime').bootstrapMaterialDatePicker('setMinDate', date);
    });

    $('#todayendtime').bootstrapMaterialDatePicker({ 
        date: false,
        format : 'hh:mm A'
    }).on('change', function(e, date){
        //$("#today_submit").css('display', 'inline');
    });

    $('.select2').select2();

    var current_id = 0;
    $(".addBtnWrap .btn").on('click', function(){ 
       var newElement = $('.managebookingWrapItem:first').clone(true);
       var id = current_id+1;
       current_id = id;

       $.each($('input', newElement), function (index, value) {
           value.id =  value.id.split("_")[0]+"_"+id ;
           console.log(newElement.find('input.text'));  
           newElement.find('input.text').bootstrapMaterialDatePicker();
           // console.log($(value.id).bootstrapMaterialDatePicker({format:'dd/mm/yy'}))
       });
       var field1 = $('select', newElement).attr("id");
       $('select', newElement).attr("id", field1.split("_")[0]+"_"+id );

       newElement.appendTo('.managebookingWrap');
    });

    $('.managebookingWrapItem .delBtn').click(function(){
        $(this).parent().parent().remove();
    });

 </script>
@endsection