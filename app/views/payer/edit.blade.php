	@extends("layout")
@section("content")
	<div>
		<ol class="breadcrumb">
		  <li><a href="{{{URL::route('user.home')}}}">{{trans('messages.home')}}</a></li>
		  <li><a href="{{ URL::route('payer.index') }}">{{ "Tax-Payers"  }}</a></li>
		  <li class="active">{{  "Edit Tax-Payer" }}</li>
		</ol>
	</div>

	<div class="panel panel-primary">
		<div class="panel-heading ">
			<span class="glyphicon glyphicon-edit"></span>
			{{ "Edit Tax Payer"}}
		</div>
		<div class="panel-body">
			@if($errors->all())
				<div class="alert alert-danger"> 
					{{ HTML::ul($errors->all()) }}
				</div>
			@endif
			{{ Form::model($taxiPayer, array('route' => array('payer.update', $taxiPayer), 'method' => 'PUT',
				'id' => 'form-edit-patient')) }}

				<div class="form-group">
					{{ Form::label('TPIN', "TPIN") }}
					{{ Form::text('TPIN', explode("-",$taxiPayer)[0], 
						array('class' => 'form-control')) }}
				</div>
				<div class="form-group">
					{{ Form::label('BusinessCertificateNumber', "BusinessCertificateNumber") }}
					{{ Form::text('BusinessCertificateNumber', explode("-",$taxiPayer)[1], array('class' => 'form-control')) }}
				</div>
				<div class="form-group">
					{{ Form::label('TradingName', "TradingName") }}
					{{ Form::text('TradingName', explode("-",$taxiPayer)[2], array('class' => 'form-control')) }}
				</div>
				<div class="form-group">
					{{ Form::label('BusinessRegistrationDate', "BusinessRegistrationDate") }}
					{{ Form::text('BusinessRegistrationDate', explode("-",$taxiPayer)[3], array('class' => 'form-control')) }}
				</div>
				<div class="form-group">
					{{ Form::label('MobileNumber', "MobileNumber") }}
					{{ Form::text('MobileNumber', explode("-",$taxiPayer)[4], array('class' => 'form-control')) }}
				</div>              
				<div class="form-group">
					{{ Form::label('Email', "Email") }}
					{{ Form::text('Email', explode("-",$taxiPayer)[5], array('class' => 'form-control')) }}
				</div>
				<div class="form-group">
					{{ Form::label('PhysicalLocation', "PhysicalLocation") }}
					{{ Form::text('PhysicalLocation', explode("-",$taxiPayer)[6], array('class' => 'form-control')) }}
				</div>
				<div class="form-group">
					{{ Form::label('Username', "Username") }}
					{{ Form::email('Username', explode("-",$taxiPayer)[7], array('class' => 'form-control')) }}
				</div>
				<div class="form-group actions-row">
					{{ Form::button('<span class="glyphicon glyphicon-save"></span> '.trans('messages.save'),
						 array('class' => 'btn btn-primary', 'onclick' => 'submit()')) }}
				</div>

			{{ Form::close() }}
		</div>
	</div>
@stop	