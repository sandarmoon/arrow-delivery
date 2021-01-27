@extends('main')
@section('content')
  <main class="app-content">
    <div class="app-title">
      <div>
        <h1><i class="fa fa-dashboard"></i> {{ __("Ways History")}}</h1>
        <!-- <p>A free and open source Bootstrap 4 admin template</p> -->
      </div>
      <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
      </ul>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="tile">
          @php $mytime = Carbon\Carbon::now(); @endphp
          

         
      <div class="row">
            <div class="form-group col-md-3">
              <label for="InputStartDate">{{ __("Start Date")}}:</label>
              <input type="date" class="form-control" id="InputStartDate" name="start_date">
            </div>
            <div class="form-group col-md-3">
              <label for="InputEndDate">{{ __("End Date")}}:</label>
              <input type="date" class="form-control" id="InputEndDate" name="end_date">
            </div>
            @role('staff')
            <div class="form-group col-md-3">
              <label for="InputClient">{{ __("Select Client")}}:</label>
                <select class="js-example-basic-single" id="InputClient" name="client">
                  <option value="">Choose Client</option>
                    @foreach($clients as $client)
                      <option value="{{$client->id}}" data-name="{{$client->clientname}}">{{$client->clientname}}</option>
                    @endforeach
                </select>
            </div>
            @endrole
            <div class="form-group col-md-3">
              <button class="btn btn-primary search_btn mt-4" type="button">{{ __("Search")}}</button>
            </div>
        </div>
          
          
          <div class="table-responsive">
            <table class="table table-bordered" id="waystable">
              <thead>
                <tr>
                  <th>{{ __("#")}}</th>
                  <th>{{ __("Item Code")}}</th>
                  <th>{{ __("Receiver Name ")}}</th>
                  <th>{{ __("Amount")}}</th>
                  <th>{{ __("Place")}}</th>
                  <th>{{ __("Client")}}</th>
                  <th>{{ __("Delivery Man")}}</th>
                  <th>{{ __("ways state")}}</th>
                  <th>{{ __("Remark")}}</th>
                  {{-- <th>Action</th> --}}
                </tr>
              </thead>
              <tbody>
                
              </tbody>
            </table>
          </div>
           
          
        </div>
      </div>
    </div>
  </main>
@endsection 
@section('script')
<script type="text/javascript">
  $(document).ready(function(){
     $('.js-example-basic-single').select2({width:'100%'});
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    function thousands_separators(num){
      var num_parts = num.toString().split(".");
      num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      return num_parts.join(".");
    }

    $('.search_btn').click(function () {
      var sdate = $('#InputStartDate').val();
      var edate = $('#InputEndDate').val();

      var client_id=$("#InputClient").val();
      var client_name=$("#InputClient option:selected").data('name');
      console.log(client_id,client_name);

      var url="{{route('getwayhistory')}}";
      var i=1;
      $('#waystable').DataTable({
        "pageLength": 100,
        "processing": true,
        "serverSide": true,
        destroy:true,
        "sort":false,
        "stateSave": true,
        "ajax": {
            url: url,
            type: "POST",
            data:{sdate:sdate,edate:edate,client_id:client_id},
            dataType:'json',
        },
        "columns": [
          {"data":'DT_RowIndex'},
          { "data": "item.codeno",},
          {"data":"item.receiver_name"},
          {"data":"item.amount"},
          {"data":function(data){
            if(data.item.township_id != undefined && data.item.sender_gate_id == undefined && data.item.sender_postoffice_id == undefined){
              // console.log('hi township');
              return data.item.township.name;
            }else if(data.item.township_id == undefined && data.item.sender_gate_id != undefined && data.item.sender_postoffice_id == undefined){
              // console.log('hi gate');
              return data.item.sender_gate.name;
            }else if(data.item.township_id == undefined && data.item.sender_gate_id == undefined && data.item.sender_postoffice_id != undefined){
              // console.log('hi office');
              return data.item.sender_postoffice.name;
            }
          }},
          { "data": "item.pickup.schedule.client.user.name" },
          { "data": "delivery_man.user.name" },
          {"data":"status_code",
            render:function(data){
              if(data=="001"){
                return "success"
              }else if(data=="002"){
                return "return"
              }else if(data=="005"){
                return "pending"
              }else if(data=="003"){
                return "reject"
              }
            }
          },
          { "data": "remark",
            render:function(data){
              if(data==null){
                return "-"
              }else{
                return data
              }
            }
          }
        ],
        "info":false
      });
    })


  })
</script>
@endsection
