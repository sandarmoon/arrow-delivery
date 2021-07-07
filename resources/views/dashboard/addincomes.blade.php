@extends('main')
@section('content')
  <main class="app-content">
    <div class="app-title">
      <div>
        <h1><i class="fa fa-dashboard"></i> {{ __("Incomes")}}</h1>
        <!-- <p>A free and open source Bootstrap 4 admin template</p> -->
      </div>
      <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="{{route('incomes')}}">{{ __("Incomes")}}</a></li>
      </ul>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="alert alert-primary success d-none" role="alert"></div>
        <div class="tile">
          <h3 class="tile-title d-inline-block">Income Create Form</h3>
          
          <div class="row">
            <div class="form-group col-md-6">
              <label for="InputDeliveryMan">{{ __("Select Delivery Man")}}:</label>
              <select class="js-example-basic-single" id="InputDeliveryMan" name="deliveryman">
                <optgroup label="Select Delivery Man">
                  @foreach($delivery_men as $deliveryman)
                    <option value="{{$deliveryman->id}}" data-name="{{$deliveryman->deliveryname}}">{{$deliveryman->deliveryname}}</option>
                  @endforeach
                </optgroup>
              </select>
            </div>
            
          </div>

          <div id="incomeform">
          </div>
        </div>
      </div>
      
    </div>
  </main>


<div class="modal fade" id="incomemodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Income form</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- <form action="{{route('incomes.store')}}" method="POST">
          @csrf -->
          <h3 class="text-dark">{{ __("Total Amount")}}:<span class="totalamount text-danger"></span></h3>
          <input type="hidden" id="totalamount" name="amount">
          <input type="hidden" name="way_id" id="way_id">
           <input type="hidden" name="deliveryfee" id="deliveryfee">
           <input type="hidden" name="deposit" id="deposit">
          <div class="form-group">
            <label for="exampleFormControlSelect1">{{ __("PaymentTypes")}}</label>
            <select class="form-control" id="paymenttype" name="paymenttype">
              <option>Choose Payment Type</option>
            </select>
          </div>

          <div class="form-group bankform">
            <label for="bank">{{ __("Banks")}}</label>
            <select class="form-control" id="bank" name="bank">
              <option>Choose Bank</option>
            </select>
          </div>

          <div class="form-group bamountform">
            <label for="bankamount">{{ __("Bank amount")}}</label>
            <input type="number" name="bank_amount" id="bankamount" class="form-control">
          </div>
          <div class="form-group camountform">
            <label for="cashamount">{{ __("Cash amount")}}</label>
            <input type="number" name="cash_amount" id="cashamount" class="form-control">
          </div>

          <div class="form-group carryfees">
            <label for="carryfees">Carry Fees (တန်ဆာခ)</label>
            <input type="number" name="carryfees" class="form-control" id="carryfees">
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __("Close")}}</button>
        <button type="button" class="btn btn-primary incomesave">{{ __("Save")}}</button>
        <!-- </form> -->
      </div>
    </div>
  </div>
</div>
@endsection 
@section('script')
  <script type="text/javascript">
    $(document).ready(function () {
      $('.js-example-basic-single').select2({
        width: '100%',
      });

      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      // $('#incomeform').hide();
      $(".bankform").hide();
      $(".bamountform").hide();
      $(".camountform").hide();

      $('#InputDeliveryMan').change(function () {
        var deliveryman_id = $(this).val();
        var deliveryman = $("#InputDeliveryMan option:selected").text();
        //alert(deliveryman)
        getdata(deliveryman_id,deliveryman);
      })

      function getdata(deliveryman_id,deliveryman){
        var url = `/incomes/getsuccesswaysbydeliveryman/${deliveryman_id}`;
        $.get(url,function (response) {
          console.log(response);
          var html = `
            
            <label>Success Ways By ${deliveryman}:</label>

            <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Codeno</th>
                    <th>Township</th>
                    <th>Customer Name</th>
                    <th>Delivered Date</th>
                    <th>Delivery Fees</th>
                    <th>Item Price</th>
                    <th>Subtotal</th>
                    <th>Accepted Amount</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>`;
                var i=1;
                var total=0;
                var pp="";
                $.each(response.paymenttypes,function(i,v){
                  pp+=`<option value="${v.id}">${v.name}</option>`
                })
                $("#paymenttype").html(pp);

                var hh=`<option value=${null}>Choose Bank</option>`;
                $.each(response.banks,function(i,v){
                  hh+=`
                  <option value="${v.id}">${v.name}</option>`
                })
                $("#bank").html(hh);
          console.log(response.ways)
          for(let row of response.ways){
            // console.log(row);
            let acceptedAmount = 0;
            if (row.item.paystatus==1) {
              acceptedAmount = Number(row.item.item_amount)
            }else if(row.item.paystatus==2){
              acceptedAmount = 0
            }else if(row.item.paystatus==3){
              acceptedAmount = Number(row.item.delivery_fees)+Number(row.item.other_fees)
            }else if(row.item.paystatus==4){
              acceptedAmount = Number(row.item.deposit)
            }

            total+= acceptedAmount


            if (row.status_code=="002") {
              color = "table-warning"
              if (row.item.paystatus==1) {
                total-=Number(row.item.item_amount)
              }else if(row.item.paystatus==2){
                total-=0
              }else if(row.item.paystatus==3){
                total-=Number(row.item.delivery_fees)+Number(row.item.other_fees)
              }else if(row.item.paystatus==4){
                total-=Number(row.item.deposit)
              }
            }else if(row.status_code=="003"){
              total -= Number(row.item.deposit)+Number(row.item.delivery_fees)
              color = "table-danger"
            }else{
              color = ""
            }
            html +=`
              <tr class="${color}">
                    <td>${i++}</td>
                    <td>
                      ${row.item.item_code}`
                    if(row.item.paystatus == 2){
                      html+=` <span class="badge badge-success">All Paid</span>`
                    }else if(row.item.paystatus == 3){
                      html+=` <span class="badge badge-success">Only Deli</span>`
                    }else if(row.item.paystatus == 4){
                      html+=` <span class="badge badge-success">Only Item Price</span>`
                    }
                    html+=`</td>
                    <td>`
                    if(row.item.township){
                      html += `${row.item.township.township_name}`

                    }else if(row.item.sendergate){

                        html += `${row.item.sendergate.gate_name}<br>
                        <span class="badge badge-dark">Gate</span>`
                    }else if(row.item.senderpostoffice){

                      html += `${row.item.senderpostoffice.postoffice_name}<br>
                      <span class="badge badge-dark">Post office</span>`

                    }
                    html+=`</td>
                    <td>
                      ${row.item.receiver_name}
                    </td>`
                    if(row.delivery_date){
                      html+=`<td>${formatDate(row.delivery_date)}</td>`
                      html+=`<td>${thousands_separators(row.item.delivery_fees)}`
                      if(row.item.other_fees > 0){
                        html += `+ ${thousands_separators(row.item.other_fees)}`
                      }
                      html+=`</td><td>${thousands_separators(row.item.deposit)}</td>
                              <td>${thousands_separators(Number(row.item.deposit)+Number(row.item.delivery_fees)+Number(row.item.other_fees))}</td>`

                    }else{
                      html+=`<td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>`
                    }
                    html+=`<td>${thousands_separators(acceptedAmount)}</td>`
                    
                    if(row.status_code=="001"){
                      html+=`<td><button class="btn btn-sm btn-primary btnsave" data-id="${row.id}" data-amount="${row.item.item_amount}" data-deliveryfee="${row.item.delivery_fees+row.item.other_fees}" data-deposit="${row.item.deposit}" data-paystatus="${row.item.paystatus}">save</button></td>`
                      }else if(row.status_code=="002"){
                       html+= `<td><span class="badge badge-info">return way</span> (${row.item.expired_date})</td>`
                      }else if(row.status_code=="003"){
                       html+= `<td><span class="badge badge-danger">reject way</span></td>`
                      }
              html+=`</tr>`;
          }
          html+=`<tr>
                    <td colspan="8">Total:</td>
                    <td colspan="2">${thousands_separators(total)} Ks</td>
                  </tr>`;
          $('#incomeform').html(html);
        })
      }

      $("#incomeform").on('click','.btnsave',function(){
        $("#incomemodal").modal('show');
        var amount=$(this).data("amount");
        var id=$(this).data("id");
        var delivery_fees=$(this).data("deliveryfee");
        var deposit = $(this).data("deposit");
        let paystatus = $(this).data("paystatus");

        $(".totalamount").html(amount);
        $("#totalamount").val(amount);
        $("#way_id").val(id);
        $("#deliveryfee").val(delivery_fees);
        $("#deposit").val(deposit);

        if (paystatus == 2) {
          $("#paymenttype").val(4)
          $('#paymenttype').attr('disabled',true)
        }else if (paystatus == 3) {
          $("#paymenttype").val(5)
          $('#paymenttype').attr('disabled',true)
          $(".bankform").show();
          $('.bankform option[value="1"]').show();
        }else if (paystatus == 4) {
          $("#paymenttype").val(6)
          $('#paymenttype').attr('disabled',true)
          $(".bankform").show();
          $('.bankform option[value="1"]').show();
        }else{
          $('#paymenttype').attr('disabled',false)
        }
        // carry fees
        $('.carryfees').hide();

        $.post("{{route('getitembyway')}}",{wayid:id},function (response) {
          // console.log(response)
          if (response.deposit == 0 && (response.sender_gate_id!=null || response.sender_postoffice_id!=null)) {
            $('.carryfees').show();
          }else{
            $("#carryfees").val('');
          }
        })
      })

      $("#paymenttype").change(function(){
        var id=$(this).val();
        if(id==2){
          $(".bankform").show();
          $('.bankform option[value="1"]').hide();
        }else if(id==3){
          $(".bankform").show();
          $('.bankform option[value="1"]').hide();
          $(".bamountform").show();
          $(".camountform").show();
        }else if(id==5 || id==6){
          $(".bankform").show();
          $('.bankform option[value="1"]').show();
        }else{
          $(".bankform").hide();
          $(".bamountform").hide();
          $(".camountform").hide();
        }
      })

      setTimeout(function(){ $('.myalert').hide(); showDiv2() },3000);

      $(".incomesave").click(function(){
        var deliveryman_id = $("#InputDeliveryMan option:selected").val();
        var deliveryman = $("#InputDeliveryMan option:selected").text();
        var deliveryfee=$("#deliveryfee").val();
        var deposit = $("#deposit").val();
        var amount=$("#totalamount").val();
        var paymenttype=$("#paymenttype").val();
        var way_id=$("#way_id").val();
        var bank=$("#bank").val()
        var bank_amount=$("#bankamount").val();
        var cash_amount=$("#cashamount").val();
        var carryfees=$("#carryfees").val();
        var url="{{route('incomes.store')}}";
        $.ajax({
          url:url,
          type:"post",
          data:{deliveryfee:deliveryfee,deposit:deposit,amount:amount,paymenttype:paymenttype,way_id:way_id,bank:bank,bank_amount:bank_amount,cash_amount:cash_amount,carryfees:carryfees},
          dataType:'json',
          success:function(response){
            if(response.success){
              $('#incomemodal').modal('hide');
              $('.success').removeClass('d-none');
              $('.success').show();
              $('.success').text('successfully added to income list');
              $('.success').fadeOut(3000);
              getdata(deliveryman_id,deliveryman);
            }
          }
        })
      })
    })

    // Y/M/D into D/M/Y
    function formatDate (input) {
      var datePart = input.match(/\d+/g),
      year = datePart[0].substring(0,4), // get only two digits
      month = datePart[1], day = datePart[2];
      return day+'-'+month+'-'+year;
    }

    function thousands_separators(num){
      var num_parts = num.toString().split(".");
      num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      return num_parts.join(".");
    }
  </script>
@endsection
