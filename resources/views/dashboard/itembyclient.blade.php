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
            <table class="table">
              <thead>
                <tr>
                        <th>{{ __("#")}}</th>
                        <th>{{ __("Codeno")}}</th>
                        <th>{{ __("Township")}}</th>
                        <th>{{__("Receiver Name")}}</th>
                        <th>{{__("Receiver Phone No")}}</th>
                        <th>{{ __("Way State")}}</th>
                        <th>{{ __("Amount")}}</th>
                      </tr>
              </thead>
              <tbody>
                @php $i=1;$total=0; @endphp
                @foreach($items as $row)
                @php $total+=$row->deposit @endphp
                <tr>
                  <td>{{$i++}}</td>
                  <td>{{$row->codeno}}</td>
                  <td>{{$row->township->name}}</td>
                  <td>{{$row->receiver_name}}</td>
                  <td>{{$row->receiver_phone_no}}</td>
                  <td>
                    @if($row->way==null)
                    <span class="badge badge-primary">
                      delay way
                    </span>
                    @elseif($row->way->status_code=='001')
                    <span class="badge badge-info">
                      success way
                    </span>
                    @elseif($row->way->status_code=='005')
                      <span class="badge badge-primary">
                      pending way
                    </span>
                    @elseif($row->way->status_code=='003')
                      <span class="badge badge-danger">
                      reject way
                    </span>
                    @else
                      <span class="badge badge-warning">
                      return way
                    </span>
                    @endif

                  </td>
                  <td>{{$row->deposit}}</td>
                </tr>
                @endforeach
                <tr>
                  <td colspan="6">Total Amount</td>
                  <td>{{$total}}</td>
                </tr>
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
  })
 
</script>
@endsection
