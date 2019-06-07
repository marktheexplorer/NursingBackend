
@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="content-wrapper">
    <div class="page-heading">
        <h1 class="page-title">FAQs Reorder</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a>
            </li>
            <li class="breadcrumb-item">Faqs</li>
        </ol>
    </div>
    @include('flash::message')
    <div class="page-content fade-in-up">
        <div class="ibox">           
            <div class="ibox-body">
                <div class="tab-content">
                    <form action="{{ route('faqs.updateorder') }}" method="post" class="form-horizontal" id="sortableform">
                    @csrf
                        <div class="row">
                            <div class="form-group col-md-12">
                                <ul id="sortable"><?php
                                    $oldorders = ''; ?>
                                    @foreach($faqs as $key => $faq)
                                        <li class="ui-state-default sortbalclass" id="{{ $faq->id }}" >
                                            <strong>
                                                <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>{{ $faq->question }}
                                            </strong>
                                        </li>
                                        <?php $oldorders .= $faq->id.",";?>
                                    @endforeach
                                </ul>
                            </div>
                            <input type="hidden" id="faqorders" value="<?php echo rtrim($oldorders, ','); ?>" name="faqorders"><?php
                            if($oldorders != ''){ ?>
                                <div class="form-group col-md-12">&nbsp;&nbsp;&nbsp;
                                    <button class="btn btn-info" id="submitform">Submit</button>
                                </div><?php
                            } ?>    
                        </div>    
                    </form>    
                </div>    
            </div>
        </div>
	</div>    
</div>
@endsection

@section('footer-scripts')
    <style>
    #sortable { list-style-type: none; margin: 5; padding: 0;  }
    #sortable li{padding:10px;margin:10px;cursor: grab;}/*
    #sortable li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; height: 18px; }
    #sortable li span { position: absolute; margin-left: -1.3em; }*/
    </style>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
    $( function() {
        $( "#sortable" ).sortable({
            stop: function( event, ui ) {
                var i = 0;
                var ids = [];  
                $(".sortbalclass").each(function(){
                    ids[i++] =  $(this).attr("id"); //this.id
                });
                $("#faqorders").val(ids);
            }
        });
        $( "#sortable" ).disableSelection();
    });

    $("#submitform").click(function(){
        $("#sortableform").Submit();
    });
  </script>
@endsection
