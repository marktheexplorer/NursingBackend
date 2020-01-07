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
            <div class="ibox">
               <div class="ibox-body">
                  <div class="tab-content">
                     <div class="tab-pane fade show active" id="tab-2">
                        <form action="" enctype = 'multipart/form-data' method="post" class="form-horizontal patientForm">
                           @csrf                           
                           <div class="card managebookingCard">
                                <div class="col-xs-12 managebookingInfoWrap">                                
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label><u>{{ '#NUR'.$booking->id }}</u></label>
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
                                            <p>John Snow</p>
                                        </div>
                                    </div>                              
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>Schedule Date/Time</label>
                                        </div>
                                        <div class="col-md-9">
                                            <p>John Snow</p>
                                        </div>
                                    </div>
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
                                            <div class="col-xs-12 actionWrap">
                                                <button class="btn-sm btn-primary btn-cir" title="Edit"><i class="fas fa-pencil-alt"></i></button>
                                                <a class="btn-sm btn-danger btn-cir delBtn" title="Delete"><i class="fas fa-trash-alt"></i></a>
                                            </div>
                                            <div class="row form-group">
                                                <label class="col-md-3">Choose Care Giver</label>
                                                <div class="col-md-9">                                           
                                                    <select name="qualification[]" class="form-control  select2">
                                                        <option value="2"  >Certified Nursing Assistant</option>
                                                        <option value="1"  >Home Health Aide</option>
                                                        <option value="5"  >Licensed Practical Nurse</option>
                                                        <option value="6"  >Registered Nurse</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <label class="col-md-3">Book Date</label>
                                                <div class="col-md-9">                                           
                                                    <input type="text" id="start_date" class="form-control floating-label" placeholder="Start Date" style="max-width: 220px;float: left; margin-right: 90px" name="start_date" >                                              
                                                </div>
                                            </div>                                        
                                            <div class="row form-group">
                                                <label class="col-md-3">Time</label>
                                                <div class="col-md-9">     
                                                    <input type="text" id="todaystarttime" class="form-control floating-label" placeholder="Start Time" style="max-width: 220px;float: left;" name="todaystarttime" value="12:00 AM">
                                                    <span style="display: inline;float: left;margin: 7px 30px;font-weight: 600;">To </span>
                                                    <input type="text" id="todayendtime" class="form-control floating-label" placeholder="End Time" style="max-width: 220px;float: left;" name="todayendtime">   
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

                                                        
    $('#start_date').bootstrapMaterialDatePicker({
        format : 'MM/DD/YYYY',
        weekStart : 0, 
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

    $('.managebookingWrapItem .select2').select2({
        placeholder: {
            text: '-- Choose Care Giver --'
        }
    });
    $(".addBtnWrap .btn").on('click', function(){         

        $('.managebookingWrapItem:first').clone(true).appendTo('.managebookingWrap');
    });

    $('.managebookingWrapItem .delBtn').click(function(){
        $(this).parent().parent().remove();
    });

 </script>
@endsection