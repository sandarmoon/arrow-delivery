@extends('main')
@section('content')
  <main class="app-content">
    <div class="app-title">
      <div>
        <h1><i class="fa fa-dashboard"></i> {{ __("Expenses")}}</h1>
        <!-- <p>A free and open source Bootstrap 4 admin template</p> -->
      </div>
      <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="{{route('expenses.index')}}">{{ __("Expenses")}}</a></li>
      </ul>
    </div>
    <div class="row">
      <div class="col-md-12">
         @if(session('successMsg') != NULL)
          <div class="alert alert-success alert-dismissible fade show myalert" role="alert">
              <strong> ✅ SUCCESS!</strong>
              {{ session('successMsg') }}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
        @endif
        <div class="tile">
          <h3 class="tile-title d-inline-block">{{ __("Expense List")}}</h3>
          <a href="{{route('expenses.create')}}" class="btn btn-sm btn-primary float-right"><i class="fa fa-plus" aria-hidden="true"></i> {{ __("Add New")}}</a>

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
              <label for="InputType">{{ __("Select Expense Type")}}:</label>
                <select class="js-example-basic-single" id="InputType" name="type">
                  <option value="">Choose Expensetype</option>
                    @foreach($expensetypes as $row)
                      <option value="{{$row->id}}" data-name="{{$row->name}}">{{$row->name}}</option>
                    @endforeach
                </select>
            </div>
            @endrole
            <div class="form-group col-md-3">
              <button class="btn btn-primary search_btn mt-4" type="button">{{ __("Search")}}</button>
            </div>
        </div>
          <div class="table-responsive">
            <table class="table table-bordered" id="expensetable">
              <thead>
                <tr>
                  <th>{{ __("#")}}</th>
                  <th>{{ __("Date")}}</th>
                  <th>{{ __("Amount")}}</th>
                  <th>{{ __("Type")}}</th>
                  <th>{{ __("Description")}}</th>
                  <th>{{ __("Actions")}}</th>
                </tr>
              </thead>
              <tbody>
                 @php $i=1; @endphp
                 @foreach($expenses as $row)
                <tr>
                  <td class="align-middle">{{$i++}}</td>
                  <td class="align-middle">
                    @if($row->expense_date == null)
                    {{Carbon\Carbon::parse($row->created_at)->format('d-m-Y')}}
                    @else
                    {{Carbon\Carbon::parse($row->expense_date)->format('d-m-Y')}}
                    @endif
                  </td>
                  <td class="align-middle">{{number_format($row->amount)}} Ks</td>
                  <td class="align-middle">{{$row->expense_type->name}}</td>
                  <td class="align-middle">{{$row->description}}</td>
                  <td class="align-middle">
                    {{-- @if($row->expense_type_id!=1) --}}
                    <a href="{{route('expenses.edit',$row->id)}}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('expenses.destroy',$row->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                    {{-- @endif --}}
                  </td>
                </tr>
                @endforeach
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
    //alert("ok");
    setTimeout(function(){ $('.myalert').hide(); showDiv2() },3000);
    $('.js-example-basic-single').select2({width:'100%'});

    $('#expensetable').dataTable({
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
        ]
    });

    $('.search_btn').click(function () {
      var sdate = $('#InputStartDate').val();
      var edate = $('#InputEndDate').val();
      var type_id=$("#InputType").val();

      $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      
      var url="{{route('expensebytype')}}";
      var i=1;
      $('#expensetable').DataTable({
        "processing": true,
        "serverSide": true,
        destroy:true,
        "sort":false,
        "stateSave": true,
        "ajax": {
            url: url,
            type: "POST",
            data:{sdate:sdate,edate:edate,type_id:type_id},
            dataType:'json',
        },
        "columns": [
          {"data":'DT_RowIndex'},
          {"data": null,
            sortable:false,
            render:function(data){
              if (data.expense_date == null) {
                return `${formatDate(data.created_at)}`;
              }else{
                return `${formatDate(data.expense_date)}`;
              }
            }
          },
          { "data": "amount",
            render:function (data) {
              return `${thousands_separators(data)}`
            }
          },
          { "data": "expense_type.name" },
          { "data": "description"},
          { "data": null,
            sortable:false,
            render:function(data){
              if (data.expense_type_id != 1) {
                var routeurl="{{route('expenses.edit',':id')}}";
                routeurl=routeurl.replace(':id',data.id);
                return `<a class="btn btn-warning btn-sm" href="${routeurl}" data-id="${data.id}">Edit</a>`;
              }else{
                return ''
              }
            }
          }
        ],
       "info":false
      });    
    })

    function thousands_separators(num){
      var num_parts = num.toString().split(".");
      num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      return num_parts.join(".");
    }

    // Y/M/D into D/M/Y
    function formatDate (input) {
      var datePart = input.match(/\d+/g),
      year = datePart[0].substring(0,4), // get only two digits
      month = datePart[1], day = datePart[2];
      return day+'-'+month+'-'+year;
    }
  })
  
</script>
@endsection