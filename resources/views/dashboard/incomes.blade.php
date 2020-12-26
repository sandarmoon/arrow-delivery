@extends('main')
@section('content')
  <main class="app-content">
    <div class="app-title">
      <div>
        <h1><i class="fa fa-dashboard"></i> {{ __("Reports")}}</h1>
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
          <h3 class="tile-title d-inline-block">{{ __("Incomes List")}} ({{$mytime->toFormattedDateString()}})</h3>
          <a href="{{route('incomes.create')}}" class="btn btn-sm btn-primary float-right">{{ __("Add Income")}}</a>
          <div class="table-responsive">
            <table class="table dataTable">
              <thead>
                <tr>
                  <th>#</th>
                  <th>{{ __("Item Code")}}</th>
                  <th>{{ __("Delivery Men")}}</th>
                  <th>{{ __("Payment Type")}}</th>
                  <th>{{ __("Cash Amount")}}</th>
                  <th>{{ __("Bank Amount")}}</th>
                </tr>
              </thead>
              <tbody>
                @php $i=1; $ctotal=$btotal=0; @endphp
                @foreach($incomes as $row)
                @php 
                  $ctotal+=$row->cash_amount;
                  $btotal+=$row->bank_amount;
                @endphp
                <tr>
                  <td>{{$i++}}</td>
                  <td><span class="badge badge-primary">{{$row->way->item->codeno}}</span></td>
                  <td>{{$row->way->delivery_man->user->name}}</td>
                  <td>{{$row->payment_type->name}}</td>
                  <td>{{number_format($row->cash_amount)}}</td>
                  <td>{{number_format($row->bank_amount)}}</td>
                </tr>
                @endforeach
                <tr>
                  <td colspan="4">{{ __("Total Amount")}}:</td>
                  <td>{{number_format($ctotal)}}</td>
                  <td>{{number_format($btotal)}}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection 