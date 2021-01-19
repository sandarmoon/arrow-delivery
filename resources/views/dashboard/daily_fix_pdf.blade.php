<!DOCTYPE html>
<html>
<head>
	<title></title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <style type="text/css">
    .mmfont{
      font-family: 'Myanmar3'
    }

    .table-warning{
      background-color: #ffeeba;
    }

    .d-block{
      display: block;
    }
  </style>
</head>
{!! Zawuni::includeFiles() !!}
<body>
	<table border="1" cellpadding="5" cellspacing="0" width="50%">
   <tbody>
     <tr>
       <td colspan="2" style="text-align: center;">Delivery Complete Date</td>
     </tr>
     <tr>
       <td>From</td>
       <td>{{Carbon\Carbon::parse($data['start_date'])->format('d-m-Y')}}</td>
     </tr>
     <tr>
       <td>To</td>
       <td>{{Carbon\Carbon::parse($data['end_date'])->format('d-m-Y')}}</td>
     </tr>
     <tr>
       <td>Invoice Date</td>
       <td>{{Carbon\Carbon::today()->format('d-m-Y')}}</td>
     </tr>
     <tr>
       <td>Client Name</td>
       <td>{{$data['client']->user->name}}</td>
     </tr>
   </tbody> 
  </table>

  <table border="1" cellpadding="5" cellspacing="0" style="margin-top: 10px;">
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
        <th>Bus Gate</th>
        <th>Other Fees</th>
        <th>Remittance Value</th>
      </tr>
    </thead>
    <tbody>
      @php $i=1; $total=0;
        $cod_total = 0;
        $delivery_fees = 0;
        $bus_gate = 0;
        $other_fees = 0;
        $subtotal = 0;
      @endphp
      @foreach($data["ways"] as $way)
      <tr>
        <td>{{$i++}}</td>
        <td>{{$way->item->codeno}}</td>
        <td>{{'completed'}}</td>
        <td>{{$way->item->receiver_name}}</td>
        <td>{{$way->item->receiver_address}}</td>
        <td>{{Carbon\Carbon::parse($way->delivery_date)->format('d-m-Y')}}</td>
        <td>
          @if($way->item->paystatus == 1)
          @php 
            $cod = $way->item->deposit+$way->item->delivery_fees+$way->item->other_fees; 
            $cod_total += $cod;
          @endphp
          {{number_format($cod)}}
          @elseif($way->item->paystatus == 2)
          @php 
            $cod = 0;
            $cod_total += $cod; 
          @endphp
          {{number_format($cod)}}
          @elseif($way->item->paystatus == 3)
          @php 
            $cod = $way->item->delivery_fees+$way->item->other_fees; 
            $cod_total += $cod
          @endphp
          {{number_format($cod)}}
          @elseif($way->item->paystatus == 4)
          @php 
            $cod = $way->item->deposit;
            $cod_total +=  $cod;
          @endphp
          {{number_format($cod)}}
          @endif
        </td>
        <td>
          @php 
            $delivery = $way->item->delivery_fees;
            $delivery_fees += $delivery; 
          @endphp
          {{number_format($delivery)}}
        </td>
        <td>
          @if(isset($way->item->expense) && $way->item->expense != null)
          @php 
            $bus = $way->item->expense->amount;
            $bus_gate += $bus; 
          @endphp
          {{number_format($bus)}}
          @else
          @php 
            $bus = 0;
            $bus_gate += $bus; 
          @endphp
          {{number_format($bus)}}
          @endif
        </td>
        <td>
          @php 
            $other = $way->item->other_fees;
            $other_fees += $other; 
          @endphp
          {{number_format($other)}}
        </td>
        <td>
          @php 
            $mysubtotal = $cod-$delivery-$bus-$other; 
            $subtotal += $mysubtotal;
          @endphp
          {{number_format($mysubtotal)}}
        </td>
      </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr>
        <td colspan="6">Grand Total</td>
        <td>{{number_format($cod_total)}}</td>
        <td>{{number_format($delivery_fees)}}</td>
        <td>{{number_format($bus_gate)}}</td>
        <td>{{number_format($other_fees)}}</td>
        <td>{{number_format($subtotal)}}</td>
      </tr>
    </tfoot>
  </table>

  <table border="1" cellpadding="5" cellspacing="0" style="margin-top: 10px;">
    <thead>
      <tr>
        <th style="color: green; font-size: 20px;">Grand Total</th>
        <th style="color: green; font-size: 20px;">{{number_format($subtotal)}} Ks</th>
      </tr>
    </thead>
  </table>
</body>
</html>