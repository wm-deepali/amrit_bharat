<div class="modal-dialog">
<div class="modal-content">
<!-- Modal Header -->
<div class="modal-header">
<h4 class="modal-title">Payment Details</h4>
<button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<!-- Modal body -->
<div class="modal-body">
<div class="table-responsive">
<table class="table table-bordered">
<tr>
<th>Date &amp; Time</th>
<td>{{ $ad->created_at }}</td>
<th>Payment Method</th>
<td>{{ $ad->paymentmethod ?? 'NA' }}</td>
</tr>
<tr>
<th>Transaction Id</th>
<td>{{ $ad->transactionnumber }}</td>
<th>Payment Status</th>
<td>{{ $ad->paymentstatus }}</td>
</tr>
<tr>
    <th>Remark</th>
    <td>{{ $ad->remark ?? 'NA' }}</td>
</tr>
@if ($ad->paymentmethod=='cash')
<tr>
    <th>Collected By</th>
    <td>{{ $ad->collectedby }}</td>
</tr>
@endif
@if ($ad->paymentmethod=='cheque')
<tr>
    <th>Cheque Date</th>
    <td>{{ $ad->chequedate }}</td>
    <th>Cheque Number</th>
    <td>{{ $ad->chequenumber }}</td>
</tr>
<tr>
    <th>Bank Name</th>
    <td>{{ $ad->bankname }}</td>
    <th>Cheque Number</th>
    <td>{{ $ad->bankbranch }}</td>
</tr>
@endif
@if ($ad->paymentmethod=='netbanking')
<tr>
    <th>UTR Number</th>
    <td>{{ $ad->utrnumber }}</td>
    <th>UTR Date</th>
    <td>{{ $ad->utrdate }}</td>
</tr>
@endif
@if ($ad->paymentmethod=='upi')
<tr>
    <th>UPI ID</th>
    <td>{{ $ad->upiid }}</td>
    <th>UPI Date</th>
    <td>{{ $ad->upidate }}</td>
</tr>
<tr>
    <th>UPI Reference Number</th>
    <td>{{ $ad->upireferencenumber }}</td>
</tr>
@endif
@if ($ad->paymentmethod=='wallet')
<tr>
    <th>Wallet</th>
    <td>{{ $ad->wallet }}</td>
    <th>Wallet Date</th>
    <td>{{ $ad->walletdate }}</td>
</tr>
<tr>
    <th>Wallet Reference Number</th>
    <td>{{ $ad->walletreferencenumber }}</td>
</tr>
@endif
</table>  
</div>
</div>
</div>
</div>