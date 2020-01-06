@extends('layouts.app')

@section('content')
<div class="content-wrapper">
   <div class="page-content fade-in-up">
      <div class="row">
         <div class="col-lg-12 col-md-12">
            <div class="ibox">
               <div class="ibox-body">
                  <div class="tab-content">
                  @include('flash::message')
                     <div class="tab-pane fade show active" id="tab-2">                   
                        <div class="card sendMsgCard">
                          <div class="card">
                             <div class="card-header" style="background-color: #ddd;">
                                <h5>Send Message
                                <a href="{{ route('sendmsg.index') }}"><button class="btn btn-info float-right"><i class="fas fa-history"></i> History</button></a></h5>
                             </div>
                             <form action="{{ route('sendmsg.store') }}" method="post" class="form-horizontal">
                             @csrf 
                             <div class="tab-content row">
                                <div class="tab-pane fade show active col-md-12" id="tab-2">
                                  <div class="card-body">
                                    <div class="col-xs-12 sendMsgWrap">
                                      <div class="row form-group">
                                          <label class="col-md-2">User Select</label>
                                          <div class="col-md-10">                                                
                                              <select name="user_id" class="form-control">
                                                @foreach($users as $user)
                                                <option value="{{ $user['id'] }}">{{ "+".$user['country_code'].$user['mobile_number']." ( ".$user['name']." )" }}</option>
                                                @endforeach
                                              </select>
                                            @if ($errors->has('user'))
                                                <span class="text-danger">
                                                    <strong>{{ $errors->first('user') }}</strong>
                                                </span>
                                            @endif
                                          </div>
                                      </div>
                                      <div class="row form-group">
                                          <label class="col-md-2">Message</label>
                                          <div class="col-md-10">                                                
                                              <textarea class="form-control" name="msg" rows="5" placeholder="Message"></textarea>
                                            @if ($errors->has('msg'))
                                                <span class="text-danger">
                                                    <strong>{{ $errors->first('msg') }}</strong>
                                                </span>
                                            @endif
                                          </div>
                                      </div>
                                      <div class="col-xs-12">
                                        <div class="form-group col-sm-8 offset-sm-2"><br>
                                          <button class="btn btn-primary pull-right" type="submit">Send</button>
                                        </div>
                                        <div class="form-group col-sm-5 pull-right"></div>
                                      </div>
                                    </div>   
                                  </div>
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
</div>
@endsection