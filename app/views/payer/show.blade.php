@extends("layout")
@section("content")
    <div>
        <ol class="breadcrumb">
          <li><a href="{{{URL::route('user.home')}}}">{{ trans('messages.home') }}</a></li>
          <li><a href="{{ URL::route('payer.index') }}">{{ "Tax-Payers"  }}</a></li>
          <li class="active">{{ "Delete Tax-Payer" }}</li>
        </ol>
    </div>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <span class="glyphicon glyphicon-user"></span>
            {{ "Tax-Payer"}}
            <div class="panel-btn">
           
     
            </div>
        </div> 
        <div class="panel-body">
            <div class="display-details">
            <?php $tpin = explode("-",$taxiPayer)[0] ?>
                <h3 class="view"><strong>{{ "Pax Payer TPIN" }}</strong>{{ explode("-",$taxiPayer)[0]}} </h3>
                <p class="view-striped"><strong>{{ "BusinessCertificateNumber" }}</strong>
                    {{ explode("-",$taxiPayer)[1]}}</p>
                <p class="view"><strong>{{ "TradingName" }}</strong>
                    {{ explode("-",$taxiPayer)[2]}}</p>
                <p class="view-striped"><strong>{{ "BusinessRegistrationDate" }}</strong>
                    {{ explode("-",$taxiPayer)[3]}}</p>
                <p class="view"><strong>{{ "MobileNumber"}}</strong>
                    {{ explode("-",$taxiPayer)[4]}}</p>
                <p class="view-striped"><strong>{{ "Email"}}</strong>
                    {{ explode("-",$taxiPayer)[5]}}</p>
                <p class="view"><strong>{{ "PhysicalLocation" }}</strong>
                    {{ explode("-",$taxiPayer)[6]}}</p>
                <p class="view-striped"><strong>{{ "Username" }}</strong>
                    {{ explode("-",$taxiPayer)[7]}}</p>
            </div>
            <a class="btn btn-sm btn-danger" href="{{ URL::route('payer.delete', array($tpin)) }}" >
							<span class="glyphicon glyphicon-edit"></span>
							{{ "Delete"}}
			</a>
        </div>
    </div>




@stop
