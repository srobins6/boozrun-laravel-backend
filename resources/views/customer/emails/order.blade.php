@extends("layouts.email")@section("headerImage",asset("branding/order_header.png"))
@section("emailText")
	<h4 align="center">Thank you for using BoozRun, the party will be there soon!</h4>
	<h4 align="center">Have your ID ready to show the driver.</h4>
	<p align="left">Store Name: {{$order->store->name}}
		<br>
		Store Phone: {{$order->store->phone}}
		<br>
		Date: {{date("F j, Y")}}
	</p>
	<br>
	<table align="left" border="0" cellpadding="0" cellspacing="0"
		style="max-width: 100% !important; min-width: 100%; border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: none !important; width: 100% !important;"
		width="100%">
		<thead>
			@foreach($order->items as $item)
				<tr>
					<td style="border-bottom:solid 1px;">{{$item->quantity . " x " . $item->name}}</td>
					<td align="right"
						style="padding-left:10px; border-bottom:solid 1px;">{{"$".number_format($item->orderPrice,2)}}</td>
				</tr>
			@endforeach
		</thead>
		<tbody>
			<tr>
				<td align="right">Subtotal:</td>
				<td align="right" style="padding-left:10px;"> ${{number_format($order->subtotal,2)}}</td>
			</tr>
			<tr>
				<td align="right">Tax:</td>
				<td align="right" style="padding-left:10px;"> ${{number_format($order->tax,2)}}</td>
			</tr>
			<tr>
				<td align="right">Tip:</td>
				<td align="right" style="padding-left:10px;"> ${{number_format($order->tip,2)}}</td>
			</tr>
			<tr>
				<td align="right">Delivery Fee:</td>
				<td align="right" style="padding-left:10px;"> ${{number_format($order->delivery,2)}}</td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<th align="right" style=" border-top:solid 1px;">Total:</th>
				<th align="right" style="padding-left:10px; border-top:solid 1px;">
					${{number_format($order->total,2)}}</th>
			</tr>
		</tfoot>
	</table>
	<br>
	<p align="left">-The BoozCrew</p>
@endsection