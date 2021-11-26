<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/bootstrap.min.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/bootstrap-theme.min.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/layout.css') }}" />
        <script type="text/javascript" src="{{ URL::asset('js/jquery.js') }} "></script>
        <script type="text/javascript" src="{{ URL::asset('js/script.js') }} "></script>
        <title>{{"MRA v1.0.0"}}</title>
    </head>
    <body>
        <div class="container login-page">
            <div class="login-form">
                <div class="form-head">
                    <img src="{{ Config::get('kblis.organization-logo') }}" alt="" height="90" width="90">
                    <h3> {{ "Malawi Revenue Authourity" }} </h3>
                    @if($errors->all())
                        <div class="alert alert-danger">
                            {{ HTML::ul($errors->all()) }}
                        </div>
                    @elseif (Session::has('message'))
                        <div class="alert alert-danger">{{ Session::get('message') }}</div>
                    @endif
                </div>
                {{ Form::open(array(
                    "route"        => "user.login",
                    "autocomplete" => "off",
                    "class" => "form-signin",
                    "role" => "form"
                )) }}
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon glyphicon glyphicon-user"></span>
                            {{ Form::text("username", Input::old("username"), array(
                                "placeholder" => trans('messages.username'),
                                "class" => "form-control"
                            )) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon glyphicon glyphicon-lock"></span>
                            {{ Form::password("password", array(
                                "placeholder" => Lang::choice('messages.password',1),
                                "class" => "form-control"
                            )) }}
                        </div>
                    </div>


                    <div class="form-group">
                        <div>
                            {{ Form::button(trans('messages.login'), array(
                                "type" => "submit",
                                "class" => "btn btn-primary btn-block"
                            )) }}
                        </div>
                    </div>
                {{ Form::close() }}
                <div class="smaller-text alone foot">
                    <p><a href="#">version v1.0.1</a></p>
                    <p>
                       
                    </p>
                </div>
            </div>
            
        </div>
    </body>
</html>
