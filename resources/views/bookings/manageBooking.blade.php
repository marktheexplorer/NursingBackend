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
                        <form action="{{ route('bookings.shiftSave') }}" onsubmit="save('{{ $booking->id }}', event,this)" enctype = 'multipart/form-data' method="post" class="form-horizontal patientForm">
                           @csrf                           
                           <div class="card managebookingCard">
                                <div class="col-xs-12 managebookingInfoWrap">                               
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>Schedule ID</label>
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
                                            <p>{{ $booking->user->f_name.' '.$booking->user->m_name.' '.$booking->user->l_name }}</p>
                                        </div>
                                    </div>                            
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>Selected Caregivers</label>
                                        </div>
                                        <div class="col-md-9">
                                            <p> @foreach($assignedCaregivers as $key => $caregiver) {{ $key+1 }}.  {{ $caregiver['name'] }}  ({{ $caregiver['email'] }}) :{{ $caregiver['phone_number'] }} <br> @endforeach</p>
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
                                <h5>Manage Schedule</h5>
                             </div>
                            @if(count($assigned) == 0)
                            <div class="container-fluid cloned-row1 educat_info" id="cloned-row1" name="cloned-row1">
                                <div class="row">
                                    <input type="hidden" name="booking_id" value="{{$booking->id}}">
                                    <div class="row col-md-12 form-group">
                                        <label class="col-md-3">Choose CareGiver</label>
                                        <div class="col-md-9">                                           
                                          <select name="caregivers[]" class="form-control select2 caregiver" id="caregiver">
                                            @foreach($caregivers as $caregiver)
                                                <option value="{{ $caregiver->id }}" >{{ $caregiver->user->f_name.' '. $caregiver->user->l_name .' ('. $caregiver->user->email . ')'}}</option>
                                            @endforeach
                                          </select>
                                        </div>
                                    </div>   
                                    <div class="row col-md-12 form-group">
                                        <label class="col-md-3"> Date</label>
                                        <div class="col-md-9">                                           
                                            <input type="text" id="start_date" class="form-control floating-label ipt_Field  start_date start_date" placeholder="Start Date" style="max-width: 220px;float: left;"  name="start_date[]" >   
                                            <span style="display: inline;float: left;margin: 7px 30px;font-weight: 600;">To </span>                                 
                                            <input type="text" id="end_date" class="form-control floating-label end_date" placeholder="End Date" style="max-width: 220px;float: left; " name="end_date[]" >      
                                        </div>
                                    </div>                           
                                    <div class="row col-md-12 form-group">
                                        <label class="col-md-3">Time</label>
                                        <div class="col-md-9">     
                                            <input type="text" id="start_time" class="form-control floating-label start_time" placeholder="Start Time" style="max-width: 220px;float: left;" name="start_time[]" autocomplete="off">
                                            <span style="display: inline;float: left;margin: 7px 30px;font-weight: 600;">To </span>
                                            <input type="text" id="end_time" class="form-control floating-label end_time" placeholder="End Time" style="max-width: 220px;float: left;" name="end_time[]" autocomplete="off">  
                                        </div>
                                    </div>
                                </div> 
                                <!-- Third row-fluid-->
                                <div class="row">
                                   <div class="col-xs-12 addBtnWrap">
                                        <a class="btn btn-info add_button btn_more" ><i class="fas fa-plus"></i> Add</a>
                                    </div>
                                </div>
                            </div>
                            @else
                            @foreach($assigned as $assign)
                             <div class="container-fluid cloned-row1 educat_info" id="cloned-row1" name="cloned-row1">
                                <div class="row">
                                    <input type="hidden" name="booking_id" value="{{$booking->id}}">
                                    <div class="row col-md-12 form-group">
                                        <label class="col-md-3">Choose CareGiver</label>
                                        <div class="col-md-9">                                           
                                            <select name="caregivers[]" class="form-control select2 caregiver" id="caregiver">
                                                @foreach($caregivers as $caregiver)
                                                    <option value="{{ $caregiver->id }}" {{ $caregiver->id == $assign->caregiver_id ? 'selected' : ''}} >{{ $caregiver->user->name .' ('. $caregiver->user->email . ')'}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>   
                                    <div class="row col-md-12 form-group">
                                        <label class="col-md-3"> Date</label>
                                        <div class="col-md-9">                                           
                                            <input type="text" id="start_date" class="form-control floating-label ipt_Field  start_date start_date" placeholder="Start Date" style="max-width: 220px;float: left;"  name="start_date[]" value="{{ $assign->start_date }}" >   
                                            <span style="display: inline;float: left;margin: 7px 30px;font-weight: 600;">To </span>                                 
                                            <input type="text" id="end_date" class="form-control floating-label end_date" placeholder="End Date" style="max-width: 220px;float: left; " name="end_date[]" value="{{ $assign->end_date }}">      
                                        </div>
                                    </div>                           
                                    <div class="row col-md-12 form-group">
                                        <label class="col-md-3">Time</label>
                                        <div class="col-md-9">     
                                            <input type="text" id="start_time" class="form-control floating-label start_time" placeholder="Start Time" style="max-width: 220px;float: left;" name="start_time[]" value="{{ $assign->start_time }}" autocomplete="off">
                                            <span style="display: inline;float: left;margin: 7px 30px;font-weight: 600;">To </span>
                                            <input type="text" id="end_time" class="form-control floating-label end_time" placeholder="End Time" style="max-width: 220px;float: left;" name="end_time[]" value="{{ $assign->end_time }}" autocomplete="off">  
                                        </div>
                                    </div>
                                </div> 
                                <!-- Third row-fluid-->
                                <div class="row">
                                   <div class="col-xs-12 addBtnWrap">
                                        <a class="btn btn-info add_button" ><i class="fas fa-plus"></i> Add</a>
                                        <input type='button' class='btn_less1 btn-danger' value='Delete' id='buttonless'/>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @endif
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

    function save(id, event,form){
        event.preventDefault();
        
        var formData = $(form).serializeArray();
        $.each(formData, function(i, field){
            console.log(field.start_date);
           /*if(field.name == 'start_time[]' || field.name == 'end_time[]'){
                var timeStart = new Date("01/01/2007 " + valuestart).getHours();
                var timeEnd = new Date("01/01/2007 " + valuestop).getHours();

                var hourDiff = timeEnd - timeStart; 
                console.log(field.value);
           }*/
        });
        
        swal({
            title: "Are you sure?",
            text: "You want to save #NUR"+id+" Schedule",
            icon: "warning",
            buttons: {
                cancel: true,
                confirm: true,
            },
            closeModal: false,
            closeModal: false,
            closeOnEsc: false,
        })
       .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                url: $(form).attr('action'),
                data: $(form).serialize(),
                type: 'POST',
                success: function(data) {
                    data = JSON.parse(data);
                    if(data['status']) {
                        swal({
                            title: data['message'],
                            text: "Press OK to continue",
                            icon: data['status'],
                            buttons: {
                                cancel: true,
                                confirm: true,
                            },
                            closeOnConfirm: false,
                            closeOnEsc: false,
                        })
                        .then((willDelete) => {
                            if (willDelete) {
                                window.location.reload();
                            }
                            });
                        } else {
                             swal("Error", data['message'], "error");
                        }
                    }
                });
            } else {
                swal("Cancelled", "#NUR"+id+" schedule can not be saved.", "error");
            }
        });
    }
    
    $(document).ready(function () { 
        $(".start_date").datepicker({
            minDate: '{{ $booking->start_date }}',
            maxDate: '{{ $booking->end_date }}',
        });
        $(".end_date").datepicker({
            minDate: '{{ $booking->start_date }}',
            maxDate: '{{ $booking->end_date }}',
        });
        $("#start_time").timepicker();
        $("#end_time").timepicker();
  
          var count=0;
          $(document).on("click", ".add_button", function () { 
              var $clone = $('.cloned-row1:eq(0)').clone(true,true);
              
              $clone.find('[id]').each(function(){this.id+='someotherpart'+count});
              $clone.find('.btn_more').after("<input type='button' class='btn_less1 btn-danger' value='Delete' id='buttonless'/>")
              $clone.attr('id', "added"+(++count));
              $clone.find("input.start_date")
                        .removeClass('hasDatepicker')
                        .removeData('datepicker')
                        .unbind()
                        .datepicker({
                            minDate: '{{ $booking->start_date }}',
                            maxDate: '{{ $booking->end_date }}',
                        });
              $clone.find("input.end_date")
                        .removeClass('hasDatepicker')
                        .removeData('datepicker')
                        .unbind()
                        .datepicker({
                            minDate: '{{ $booking->start_date }}',
                            maxDate: '{{ $booking->end_date }}',
                        });

              $clone.find("input.start_time").each(function(){
                $(this).attr("id", "").removeData().off();
                $(this).find('.add-on').removeData().off();
                $(this).find('input').removeData().off();
                $(this).timepicker();
              });

              $clone.find("input.end_time").each(function(){
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

 </script>
@endsection