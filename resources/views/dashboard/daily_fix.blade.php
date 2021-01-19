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
                        <option value="0">Choose Client</option>
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
                  <tbody>
                    
                  </tbody>
                  <tfoot>
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                  </tfoot>
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
      // alert('hi')
      var url="{{route('getsuccessways')}}";
      $('#mytable').dataTable({
        "destroy": true,
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
          {"data": "item.receiver_name",
            render:function (data) {
              return `${data}`
            }
          },
          {"data": "item.receiver_address",
            render:function (data) {
              return `${data}`
            }
          },
          {"data": "delivery_date",
            render:function (data) {
              return `${formatDate(data)}`
            }
          },
          {"data": "item",
            render:function (data) {
              if (data.paystatus == 1) {
                return thousands_separators(Number(data.deposit)+Number(data.delivery_fees)+Number(data.other_fees))
              }else if(data.paystatus == 2){
                return 0;
              }else if(data.paystatus == 3){
                return thousands_separators(Number(data.delivery_fees)+Number(data.other_fees))
              }else if(data.paystatus == 4){
                return thousands_separators(Number(data.deposit))
              }
            }
          },
          {"data": "item.delivery_fees",
            render:function (data) {
              return `${thousands_separators(data)}`
            }
          },
          {"data": "item",
            render:function (data) {
              console.log(data)
              if (data.expense!=null) {
                var bus_gate_fees = Number(data.expense.amount)
                return `${thousands_separators(bus_gate_fees)} / ${thousands_separators(data.other_fees)}`
              }else{
                return `${thousands_separators(data.other_fees)}`
              }
            }
          },
          {"data": null,
            render:function (data) {
              return `${data}`
            }
          },
        ],
        footerCallback: function (row, data) {
          var table = $('#mytable').DataTable();
          var api = table,data;
          
          // Remove the formatting to get integer data for summation
          var intVal = function ( i ) {
              return typeof i === 'string' ?
                  i.replace(/[\$,]/g, '')*1 :
                  typeof i === 'number' ?
                      i : 0;
          };

          // Total over all pages
          if(data.length > 0){
              
            // var price = data[0].item.deposit;
            cod_total =  api
                .column(7 )
                .data()
                .reduce( function (a, b) {
                   return intVal(a) + intVal(b);
                    
                }, 0 );

            cod_pageTotal = api
                .column( 7, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b) ;
                    
                }, 0 );

            delivery_fee_total = api
                .column( 8 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            delivery_fee_pageTotal = api
                .column( 8, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            $( api.column( 7 ).footer() ).html(
                thousands_separators(cod_pageTotal) ,
            );

            $( api.column( 8 ).footer() ).html(
                thousands_separators( delivery_fee_pageTotal ),
            );

            }
        },
        "info":false
      });
    }

    function formatDate(input) {
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
  })
</script>
@endsection
