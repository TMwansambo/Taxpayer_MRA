@extends("layout")
@section("content")
	<div>
		<ol class="breadcrumb">
		  <li><a href="{{{URL::route('user.home')}}}">{{ trans('messages.home') }}</a></li>
		  <li><a href="{{ URL::route('payer.index') }}">{{"Tax-Payers" }}</a></li>
		  <li class="active">{{ "Edit Tax-Payer" }}</li>
		</ol>
	</div>
	<div class="panel panel-primary">
		<div class="panel-heading ">
			<span class="glyphicon glyphicon-user"></span>
			{{ "Create Tax Payer"}}}
		</div>
		<div class="panel-body">
		<!-- if there are creation errors, they will show here -->
			
			@if($errors->all())
				<div class="alert alert-danger">
					{{ HTML::ul($errors->all()) }}
				</div>
			@endif
			{{ Form::open(array('url' => 'payer', 'id' => 'form-create-patient')) }}
			
				<div class="form-group">
					{{ Form::label('TPIN', "TPIN") }}
					{{ Form::text('TPIN', Input::old('TPIN'), array('class' => 'form-control')) }}
				</div>
				<div class="form-group">
					{{ Form::label('BusinessCertificateNumber', "BusinessCertificateNumber") }}
					{{ Form::text('BusinessCertificateNumber', Input::old('BusinessCertificateNumber'), array('class' => 'form-control')) }}
				</div>
				<div class="form-group">
					{{ Form::label('TradingName', "TradingName") }}
					{{ Form::text('TradingName', Input::old('TradingName'), array('class' => 'form-control')) }}
				</div>		
			
				<div class="form-group">
					{{ Form::label('BusinessRegistrationDate', trans('messages.date-of-birth')) }}
					{{ Form::text('BusinessRegistrationDate', Input::old('BusinessRegistrationDate'), 
						array('class' => 'form-control standard-datepicker')) }}
				</div>
				<div class="form-group">
					{{ Form::label('MobileNumber', 'MobileNumber') }}
					{{ Form::text('MobileNumber', Input::old('MobileNumber'), array('class' => 'form-control')) }}
				</div>
				<div class="form-group">
					{{ Form::label('Email', 'Email') }}
					{{ Form::email('Email', Input::old('Email'), array('class' => 'form-control')) }}
				</div>
				<div class="form-group">
					{{ Form::label('PhysicalLocation', 'PhysicalLocation') }}
					{{ Form::text('PhysicalLocation', Input::old('PhysicalLocation'), array('class' => 'form-control')) }}
				</div>
				<div class="form-group">
					{{ Form::label('Username', 'Username') }}
					{{ Form::email('Username', Input::old('Username'), array('class' => 'form-control')) }}
				</div>
				<div class="form-group actions-row">
					{{ Form::button('<span class="glyphicon glyphicon-save"></span> '.trans('messages.save'), 
						['class' => 'btn btn-primary', 'onclick' => 'submit()']) }}
				</div>

			{{ Form::close() }}
		</div>
	</div>
@stop	
