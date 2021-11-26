@extends("layout")
@section("content")
<div>
	<ol class="breadcrumb">
	  <li><a href="{{{URL::route('user.home')}}}">{{trans('messages.home')}}</a></li>
	  <li class="active">{{ "Tax-Payers" }}</li>
	</ol>
</div>

<div class='container-fluid'>
	<div class='row'>
		<div class='col-md-12'>
			{{ Form::open(array('route' => array('payer.index'), 'class'=>'form-inline',
				'role'=>'form', 'method'=>'GET')) }}
				<div class="form-group">

				    {{ Form::label('search', "search", array('class' => 'sr-only')) }}
		            {{ Form::text('search', Input::get('search'), array('class' => 'form-control test-search barcode')) }}
				</div>
				<div class="form-group">
					{{ Form::button("<span class='glyphicon glyphicon-search'></span> ".trans('messages.search'), 
				        array('class' => 'btn btn-primary', 'type' => 'submit')) }}
				</div>
			{{ Form::close() }}
		</div>
	</div>
</div>

	<br>

@if (Session::has('message'))
	<div class="alert alert-info">{{ trans(Session::get('message')) }}</div>
@endif

<div class="panel panel-primary">
	<div class="panel-heading ">
		<span class="glyphicon glyphicon-user"></span>
		{{ "List Tax-Payers"}}
		
		
			<div class="panel-btn">
				<a class="btn btn-sm btn-info" href="{{ URL::route('payer.create') }}">
					<span class="glyphicon glyphicon-plus-sign"></span>
					{{ "New Tax-Payer"}}
				</a>
			</div>
		
		
	</div>
	<div class="panel-body">
		<table class="table table-striped table-hover table-condensed">
			<thead>
				<tr>
					<th>{{"TPIN"}}</th>
					<th>{{"BusinessCertificateNumber"}}</th>
					<th>{{"TradingName"}}</th>
					<th>{{"BusinessRegistrationDate"}}</th>
					<th>{{"MobileNumber"}}</th>
					<th>{{"Email"}}</th>
					<th>{{"PhysicalLocation"}}</th>
					<th> {{"Username"}} </th>
					
					<th> </th>
				</tr>
			</thead>
			<tbody>
			@foreach($taxPayers as $key => $taxPayer)
			
					<td>{{ $taxPayer->TPIN }}</td>
					<td>{{ $taxPayer->BusinessCertificateNumber}}</td>
					<td>{{ $taxPayer->TradingName }}</td>
					<td>{{ $taxPayer->BusinessRegistrationDate }}</td>
					<td>{{ $taxPayer->MobileNumber  }}</td>
					<td>{{  $taxPayer->Email  }}</td>
					<td>{{  $taxPayer->PhysicalLocation  }}</td>
					<td>{{  $taxPayer->Username  }}</td>
					
					

					<td> 
						
						<!-- show the patient (uses the show method found at GET /patient/{id} -->
						<a class="btn btn-sm btn-info" href="{{ URL::route('payer.edit', array($taxPayer->TPIN.'-'.
																	$taxPayer->BusinessCertificateNumber.'-'.
																	$taxPayer->TradingName.'-'.
																	$taxPayer->BusinessRegistrationDate.'-'.
																	$taxPayer->MobileNumber.'-'.
																	$taxPayer->Email.'-'.
																	$taxPayer->PhysicalLocation.'-'.
																	$taxPayer->Username)) }}" >

							<span class="glyphicon glyphicon-edit"></span>
							{{"Edit"}}
						</a>

					
						<a class="btn btn-sm btn-danger" href="{{ URL::route('payer.show', array($taxPayer->TPIN.'-'.
																	$taxPayer->BusinessCertificateNumber.'-'.
																	$taxPayer->TradingName.'-'.
																	$taxPayer->BusinessRegistrationDate.'-'.
																	$taxPayer->MobileNumber.'-'.
																	$taxPayer->Email.'-'.
																	$taxPayer->PhysicalLocation.'-'.
																	$taxPayer->Username)) }}" >

							<span class="glyphicon glyphicon-edit"></span>
							{{"Delete"}}
						</a>

					</td>
				</tr>
			@endforeach
				
			</tbody>
		</table>
		
	</div>
</div>
@stop
