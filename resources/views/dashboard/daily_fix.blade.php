@extends('main')
@section('content')
  <main class="app-content">
    <div class="app-title">
      <div>
        <h1><i class="fa fa-dashboard"></i> Daily fix</h1>
        <!-- <p>A free and open source Bootstrap 4 admin template</p> -->
      </div>
      <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
      </ul>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="alert alert-primary success d-none" role="alert"></div>
        <div class="tile">
          @php $mytime = Carbon\Carbon::now(); @endphp
          <h3 class="tile-title d-inline-block">{{ __("Daily Fix")}} ({{$mytime->toFormattedDateString()}})</h3>
          
          <div class="row">
            <div class="col-md-12">
              <form method="#" action="#" class="myform">
                <div class="form-group row">
                  <div class="col-md-3">
                    <label for="InputClient">{{ __("Select Client")}}:</label>
                    <select class="form-control" id="InputClient" name="client">
                      <optgroup label="Select Client">
                        @foreach($clients as $client)
                          <option value="{{$client->id}}" data-name="{{$client->clientname}}" data-owner="{{$client->owner}}" data-account="{{$client->account}}">{{$client->clientname}}</option>
                        @endforeach
                      </optgroup>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label for="InputStartDate">{{ __("Start Date")}}:</label>
                    <input type="date" class="form-control" id="InputStartDate" name="start_date">
                  </div>
                  <div class="col-md-3">
                    <label for="InputEndDate">{{ __("End Date")}}:</label>
                    <input type="date" class="form-control" id="InputEndDate" name="end_date">
                  </div>
                  <div class="col-md-3">
                    <input class="btn btn-primary mt-4" type="submit" value="{{ __("Search")}}">
                  </div>
                </div>
              </form>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table class="table table-bordered" id="mytable">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Codeno</th>
                      <th>Status</th>
                      <th>Customer Name</th>
                      <th>Address</th>
                      <th>Delivery Date</th>
                      <th>COD Total</th>
                      <th>Delivery Charges</th>
                      <th>Bus Gate / Other Fees</th>
                      <th>Remittance Value</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection

@section('script')
<script type="text/javascript">
  $(document).ready(function(){

    function thousands_separators(num){
      var num_parts = num.toString().split(".");
      num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      return num_parts.join(".");
    }

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $('.myform').on('submit',function (e) {
      e.preventDefault();
      let client_id = $('#InputClient').val();
      let inputStartDate = $('#InputStartDate').val();
      let inputEndDate = $('#InputEndDate').val();
      showmytable(client_id, inputStartDate, inputEndDate);
    })

    function showmytable(client_id,inputStartDate,inputEndDate) {
      var url="{{route('getsuccessways')}}";
      $('#mytable').dataTable({
        "pageLength": 100,
        "bPaginate": true,
        "bLengthChange": true,
        "bFilter": true,
        "bSort": true,
        "bInfo": true,
        "bAutoWidth": true,
        "bStateSave": true,
        "aoColumnDefs": [
        { 'bSortable': false, 'aTargets': [ -1,0] }
        ],
        "bserverSide": true,
        "bprocessing":true,
        "ajax": {
          data: {client_id:client_id, inputStartDate:inputStartDate, inputEndDate:inputEndDate},
          url: url,
          type: "POST",
          dataType:'json',
        },
        "columns": [
          {"data":'DT_RowIndex'},
          {"data": "item.codeno"},
          {"data": null,
            render:function (data) {
              return 'completed'
            }
          },
          {"data": "item.receiver_name"},
          {"data": "item.receiver_address"},
          {"data": "delivery_date"},
          {"data": "item.deposit"},
          {"data": "item.delivery_fees"},
          {"data": "item.delivery_fees"},
          {"data": "item.delivery_fees"},
          // {
          //   "data":"item.assign_date",
          //   render:function(data){
          //     var date=new Date(data);
          //     date =date.toLocaleDateString(undefined, {year:'numeric'})+ '-' +date.toLocaleDateString(undefined, {month:'numeric'})+ '-' +date.toLocaleDateString(undefined, {day:'2-digit'})
          //      return date;
          //   }
          // },
          // {
          //   "data":"item.pickup.schedule.client.user.name"
          // },
          // {"data":"item.receiver_name"},
          // {
          //   "data":"item.township.name"
          // },
          // {
          //   "data":"delivery_man.user.name"
          // },
          // {
          //   "data":"item.deposit",
          //   render:function(data){
          //     return `${thousands_separators(data)}`
          //   }
          // },
          // {
          //   "data":"item.delivery_fees",
          //   render:function(data){
          //     return `${thousands_separators(data)}`
          //   }
          // },
          // {
          //   "data":null,
          //    render:function(data, type, full, meta){
          //     var wayediturl="{{route('items.edit',":id")}}"
          //     wayediturl=wayediturl.replace(':id',data.item.id);
          //     var waydeleteurl="{{route('deletewayassign',":id")}}"
          //     waydeleteurl=waydeleteurl.replace(':id',data.item.id);
          //     return`<a href="#" class="btn btn-sm btn-primary detail" data-id="${data.item.id}">{{ __("Detail")}}</a>
          //     <a href="${wayediturl}" class="btn btn-sm btn-warning">{{ __("Edit")}}</a>
          //     <a href="${waydeleteurl}" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">{{ __("Delete")}}</a>`
          //    }
          // }
        ],
        "info":false
      });
    }
  })
</script>
@endsection
