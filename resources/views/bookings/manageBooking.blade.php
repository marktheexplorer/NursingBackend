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
                             <!-- <div class="tab-content row">
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
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <label class="col-md-3"> Date</label>
                                                <div class="col-md-9">                                           
                                                    <input type="text" id="startDate_0" class="form-control floating-label" placeholder="Start Date" style="max-width: 220px;float: left;"  name="start_date[]" >   
                                                    <span style="display: inline;float: left;margin: 7px 30px;font-weight: 600;">To </span>                                 
                                                    <input type="text" id="endDate_0" class="form-control floating-label" placeholder="End Date" style="max-width: 220px;float: left; " name="end_date[]" >      
                                                </div>
                                            </div>                                         
                                            <div class="row form-group">
                                                <label class="col-md-3">Time</label>
                                                <div class="col-md-9">     
                                                    <input type="text" id="startTime_0" class="form-control floating-label" placeholder="Start Time" style="max-width: 220px;float: left;" name="start_time[]">
                                                    <span style="display: inline;float: left;margin: 7px 30px;font-weight: 600;">To </span>
                                                    <input type="text" id="endTime_0" class="form-control floating-label" placeholder="End Time" style="max-width: 220px;float: left;" name="end_time[]">  
                                                </div>
                                            </div>
                                        </div>  
                                    </div>                                 
                                   <div class="col-xs-12 addBtnWrap">
                                        <a class="btn btn-info"><i class="fas fa-plus"></i> Add</a>
                                    </div>
                                 </div>
                                </div>
                             </div> -->

                             <div class="container-fluid cloned-row1 educat_info" id="cloned-row1" name="cloned-row1">
                                <div class="row">
                                    <input type="hidden" name="booking_id" value="{{$booking->id}}">
                                    <div class="row form-group">
                                        <label class="col-md-3">Choose CareGiver</label>
                                        <div class="col-md-9">                                           
                                            <select name="caregiver[]" class="form-control select2 caregiver" id="caregiver">
                                                @foreach($caregivers as $caregiver)
                                                    <option value="{{ $caregiver->id }}" >{{ $caregiver->user->name .' ('. $caregiver->user->email . ')'}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>   
                                    <div class="row form-group">
                                        <label class="col-md-3"> Date</label>
                                        <div class="col-md-9">                                           
                                            <input type="text" id="start_data" class="form-control floating-label ipt_Field  start_data deg_date" placeholder="Start Date" style="max-width: 220px;float: left;"  name="start_data[]" >   
                                            <span style="display: inline;float: left;margin: 7px 30px;font-weight: 600;">To </span>                                 
                                            <input type="text" id="end_date" class="form-control floating-label deg_date" placeholder="End Date" style="max-width: 220px;float: left; " name="end_date[]" >      
                                        </div>
                                    </div>                           
                                    <div class="row form-group">
                                        <label class="col-md-3">Time</label>
                                        <div class="col-md-9">     
                                            <input type="text" id="start_time" class="form-control floating-label start_time" placeholder="Start Time" style="max-width: 220px;float: left;" name="start_time[]">
                                            <span style="display: inline;float: left;margin: 7px 30px;font-weight: 600;">To </span>
                                            <input type="text" id="end_time" class="form-control floating-label deg_time" placeholder="End Time" style="max-width: 220px;float: left;" name="end_time[]">  
                                        </div>
                                    </div>
                                </div> 
                                <!-- Third row-fluid-->
                                <div class="row">
                                   <div class="col-xs-12 addBtnWrap">
                                        <a class="btn btn-info add_button" ><i class="fas fa-plus"></i> Add</a>
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
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

<script>

    $(document).ready(function () { 
        $(".deg_date").datepicker();
        $("#start_time").timepicker();
  
          var count=0;
          $(document).on("click", ".add_button", function () { 
              var $clone = $('.cloned-row1:eq(0)').clone(true,true);
              
              $clone.find('[id]').each(function(){this.id+='someotherpart'+count});
              $clone.find('.btn_more').after("<input type='button' class='btn_less1' value='less' id='buttonless'/>")
              $clone.attr('id', "added"+(++count));
              $clone.find("input.deg_date")
                        .removeClass('hasDatepicker')
                        .removeData('datepicker')
                        .unbind()
                        .datepicker();

              $clone.find("input.start_time").each(function(){
                $(this).attr("id", "").removeData().off();
                $(this).find('.add-on').removeData().off();
                $(this).find('input').removeData().off();
                $(this).timepicker();
              });
              
              $(this).parents('.educat_info').after($clone);
          });
          $(document).on('click', ".btn_less1", function (){
              var len = $('.cloned-row1').length;
              if(len>1){
                  $(this).closest(".btn_less1").parent().parent().parent().remove();
              }
          });
      });
                                                        
    /*$("[id^=startDate]").bootstrapMaterialDatePicker({
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
    });*/

 </script>
@endsection