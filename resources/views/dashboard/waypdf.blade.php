<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<h1>Wyas of {{$data['deliveryman']->user->name}}</h1>
	<table border="1" cellpadding="5px">
                    <thead>
                      <tr>
                      	<th>No</th>
                        <th>Item Cod</th>
                        <th>Delivered Township</th>
                        <th>Receiver Name</th>
                        <th>Full Address</th>
                        <th>Receiver Phone No</th>
                        <th>Client</th>
                        <th>Amount</th>
                      </tr>
                    </thead>

                    <tbody>
                    @php $i=1; @endphp
                     @foreach($data["ways"] as $way)
                     <tr>
                     	<td>{{$i++}}</td>
                     	<td>{{$way->item->codeno}}</td>
                     	@if($way->item->sender_gate_id != null)
                			<td>{{$way->item->SenderGate->name}}</td>
              			@elseif($way->item->sender_postoffice_id != null)
               				<td> {{$way->item->SenderPostoffice->name}}</td>
              			@else
               			 <td>{{$way->item->township->name}}</td>
              			@endif
                     	<td>{{$way->item->receiver_name}}</td>
                     	<td>{{$way->item->receiver_address}}</td>
                     	<td>{{$way->item->receiver_phone_no}}</td>
                     	<td>{{$way->item->pickup->schedule->client->user->name}}<br>
                     	({{$way->item->pickup->schedule->client->phone_no}})
                     	</td>
                     	 @if($way->item->paystatus==1)
              			 <td>{{$way->item->amount}} Ks</td>
            			@else
             				<td>All Paid!</td>
            			@endif
                     </tr>
                     @endforeach
                    </tbody>
              </table>

</body>
</html>