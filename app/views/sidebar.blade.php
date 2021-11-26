@section("sidebar")
<?php
	$active = array("","","","","","", "", "", "");
	$key = explode("?",str_replace("/", "?", Request::path()));
	switch ($key[0]) {
		case 'home': $active[0] = "active"; break;
		case 'patient': $active[1] = "active"; break;

	}
?>
	<ul class="nav nav-sidebar">
		<li>
			<div class="main-menu {{$active[0]}}">
				<a href="{{ URL::route('user.home')}}" title="{{trans('messages.home')}}">
					<span class="glyphicon glyphicon-home"></span> {{trans('messages.home')}}</a>
			</div>
		</li>
		<li>
			<div class="main-menu {{$active[1]}}">
				<a href="{{ URL::route('payer.index')}}">
					<span class="glyphicon glyphicon-download-alt"></span> {{ "Pax Payers"}}</a>
			</div>
		</li>
	
	
	</ul>
