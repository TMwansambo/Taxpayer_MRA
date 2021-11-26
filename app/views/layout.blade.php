<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/ui-lightness/jquery-ui-min.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/bootstrap.min.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/bootstrap-theme.min.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/dataTables.bootstrap.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/layout.css') }}" />
        <script type="text/javascript" src="{{ URL::asset('js/jquery.js') }}"></script>
        <script type="text/javascript" src="{{ URL::asset('js/jquery-ui-min.js') }}"></script>
        <script type="text/javascript" src="{{ URL::asset('js/bootstrap.min.js') }}"></script>
        <script type="text/javascript" src="{{ URL::asset('js/jquery.dataTables.js') }}"></script>
        <script type="text/javascript" src="{{ URL::asset('js/dataTables.bootstrap.js') }}"></script>
        <script type="text/javascript" src="{{ URL::asset('js/script.js') }} "></script>
        <script type="text/javascript" src="{{ URL::asset('js/spin.js') }} "></script>
        <script type="text/javascript" src="{{ URL::asset('highcharts/highcharts.js') }} "></script>
        <script type="text/javascript" src="{{ URL::asset('highcharts/exporting.js') }} "></script>
        <title>{{ Config::get('kblis.name') }} {{ Config::get('kblis.version') }}</title>
    </head>
    <body>
        <div id="wrap" class="no-select">
            @include("header")
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-2 sidebar" style="width:15% !important;">
                        @include("sidebar")
                    </div>
                    <div class="col-md-10 col-md-offset-2 main" id="the-one-main" style="width:85% !important;margin-left: 15% !important;
                        padding-left: 5px !important;padding-right: 5px !important;">
                        @yield("content")
                    </div>
                </div>
            </div>
        </div>
        @include("footer")
    </body>
</html>