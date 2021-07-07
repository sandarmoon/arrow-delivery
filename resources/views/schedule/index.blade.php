@extends('main')
@section('content')
  <main class="app-content">
    <div class="app-title">
      <div>
        <h1><i class="fa fa-dashboard"></i> {{ __("Schedules")}}</h1>
        <!-- <p>A free and open source Bootstrap 4 admin template</p> -->
      </div>
      <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="{{route('schedules.index')}}">{{ __("Schedules")}}</a></li>
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
          <h3 class="tile-title d-inline-block">{{ __("Pickup List")}}</h3>
          <a href="{{route('schedules.create')}}" class="btn btn-sm btn-primary float-right"><i class="fa fa-plus" aria-hidden="true"></i> {{ __("Add New")}}</a>


          <div class="bs-component">
            @role('staff')
            <ul class="nav nav-tabs">
              <li class="nav-item"><a class="nav-link @role('client'){{'active'}}@endrole" data-toggle="tab" href="#schedules">{{ __("Schedules")}}</a></li>
              <li class="nav-item"><a class="nav-link @role('staff'){{'active'}}@endrole" data-toggle="tab" href="#assigned">{{ __("Assigned")}}</a></li>
            </ul>
            @endrole
            <div class="tab-content mt-3" id="myTabContent">
              <div class="tab-pane fade @role('client'){{'active show'}}@endrole" id="schedules">
                <div class="table-responsive">
                  <table class="table table-bordered dataTable">
                    <thead>
                      <tr>
                        <th>{{ __("#")}}</th>
                        @role('staff')<th>{{ __("Client Name")}}</th>@endrole
                        <th>{{ __("Pickup Date")}}</th>
                        <th>{{ __("Remark")}}</th>
                        <th>{{ __("Quantity")}}</th>
                        <th>{{ __("Actions")}}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @php $i=1; @endphp
                      @foreach($schedules as $row)
                      <tr>
                        <td class="align-middle">{{$i++}}</td>
                        @role('staff')
                          <td class="text-danger">{{$row->client->user->name}}</td>
                        @endrole
                        <td class="align-middle">{{\Carbon\Carbon::parse($row->pickup_date)->format('d-m-Y')}}</td>
                        <td class="align-middle">{{$row->remark}}</td>
                        <td class="align-middle">{{$row->quantity}}</td>
                        <td class="align-middle">
                          @role('staff')
                            <a href="#" class="btn btn-sm btn-primary assign" data-id="{{$row->id}}">{{ __("Assign")}}</a>
                            <a href="#" class="btn btn-sm btn-info showfile" data-file="{{$row->file}}">{{ __("show file")}}</a>
                          @endrole
                          @role('client')
                            @if($row->status == 0)
                            <a href="{{route('schedules.edit',$row->id)}}" class="btn btn-sm btn-warning">{{ __("Edit")}}</a>
                            @else
                            <button class="btn btn-sm btn-info">{{ __("Complete")}}</button>
                            @endif
                          @endrole
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>

              {{-- Assign table datatabljs --}}
              <div class="tab-pane fade @role('staff'){{'active show'}}@endrole" id="assigned" >
                  
                  {{-- filter start here --}}
                  <div class="row">
                    <div class="form-group col-md-3">
                      <label for="InputStartDate">{{ __("Start Date")}}:</label>
                      <input type="date" class="form-control" id="InputStartDate" name="start_date">
                    </div>
                    <div class="form-group col-md-3">
                      <label for="InputEndDate">{{ __("End Date")}}:</label>
                      <input type="date" class="form-control" id="InputEndDate" name="end_date">
                    </div>

                    <div class="form-group col-md-3">
                      <button class="btn btn-primary search_btn mt-4" type="button">{{ __("Search")}}</button>
                    </div>
                  </div>
                  {{-- filter end here --}}


                <div class="table-responsive">
                  <table class="table table-bordered " style="width: 100%;" id="assign-table">
                    <thead>
                      <tr>
                        <th>{{ __("#")}}</th>
                        @role('staff')<th>{{ __("Client Name")}}</th>@endrole
                        <th>{{ __("Pickup Date")}}</th>
                        <th>{{ __("Remark")}}</th>
                        <th>{{ __("Delivery Man")}}</th>
                        <th>{{ __("Quantity")}}</th>
                        <th>{{ __("InStock")}}</th>
                        <th>{{ __("Estimation")}}</th>
                        <th>{{ __("Amount")}}</th>
                        {{-- <th>{{ __("Total Item Price")}}</th> --}}
                        <th>{{ __("Prepaid ")}}</th>
                        <th>{{ __("Actions")}}</th>
                      </tr>
                    </thead>
                    <tbody>
                      
                    </tbody>
                    <tfoot>
                      <td></td>
                     @role('staff') <td></td>@endrole
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tfoot>

                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  {{-- Assign modal --}}
  <div class="modal fade" id="assignModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{ __("Assign Delivery Man")}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="{{route('schedules.storeandassign')}}" method="POST" enctype="multipart/form-data">
            @csrf
          <input type="hidden" name="assignid" id="assignid" value="">
          <select class="form-control" name="deliveryman">
            <optgroup label="Choose Delivery Man">
              <option>{{ __("Choose Delivery Man")}}</option>
              @foreach($deliverymen as $row)
              <option value="{{$row->id}}">{{$row->user->name}}</option>
              @endforeach
            </optgroup>
          </select>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __("Close")}}</button>
          <button type="submit" class="btn btn-primary">{{ __("Assign")}}</button>
        </form>
        </div>
      </div>
    </div>
  </div>

  {{-- addfile modal --}}
  <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{ __("Add File")}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="{{route('uploadfile')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="addid" id="addid" value="">
            <input type="hidden" name="oldfile" id="oldfile">

            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item" role="presentation">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">{{ __("New file")}}</a>
              </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">{{ __("Old file")}}</a>
              </li>
            </ul>
            <div class="tab-content mt-3" id="myTabContent">
              <div class="tab-pane fade show active " id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="form-group">
                  <input type="file"  id="addfile" name="addfile">
                 </div>
              </div>
              <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <img src="" class="myoldfile img-fluid">
              </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">{{ __("Save")}}</button>
        </form>
        </div>
      </div>
    </div>
  </div>

  {{-- show file modal --}}
  <div class="modal fade" id="filedisplay" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{ __("File")}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <img src="" class="img-fluid stafffile" width="100%" height="100%">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __("Close")}}</button>
        </div>
      </div>
    </div>
  </div>

  {{--Add amount modal--}}
  <div class="modal fade" id="addamount" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{ __("Add Amount and Quantity")}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="schedule" id="schedule_id" value="">
          <div class="form-group quantity">
            <label for="quantity">{{ __("Quantity")}}:</label>
            <input type="number"  id="quantity" class="form-control" name="quantity">
            <span class="Eamount error d-block" ></span>
          </div>
          <div class="form-group amount">
            <label for="amount">{{ __("Amount")}}:</label>
            <input type="number"  id="amount" class="form-control" name="amount">
            <span class="Equantity error d-block" ></span>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary amountsave">{{ __("Save")}}</button>
        </div>
      </div>
    </div>
  </div>

  {{--Add amount modal--}}
  <div class="modal fade" id="editprepaid" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{ __("Edit Prepaid Amount!")}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="pickup" id="pickup_id" value="">
          <div class="form-group prepaidamount">
            <label for="prepaidamount">{{ __("Amount")}}:</label>
            <input type="number" id="prepaidamount" class="form-control" name="prepaidamount">
            <input type="hidden" id="client_id" class="form-control" name="client_id">
            <span class="Equantity error d-block" ></span>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary prepaidamountsave">{{ __("Save")}}</button>
        </div>
      </div>
    </div>
  </div>

@endsection 
@section('script')
  <script type="text/javascript">
    $(document).ready(function () {
      // for loading assign date for today  start

      // for loading assign date for today  end

      $.ajaxSetup({
         headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      getData('','');
       

      $('.assign').click(function () {
        $('#assignModal').modal('show');
        var id=$(this).data(id);
        $("#assignid").val(id.id);
      })

      $('.addfile').click(function () {
        $('#addModal').modal('show');
        var id=$(this).data(id);
        var file=$(this).data(file);
        console.log(file.file);
        //console.log(id.id);
        $("#addid").val(id.id);
        $("#oldfile").val(file.file);
        $(".myoldfile").attr("src",file.file)
      })

      $(".showfile").click(function(){
        $('#filedisplay').modal('show');
        var file=$(this).data("file");
        //console.log(file);
        $(".stafffile").attr("src",file);
      })

      $("#assign-table").on('click','.addamount',function(e){
        e.preventDefault();
        $('#addamount').modal('show');
        var id=$(this).data('id');
        $("#schedule_id").val(id);
      })

      $(".amountsave").click(function(){
        var schedule_id=$("#schedule_id").val();
        var amount=$("#amount").val();
        var quantity=$("#quantity").val();
        var url="{{route('editamountandqty')}}";
          
        $.ajax({
          url:url,
          type:"post",
          data:{schedule_id:schedule_id,amount:amount,quantity:quantity},
          dataType:'json',
          success:function(response){
            if(response.success){
               $('#addamount').modal('hide');
               $('.Eamount').text('');
               $('.quantity').text('');
              $('span.error').removeClass('text-danger');
              location.href="{{route('schedules.index')}}";
            }
          },
          error:function(error){
            var message=error.responseJSON.message;
            var errors=error.responseJSON.errors;
            console.log(error.responseJSON.errors);
            if(errors){
              var amount=errors.amount;
              var quantity=errors.quantity;
              $('.Eamount').text(amount);
              $('.Equantity').text(quantity);
              $('span.error').addClass('text-danger');
            }
          }
        })

      })

      setTimeout(function(){ $('.myalert').hide(); showDiv2() },3000);

      $("#assign-table").on('click','.editprepaid',function(e){
        e.preventDefault();
        $('#editprepaid').modal('show');
        var id=$(this).data('id');
        var amount = $(this).data('amount');
        var client_id = $(this).data('client_id');
        $("#pickup_id").val(id);
        $("#prepaidamount").val(amount);
        $("#client_id").val(client_id);
      })

      $(".prepaidamountsave").click(function(){
        var pickup_id=$("#pickup_id").val();
        var prepaidamount=$("#prepaidamount").val();
        var client_id=$("#client_id").val();
        // var bank_id=$("#bank").val();
        var url="{{route('editprepaidamount')}}";
          
        $.ajax({
          url:url,
          type:"post",
          data:{pickup_id:pickup_id,prepaidamount:prepaidamount},
          dataType:'json',
          success:function(response){
            if(response.success){
               $('#prepaidamount').modal('hide');
               $('.Eamount').text('');
              $('span.error').removeClass('text-danger');
              location.href="{{route('schedules.index')}}";
            }
          },
          error:function(error){
            var message=error.responseJSON.message;
            var errors=error.responseJSON.errors;
            console.log(error.responseJSON.errors);
            if(errors){
              var amount=errors.amount;
              $('.Eamount').text(amount);
              $('span.error').addClass('text-danger');
            }
          }
        })
      })

      $('.search_btn').click(function(){
        var sdate = $('#InputStartDate').val();
        var edate = $('#InputEndDate').val();
        getData(sdate,edate);
      })
    })

    function getData(sdate,edate){
      let url="{{route('assignList')}}";
      $('#assign-table').DataTable({
        "processing": true,
        "serverSide": true,
        "destroy":true,
        "ajax": {
            url: url,
            type: "POST",
            data:{sdate:sdate,edate:edate},
            dataType:'json',
        },
        "columns":[
          {"data":'DT_RowIndex'},
          {"data":"schedule.client.user.name"},
          {"data":"schedule.pickup_date",
            render:function(data){
              return formatDate(data);
            }
          },
          {"data":"schedule.remark"},
          {"data":"delivery_man.user.name"},
          {"data":function(data){
            
             let html='';
             html=`${data.schedule.quantity}`;
             return html;
          }},
          {"data":function(data){
            
             let html='';
             html=`${data.items.length}`;
             return html;
          }},
          {"data":"schedule.amount",
            render:function(data) {
              return thousands_separators(data)
            }
          },
          {"data":function(data){
            let item=data.items;
            let html='';
            let allpaid_delivery=0;
            let notallpaid_deposit=0;
            let depositamount=0;

            if(item.length >0){
              $.each(item,function(i,v){
                  if(v.paystatus == '2' && v.paystatus =='4'){
                    allpaid_delivery=Number(v.delivery_fees)+Number(v.deposit);
                  }

                  if (v.paystatus != "2") {
                    notallpaid_deposit += v.deposit;
                  }
              })
              depositamount= notallpaid_deposit-allpaid_delivery;
            }

            // if(item.length >0){
            //   html=`<strike>${data.schedule.amount}</strike>`
            // }else{
            //   html=data.schedule.amount
            // }
            html=`${thousands_separators(depositamount)}`

            return html;
          }},
          {"data":function(data){
            let expenses=data.expenses;
            let total_expense=0;
            if(expenses.length >0){
              $.each(expenses,function(i,v){
                total_expense+=Number(v.amount);
              })
            }
            return thousands_separators(total_expense);
          }},

          {"data":function(data){
            let expenses=data.expenses;
            let html='';
            let url="items/collectitem/"+data.schedule.client.id+'/'+data.id;

            let url2="{{route('goDailyfixprint',':id')}}";
            url2=url2.replace(':id', data.id);

            let url3="{{route('checkitem',':id')}}";
            url3=url3.replace(':id', data.id);

            let url4="{{route('schedules.edit',':id')}}";
            url4=url4.replace(':id', data.schedule.id);

            let url5="{{ route('schedules.destroy',':id') }}";
            url5=url5.replace(':id',data.schedule.id);

            let total_expense=0;
            // let data=['cid'=>$row->schedule->client->id,'pid'=>$row->id];

            //start here
             if(data.status==1 && data.schedule.quantity != data.items.length){

              html=`@role('staff')
                <a href="${url}" class="btn btn-sm btn-primary">{{ __("Collect")}}</a>
              @endrole
              @role('client')
                <button type="button" class="d-inline btn btn-sm btn-info">{{ __("Brought")}}</button>
              @endrole`




             }else if(data.status == 4 && data.schedule.quantity == (data.items).length){

                 html+=`<button type="button" class=" d-inline btn btn-sm btn-info">{{ __("completed")}}</button>
               <a type="button" class="mx-1 d-inline-block btn btn-sm btn-secondary" href="${url2}">{{ __("Print")}}</a>`

                if(expenses.length >0){
                    $.each(expenses,function(i,v){
                      total_expense+=Number(v.amount);
                    })
                  html+=`<button type="button" class="d-inline-block btn btn-sm btn-warning editprepaid" data-id="${data.id}" data-amount="${total_expense}" data-client_id="${data.schedule.client_id}">Edit Prepaid Amount</button>`
                }
                else{
                  html+=`<button type="button" class="d-inline btn btn-sm btn-success editprepaid" data-id="${data.id}" data-amount="0" data-client_id="${data.schedule.client_id}">Add Prepaid Amount</button> `
                }
                




             }else if(data.status==2){

              html+=`<a href="${url3}" class="btn btn-sm btn-danger">{{ __("fail")}}</a>`

             }else if(data.status==3){

              html+= `<a href="#" class="btn btn-sm btn-secondary addamount" data-id="${data.schedule_id}">{{ __("Add amount and qty")}}</a>`

             }else{
              html+=  `<button type="button" class="btn btn-sm btn-danger">{{ __("pending")}}</button>`;
             }
             //end here

             // start again here
             if(data.status != 4 || data.schedule.quantity != (data.items).length){

              html+=`<a href="${url4}" class="mx-1 btn btn-sm btn-warning">{{ __("Edit")}}</a>`

              if((data.items).length == 0){
               html+= `<form action="${url5}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="mx-1 btn btn-sm btn-danger">{{ __("Delete")}}</button>
                </form>`
              }
                

             }
             // end again here

             return html;


          }}
         

        ],

        "footerCallback":function(row,data,start,end,display){
           var api = this.api(), data;
           // var currentPosition = api.colReorder.transpose( 6 );
           console.log(data);
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };

            // for qty
             // Total over all pages
            totalqty = api
                .column( 5 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotalqty = api
                .column( 5, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            $( api.column( 5 ).footer() ).html(
                // pageTotalqty +' ('+ totalqty +' total)'
                pageTotalqty 
            );


            // for instock
             totalqty = api
                .column( 6 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotalqty = api
                .column( 6, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            $( api.column( 6 ).footer() ).html(
                pageTotalqty 
            );

             // for estimation
             totalqty = api
                .column( 7 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotalqty = api
                .column( 7, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            $( api.column( 7 ).footer() ).html(
                thousands_separators(pageTotalqty) 
            );

             // for amount
             totalqty = api
                .column( 8 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotalqty = api
                .column( 8, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            $( api.column( 8 ).footer() ).html(
                thousands_separators(pageTotalqty)
            );
            // for Prepaid
             totalqty = api
                .column( 9 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotalqty = api
                .column( 9, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            $( api.column( 9 ).footer() ).html(
                thousands_separators(pageTotalqty) 
            );
        }
      })
    }

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