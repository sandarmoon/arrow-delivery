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
	<h1>Ways of {{$data['deliveryman']->user->name}}</h1>
	<table border="1" cellpadding="5" cellspacing="0">
    <thead>
      <tr>
      	<th>No</th>
        <th>Codeno</th>
        <th>Client Info</th>
        <th>Receiver Info</th>
        <th>Receiver Address</th>
        <th>Item Price</th>
        <th>Deli Fees</th>
        <th>Other Charges</th>
        <th>Subtotal</th>
      </tr>
    </thead>

    <tbody>
      @php $i=1; $total=0;@endphp
      @foreach($data["ways"] as $way)
        @php
          $allpaid = "";
          $payment_type = "";
          $total += $way->item->deposit+$way->item->delivery_fees+$way->item->other_fees;

          if ($way->item->paystatus==2) {
            $payment_type = "(allpaid)";
            $allpaid = "table-warning";
            $total -= ($way->item->delivery_fees+$way->item->other_fees);
          }elseif ($way->item->paystatus==3) {
            $payment_type = "(only deli)";
          }elseif ($way->item->paystatus==4) {
            $payment_type = "(only item price)";
          }
        @endphp
        <tr class="{{$allpaid}}">
         	<td>{{$i++}}</td>
         	<td>
            <span class="d-block">{{$way->item->codeno}}</span>{{$payment_type}}
          </td>
          <td>
            {{$way->item->pickup->schedule->client->user->name}}<br>
            ({{$way->item->pickup->schedule->client->phone_no}})
          </td>
         	
         	<td class="mmfont">
            <span class="d-block">{{strip_tags(zawuni($way->item->receiver_name))}}</span>
            {{'('.$way->item->receiver_phone_no.')'}}
          </td>
         	<td class="mmfont">
            <span class="d-block">{{$way->item->receiver_address}}</span>
            {{strip_tags(zawuni($way->item->receiver_address))}}
          </td>
         	<td>{{number_format($way->item->deposit)}}</td>
          <td>{{number_format($way->item->delivery_fees)}}</td>
          <td>{{number_format($way->item->other_fees)}}</td>
          <td>{{number_format($way->item->deposit+$way->item->delivery_fees+$way->item->other_fees)}}</td>
        </tr>
      @endforeach
        <tr>
          <td colspan="5">Total Amount</td>
          <td colspan="4">{{number_format($total)}} Ks</td>
        </tr>
    </tbody>
  </table>
</body>
</html>