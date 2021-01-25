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
              <div class="tab-pane fade @role('staff'){{'active show'}}@endrole" id="assigned">
                <div class="table-responsive">
                  <table class="table table-bordered dataTable">
                    <thead>
                      <tr>
                        <th>{{ __("#")}}</th>
                        @role('staff')<th>{{ __("Client Name")}}</th>@endrole
                        <th>{{ __("Pickup Date")}}</th>
                        <th>{{ __("Remark")}}</th>
                        <th>{{ __("Delivery Man")}}</th>
                        <th>{{ __("Quantity")}}</th>
                        <th>{{ __("Amount")}}</th>
                        {{-- <th>{{ __("Total Item Price")}}</th> --}}
                        <th>{{ __("Prepaid Amount")}}</th>
                        <th>{{ __("Actions")}}</th>
                      </tr>
                    </thead>
                    <tbody class="assigntbody">
                      @php $i=1; @endphp
                      @foreach($pickups as $row)
                      @if($row->items)
                        @php 
                          $allpaid_delivery_fees=$notallpaid_deposit= 0; 
                          foreach ($row->items as $item) {
                            if ($item->paystatus == "2" || $item->paystatus == "4") {
                              $allpaid_delivery_fees += ($item->delivery_fees+$item->other_fees);
                            }

                            if ($item->paystatus != "2") {
                              $notallpaid_deposit += $item->deposit;
                            }
                          }

                          $depositamount = $notallpaid_deposit-$allpaid_delivery_fees;
                        @endphp
                      @endif
                      <tr>
                        <td class="align-middle">{{$i++}}</td>
                        @role('staff')<td class="text-danger align-middle">{{$row->schedule->client->user->name}}</td>@endrole
                        <td class="align-middle">{{\Carbon\Carbon::parse($row->schedule->pickup_date)->format('d-m-Y')}}</td>
                        <td class="align-middle">{{$row->schedule->remark}}</td>
                        <td class="text-danger align-middle">{{$row->delivery_man->user->name}}
                          @foreach($data as $dd)
                            @if($dd->id==$row->id)
                            <span class="badge badge-info seen">seen</span>
                            @endif

                           @endforeach
                        </td>
                        <td class="align-middle">{{$row->schedule->quantity}} / {{count($row->items)}}</td>
                        <td class="align-middle">
                          @if(count($row->items)>0)
                          <strike>{{number_format($row->schedule->amount)}}</strike>
                          @else
                            {{number_format($row->schedule->amount)}}
                          @endif
                           / {{number_format($depositamount)}}</td>
                        {{-- @php
                          $total_item_price = $row->item->sum('deposit');
                        @endphp --}}
                        {{-- <td class="align-middle">{{$total_item_price}}</td> --}}
                        <td class="align-middle">
                          @if($row->expense)
                            {{number_format($row->expense->amount)}}
                          @else
                            {{'-'}}
                          @endif
                        </td>
                        <td class="align-middle">
                          @if($row->status==1 && $row->schedule->quantity != count($row->items))
                            @role('staff')
                              <a href="{{route('items.collect',['cid'=>$row->schedule->client->id,'pid'=>$row->id])}}" class="btn btn-sm btn-primary">{{ __("Collect")}}</a>
                            @endrole
                            @role('client')
                              <button type="button" class="btn btn-sm btn-info">{{ __("Brought")}}</button>
                            @endrole
                          @elseif($row->status == 4 && $row->schedule->quantity == count(($row->items)))
                            <button type="button" class="btn btn-sm btn-info">{{ __("completed")}}</button>
                            @if($row->expense)
                              <button type="button" class="btn btn-sm btn-warning editprepaid" data-id="{{$row->id}}" data-amount="{{$row->expense->amount}}" data-client_id="{{$row->schedule->client_id}}">Edit Prepaid Amount</button>
                            @else
                              <button type="button" class="btn btn-sm btn-success editprepaid" data-id="{{$row->id}}" data-amount="0" data-client_id="{{$row->schedule->client_id}}">Add Prepaid Amount</button>
                            @endif
                          @elseif($row->status==2)
                            <a href="{{route('checkitem',$row->id)}}" class="btn btn-sm btn-danger">{{ __("fail")}}</a>
                          @elseif($row->status==3)
                            <a href="#" class="btn btn-sm btn-secondary addamount" data-id="{{$row->schedule->id}}">{{ __("Add amount and qty")}}</a>
                          @elseif($row->status == 0)
                            <button type="button" class="btn btn-sm btn-danger">{{ __("pending")}}</button>
                          @endif

                          @if($row->status != 4 || $row->schedule->quantity != count(($row->items)))
                            @if($row->status != 5)
                            <a href="{{route('schedules.edit',$row->schedule->id)}}" class="btn btn-sm btn-warning">{{ __("Edit")}}</a>
                            @endif
                            
                            @if(count($row->items) == 0)
                            <form action="{{ route('schedules.destroy',$row->schedule->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure?')">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-sm btn-danger">{{ __("Delete")}}</button>
                            </form>
                            @endif
                          @endif

                          @if($row->status == 5)
                            <button type="button" class="btn btn-sm btn-success">Cleared</button>
                          @endif
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
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
      $.ajaxSetup({
         headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

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

      $(".assigntbody").on('click','.addamount',function(e){
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

      $(".assigntbody").on('click','.editprepaid',function(e){
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
    })
  </script>
@endsection