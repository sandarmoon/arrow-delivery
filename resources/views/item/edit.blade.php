@extends('main')
@section('content')
  <main class="app-content">
    <div class="app-title">
      <div>
        <h1><i class="fa fa-dashboard"></i>Items</h1>
        <!-- <p>A free and open source Bootstrap 4 admin template</p> -->
      </div>
      <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="{{route('items.index')}}"> Items</a></li>
      </ul>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="tile">
          <h3 class="tile-title d-inline-block">Item Edit Form</h3>
          
          <form action="{{route('items.update',$item->id)}}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
              <div class="col-md-12">
                <div class="form-group row">
                  <div class="col">
                    <label for="InputCodeno">Codeno:</label>
                    <input class="form-control" id="InputCodeno" type="text" value="{{$item->codeno}}" name="codeno" readonly>
                  </div>

                  <div class="col">
                    <label for="InputExpiredDate">Expired Date:</label>
                    <input class="form-control" id="InputExpiredDate" type="date" name="expired_date"  value="{{$item->expired_date}}">
                    <div class="form-control-feedback text-danger"> {{$errors->first('expired_date') }} </div>
                  </div>
                </div>

                <div class="form-group row">
                  <div class="col">
                    <label for="InputReceiverName">Receiver Name:</label>
                    <input class="form-control" id="InputReceiverName" type="text" name="receiver_name" value="{{$item->receiver_name}}">
                    <div class="form-control-feedback text-danger"> {{$errors->first('receiver_name') }} </div>
                  </div>
                  <div class="col">
                    <label for="InputReceiverPhoneNumber">Receiver Phone Number:</label>
                    <input class="form-control" id="InputReceiverPhoneNumber" type="text" name="receiver_phoneno" value="{{$item->receiver_phone_no}}">
                    <div class="form-control-feedback text-danger"> {{$errors->first('receiver_phoneno') }} </div>
                  </div>
                </div>

                <div class="form-group row">
                  <div class="col">
                    <label for="InputReceiverAddress">Receiver Address:</label>
                    <textarea class="form-control" id="InputReceiverAddress" name="receiver_address">{{$item->receiver_address}}</textarea>
                     <div class="form-control-feedback text-danger"> {{$errors->first('receiver_address') }} </div>
                  </div>
                </div>

                <div class="row my-3">
                  <div class="col-4">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="rcity" id="incity" value="1" @if($item->sender_gate_id==null && $item->sender_postoffice_id==null)
                        checked="checked" 
                       @endif>
                      <label class="form-check-label" for="incity">
                        {{ __("In city")}}
                      </label>
                    </div>
                  </div>

                  <div class="col-4">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="rcity" id="gate" value="2" @if($item->sender_gate_id!=null)
                        checked="checked" 
                       @endif>
                      <label class="form-check-label" for="gate" >
                        {{ __("Gate")}}
                      </label>
                    </div>
                  </div>

                  <div class="col-4">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="rcity" id="post" value="3" @if($item->sender_postoffice_id!=null)
                        checked="checked" 
                       @endif >
                      <label class="form-check-label" for="post">
                        {{ __("Post Office")}}
                      </label>
                    </div>
                  </div>
                  <div class="form-control-feedback text-danger"> {{$errors->first('rcity') }} </div>
                </div>

                <div class="form-row mygate">
                  <div class="form-group col-md-6 ">
                    <label for="mygate">{{ __("Sender Gate")}}:</label><br>
                    <select class="js-example-basic-single " id="mygate" name="mygate" >
                      <option value="">{{ __("Choose Gate")}}</option>
                      @foreach($sendergates as $row)
                        <option value="{{$row->id}}"

                          @if($row->id ==$item->sender_gate_id)
                          selected="selected" 
                          @endif

                          >{{$row->name}}</option>
                      @endforeach
                    </select>
                    {{-- <div class="form-control-feedback text-danger"> {{$errors->first('mygate') }} </div> --}}
                  </div>

                  <div class="form-group col-md-6 ">
                    <label for="mygate">{{ __("Sender Gate")}}:</label><br>
                    <select class=" form-control" id="gateTownships" name="gateTownships" >
                      <option data-group="0" value="">{{ __("Choose Gate")}}</option>
                      @foreach($gateTownships as $row)
                        <option data-group="{{$row->sender_gate_id}}" value="{{$row->id}}"

                          @if($row->id ==$item->township_id)
                          selected="selected" 
                          @endif

                          >{{$row->name}}</option>
                      @endforeach
                    </select>
                    {{-- <div class="form-control-feedback text-danger"> {{$errors->first('mygate') }} </div> --}}
                  </div>
                </div>

                <div class="form-group myoffice">
                  <label for="myoffice">{{ __("Sender PostOffice")}}:</label><br>
                  <select class="js-example-basic-single" id="myoffice" name="myoffice"  >
                    <option value="">{{ __("Choose Post Office")}}</option>
                    @foreach($senderoffice as $row)
                      <option value="{{$row->id}}" @if($item->sender_postoffice_id==$row->id) selected @endif>{{$row->name}}</option>
                    @endforeach
                  </select>
                  <div class="form-control-feedback text-danger"> {{$errors->first('mygate') }} {{$errors->first('receiver_township') }} {{$errors->first('myoffice') }} </div>
                </div>

                <div class="form-group township">
                  <input type="hidden" name="oldtownship" value="{{$item->township_id}}" id="oldtownship">
                  <label for="InputReceiverTownship">Receiver Township:</label>
                  <select class="js-example-basic-single form-control mytownship" id="InputReceiverTownship" name="receiver_township">
                    <optgroup label="Choose Township">
                      <option>Choose township</option>
                      @foreach($townships as $row)
                        <option value="{{$row->id}}" @if($item->township_id==$row->id){{'selected'}}@endif>{{$row->name}}</option>
                      @endforeach
                    </optgroup>
                  </select>
                  <div class="form-control-feedback text-danger"> {{$errors->first('mygate') }} {{$errors->first('receiver_township') }} {{$errors->first('myoffice') }} </div>
                </div>

                <div class="form-group row">
                  <div class="col">
                    <label for="other">{{ __("Delivery Type")}}:</label>
                    <select class="form-control paystatus" name="amountstatus" >
                      <optgroup label="Choose type">
                        <option value="1" @if($item->paystatus==1) {{"selected"}} @endif>Unpaid</option>
                        <option value="2" @if($item->paystatus==2) {{"selected"}} @endif>Allpaid</option>
                        <option value="3" @if($item->paystatus==3) {{"selected"}} @endif>Only Deli</option>
                        <option value="4" @if($item->paystatus==4) {{"selected"}} @endif>Only Item Price</option>
                      </optgroup>
                    </select>
                    <div class="form-control-feedback text-danger"> {{$errors->first('amountstatus') }} </div>
                  </div>

                  <div class="col">
                    <label for="other">{{ __("Other Charges")}}:</label>
                    <input class="form-control" id="other" type="number" name="othercharges" value="{{$item->other_fees}}">
                  </div>
                </div>

                <div class="form-group row">

                  <div class="col">
                    <label for="InputDeliveryFees">Delivery Fees:</label>
                    <input class="form-control" id="InputDeliveryFees" type="number" name="delivery_fees" value="{{$item->delivery_fees}}">
                    <div class="form-control-feedback text-danger"> {{$errors->first('delivery_fees') }} </div>
                  </div>
                  
                  <div class="col">
                    <label for="InputDeposit">Item Price:</label>
                    <input class="form-control" id="InputDeposit" type="number" name="deposit" value="{{$item->deposit}}">
                    <div class="form-control-feedback text-danger"> {{$errors->first('deposit') }} </div>
                  </div>

                  

                  <div class="col">
                    <label for="InputAmount">Amount:</label>
                    <input class="form-control" id="InputAmount" type="number" name="amount" value="{{$item->amount}}">
                    <div class="form-control-feedback text-danger"> {{$errors->first('amount') }} </div>
                  </div>
                </div>

                <div class="form-group row">
                  <div class="col">
                    <label for="InputRemark">Remark:</label>
                    <textarea class="form-control" id="InputRemark" name="remark">{{$item->remark}}</textarea>
                    <div class="form-control-feedback text-danger"> {{$errors->first('remark') }} </div>
                  </div>
                </div>
                @if($item->way)
                <div class="form-group row">
                  <div class="col">
                    <label>{{ __("Choose Delivery Man")}}:</label>
                    <select class="js-example-basic-multiple form-control" name="delivery_man">
                      <option>Choose Delivery Man</option>
                      @foreach($deliverymen as $man)
                        <option value="{{$man->id}}" @if($man->id == $item->way->delivery_man_id) {{'selected'}} @endif>{{$man->user->name}}
                          @foreach($man->townships as $township)
                            ({{$township->name}})
                          @endforeach
                        </option>
                      @endforeach
                    </select>
                  </div>
                </div>
                @endif

                <div class="form-group row">
                  <div class="col">
                    <button class="btn btn-primary" type="submit">Save</button>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
      
    </div>
  </main>
@endsection 
@section('script')
<script type="text/javascript">
  $(document).ready(function(){
    
    setTimeout(function(){ $('.myalert').hide(); showDiv2() },3000);
    var today = new Date();
    var numberofdays = 3;
    today.setDate(today.getDate() + numberofdays); 
    var day = ("0" + today.getDate()).slice(-2);
    var month = ("0" + (today.getMonth() + 1)).slice(-2);
    var incityday= today.getFullYear()+"-"+(month)+"-"+(day) ;
    console.log(incityday);
    $(".pickdate").val(incityday);

    $(".mytownship").change(function(){
      var id=$(this).val();
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.post("/delichargebytown",{id:id},function(res){
        $("#InputDeliveryFees").val(res);
        var deposit=Number($('#InputDeposit').val()) | 0;
        var other=Number($('#other').val()) | 0;

        if (deposit>0 && other>0) {
          $('#InputAmount').val(Number(res)+other+deposit)
          var amount=Number($('#InputAmount').val()) | 0;
          if($('.paystatus').val() == 2 || $('.paystatus').val() == 3){
            $('#InputDeposit').val(0)
          }else{
            $('#InputDeposit').val(amount-Number(res)-other)
          }
        }
      })
    })

    $("#InputAmount").change(function(){
      var other=Number($('#other').val()) | 0;
      var deposit=Number($('#InputDeposit').val()) | 0;
      var delivery_fees=Number($("#InputDeliveryFees").val())|0;
      $(this).val(delivery_fees+deposit+other)
    })

    $("#InputAmount").focus(function(){
      var amount = Number($(this).val())|0;
      var delivery_fees=Number($("#InputDeliveryFees").val())|0;
      var other=Number($('#other').val()) | 0;

      if($('.paystatus').val() == 2 || $('.paystatus').val() == 3){
        $('#InputDeposit').val(0)
        $(this).val(delivery_fees+other)
      }else if(other>0){
        $('#InputDeposit').val(amount-delivery_fees-other)
      }else{
        $('#InputDeposit').val(amount-delivery_fees)
      }
    })

    $("#InputDeposit").focus(function(){
      var amount=Number($('#InputAmount').val())|0;
      var delivery_fees=Number($("#InputDeliveryFees").val())|0;
      var other=Number($('#other').val()) | 0;
      var deposit = Number($(this).val()) | 0;

      if (amount>0 && delivery_fees>0 && other>0) {
        if (deposit>0) {
          var amount = delivery_fees+deposit+other
          $('#InputAmount').val(amount)
        }
        if($('.paystatus').val() == 2 || $('.paystatus').val() == 3){
          $(this).val(0)
        }else{
          $(this).val(amount-delivery_fees-other);
        }
      }else if(amount==0){
        $(this).val('');
      }
    })

    $("#InputDeposit").change(function(){
      var deposit=parseInt($('#InputDeposit').val());
      var delivery_fees=parseInt($("#InputDeliveryFees").val());
      var other=Number($('#other').val()) | 0;

      var amount=deposit+delivery_fees+other;
      $("#InputAmount").val(amount);
    })
    
    $(function(){
        var dtToday = new Date();
        var month = dtToday.getMonth() + 1;
        var day = dtToday.getDate();
        var year = dtToday.getFullYear();
        if(month < 10)
            month = '0' + month.toString();
        if(day < 10)
            day = '0' + day.toString();
        
        var maxDate = year + '-' + month + '-' + day;
        //alert(maxDate);
        $('#txtDate').attr('min', maxDate);
    });

    $("input[name=rcity]").click(function(){
      if ($(this).is(':checked')){
        $(".township").show();
        var id=$(this).val();
        if(id==1){
          var today = new Date();
          var numberofdays = 3;
          today.setDate(today.getDate() + numberofdays); 
          var day = ("0" + today.getDate()).slice(-2);
          var month = ("0" + (today.getMonth() + 1)).slice(-2);
          //console.log(month);
          var incityday= today.getFullYear()+"-"+(month)+"-"+(day) ;
          // console.log(incityday);
          $(".paystatus").val(1);
          $(".paystatus").attr('readonly',false);
          $(".pickdate").val(incityday);
          $('#InputDeposit').prop('readonly',false);
          getTownship(id);

          let oldtownship = $('#oldtownship').val();
          // alert($('#InputReceiverTownship').val() + oldtownship);
          $.post("/delichargebytown",{id:oldtownship},function(res){
            $("#InputDeliveryFees").val(res);
          })
        }else{
          var today = new Date();
          var numberofdays = 7;
          today.setDate(today.getDate() + numberofdays); 
          var day = ("0" + today.getDate()).slice(-2);
          var month = ("0" + (today.getMonth() + 1)).slice(-2);
          //console.log(month);
          var gateday= today.getFullYear()+"-"+(month)+"-"+(day) ;
          console.log(gateday);
          // type change allpaid
          $(".paystatus").val(2);
          $(".paystatus").attr('readonly',true);
          $(".pickdate").val(gateday);
          $("#InputDeposit").val(0);
          $('#InputDeposit').prop('readonly',true);
          getTownship(id);
          $("#InputDeliveryFees").val(1000);
        } 
      }
    });

    function getTownship(id){
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.post("/townshipbystatus",{id:id},function(res){
        // console.log(res);
        var html="";
        html+=`<option>Choose township</option>`
        $.each(res,function(i,v){
          html+=`<option value="${v.id}">${v.name}</option>`
        })
        $("#InputReceiverTownship").html(html);
        let oldtownship = $('#oldtownship').val();
        $(`#InputReceiverTownship option:eq(${oldtownship})`).prop('selected', true)
        // alert(oldtownship)
      })
    }

    var checked=$("input[name='rcity']:checked").val();
    console.log(checked);
    if(checked==1){
      // getTownship(checked);
      $(".mygate").hide();
      $(".myoffice").hide();
      $('.township').show();
      $('#InputDeposit').prop("readonly",false);

      var paystatus = $('.paystatus :checked').val();
      if(paystatus == 2){
        $('#InputDeposit').prop('readonly',true);
      }
      if(paystatus == 3){
        $('#InputDeposit').prop('readonly',true);
      }


    }else if(checked==2){
      // getTownship(checked);
      $(".mygate").show();
      $(".myoffice").hide();
      $('.township').hide();
          $(".paystatus").attr('readonly',true);

      $('#InputDeposit').prop("readonly",true);
    }else if(checked==3){
      $(".mygate").hide();
      $(".myoffice").show();
      $('.township').hide();

      $('#InputDeposit').prop("readonly",true);
      $(".paystatus").attr('readonly',true);


      // getTownship(checked);
    }

    $("#gate").click(function(){
      $(".mygate").show();
      $(".myoffice").hide();
      $(".township").hide();
      $('#InputDeliveryFees').val(1000);
      $('#InputAmount').val($('#InputDeliveryFees').val());
    })

    $("#incity").click(function(){
      $(".mygate").hide();
      $(".myoffice").hide();
      $(".township").show();

    })

    $("#post").click(function(){
      $(".mygate").hide();
      $(".myoffice").show();
      $(".township").hide();
      $('#InputDeliveryFees').val(1000);
      $('#InputAmount').val($('#InputDeliveryFees').val());
    })

    $('.js-example-basic-single').select2({width:'100%'});



    // if type is allpaid, set item price into 0
    $('.paystatus').change(function () {
      let type = $(this).val()
      if( type == 2 || type == 3){
        $("#InputDeposit").val(0);
        $('#InputDeposit').prop('readonly',true);
      }else{
        $("#InputDeposit").val("");
        $('#InputDeposit').prop('readonly',false);
      }
    })



    $('#mygate').change(function(){
      let gid=$(this).val();
      let townshipgate=$('#gateTownships');

       // $('#gateTownships').val(gid);
       $('#gateTownships').val('');
      $('option', townshipgate).filter(function(){
            if (
                 $(this).attr('data-group') === gid 
              || $(this).attr('data-group') === 'SHOW'
            ) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
       $('#gateTownships').trigger('change');
      // $('#mygate').trigger('change');
    })
    
  })
</script>
@endsection