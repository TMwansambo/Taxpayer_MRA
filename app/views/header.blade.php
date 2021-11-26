@section ("header")
   
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand">{{ "Tax Payers" }} {{ "v1.0.1" }}
                    </a>
            </div>

            <div class="grid-3 user-profile">
               
                    <ul class="nav navbar-nav navbar-right dropup">
                        <li class="user-link">
                            <a href="javascript:void(0);">
                                <strong><i>{{ "Mwansambo" }}</i></strong>
                            </a>
                        </li>
                    </ul>
                    <div class="user-settings">
                     
                        <div>
                            <span class="glyphicon glyphicon-log-out"></span>
                            <a href="{{ URL::route("user.logout") }}">{{trans('messages.logout')}}</a>
                        </div>
                    </div>
               
            </div>

         

            <div class="pull-right">

            

            </div>

        </div>
    </div>
@show
