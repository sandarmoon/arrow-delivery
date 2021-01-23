@extends('main')
@section('content')
  <main class="app-content">
    <div class="app-title">
      <div>
        <h1><i class="fa fa-dashboard"></i> {{ __("Pickup Detail")}}</h1>
        <!-- <p>A free and open source Bootstrap 4 admin template</p> -->
      </div>
      <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="{{route('pickup_history')}}">{{ __("Pickup Hsitory")}}</a></li>
      </ul>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="tile">
          @php $mytime = Carbon\Carbon::now(); @endphp
            <div class="table-responsive">
            <table class="table table-bordered dataTable">
              <thead>
                <tr>
                  <th>{{ __("#")}}</th>
                  <th>{{ __("Codeno")}}</th>
                  <th>{{ __("Township")}}</th>
                  <th>{{ __("Receiver Name")}}</th>
                  <th>{{ __("Receiver Phone No")}}</th>
                  <th>{{ __("Way State")}}</th>
                  <th>{{ __("Deli Fees")}}</th>
                  <th>{{ __("Bus Gate / Other Fees")}}</th>
                  <th>{{ __("Item Price")}}</th>
                </tr>
              </thead>
              <tbody>
                @php $i=1;$total=0;$sub=0; $allpaid_color=""; @endphp
                @foreach($items as $row)
                @php 
                  $carry_fees = 0;
                  $total+=$row->deposit;
                @endphp
                @if(($row->paystatus == 2 || $row->paystatus == 4) && ($row->status==0))
                  @php $sub += ($row->delivery_fees+$row->other_fees); @endphp
                @endif

                @if(isset($row->expense))
                  @php 
                    $carry_fees = $row->expense->amount; 
                    $sub += $carry_fees;
                  @endphp
                @endif

                @php $prepaidtotal=0; @endphp
                @if(count($row->pickup->expenses)>0)
                  @php 
                    foreach ($row->pickup->expenses as $expense) {
                      $prepaidtotal += $expense->amount;
                    }
                  @endphp
                @endif
                <tr>
                  <td class="align-middle">{{$i++}}</td>
                  <td class="align-middle">
                    <span class="d-block">{{$row->codeno}}</span>
                    @if(($row->paystatus == 2 || $row->paystatus == 4) && ($row->status == 0))
                      @php $allpaid_color="text-danger"; @endphp
                      @if($row->paystatus == 2)
                        <span class="badge badge-info">allpaid</span>
                      @else
                        <span class="badge badge-info">only item price</span>
                      @endif
                    @elseif($row->paystatus == 3)
                      @php $allpaid_color=""; @endphp
                      <span class="badge badge-info">only deli</span>
                    @else
                      @php $allpaid_color=""; @endphp
                    @endif
                  </td>
                  <td class="align-middle">
                    @if($row->township)
                    {{$row->township->name}}
                    @elseif($row->SenderGate)
                    {{$row->SenderGate->name}}
                    @elseif($row->SenderPostoffice)
                    {{$row->SenderPostoffice->name}}
                    @endif
                  </td>
                  <td class="align-middle">{{$row->receiver_name}}</td>
                  <td class="align-middle">{{$row->receiver_phone_no}}</td>
                  <td class="align-middle">
                    @if($row->way==null)
                    <span class="badge badge-primary">
                      delay
                    </span>
                    @elseif($row->way->status_code=='001')
                    <span class="badge badge-info">
                      success
                    </span>
                    @elseif($row->way->status_code=='005')
                      <span class="badge badge-primary">
                      pending
                    </span>
                    @elseif($row->way->status_code=='003')
                      <span class="badge badge-danger">
                      reject
                    </span>
                    @else
                      <span class="badge badge-warning">
                      return
                    </span>
                    @endif

                  </td>
                  <td class="{{$allpaid_color}} align-middle">{{number_format($row->delivery_fees)}}</td>
                  <td class="{{$allpaid_color}} align-middle">
                    {{number_format($row->other_fees+$carry_fees)}}
                  </td>
                  <td class="align-middle">{{number_format($row->deposit)}}</td>
                </tr>
                @endforeach

              </tbody>
              <tfoot>
                <tr>
                  <td colspan="7">Total Amount</td>
                  <td colspan="2">{{number_format($total-$sub)}} Ks</td>
                </tr>
                <tr>
                  <td colspan="7">Prepaid Amount</td>
                  <td colspan="2">{{number_format($prepaidtotal)}} Ks</td>
                </tr>
                <tr>
                  <td colspan="7">Balance</td>
                  <td colspan="2">{{number_format($total-$sub-$prepaidtotal)}} Ks</td>
                </tr>
              </tfoot>
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
  })
 
</script>
@endsection
