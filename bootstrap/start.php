<?php


/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application;

/*
|--------------------------------------------------------------------------
| Detect The Application Environment
|--------------------------------------------------------------------------
|
| Laravel takes a dead simple approach to your application environments
| so you can just specify a machine name for the host that matches a
| given environment, then we will automatically detect it for you.
|
*/

$env = $app->detectEnvironment(array(

	'local' => array('stallion'),

));

/*
|--------------------------------------------------------------------------
| Bind Paths
|--------------------------------------------------------------------------
|
| Here we are binding the paths configured in paths.php to the app. You
| should not be changing these here. If you need to change these you
| may do so within the paths.php file and they will be bound here.
|
*/

$app->bindInstallPaths(require __DIR__.'/paths.php');

/*
|--------------------------------------------------------------------------
| Load The Application
|--------------------------------------------------------------------------
|
| Here we will load this Illuminate application. We will keep this in a
| separate location so we can isolate the creation of an application
| from the actual running of the application with a given request.
|
*/

$framework = $app['path.base'].
                 '/vendor/laravel/framework/src';

require $framework.'/Illuminate/Foundation/start.php';

/*
|--------------------------------------------------------------------------
|       Descrypt database credentials
|       Kenneth Kapundi
|--------------------------------------------------------------------------
|
/The function below will override encrypted database connections attributes with human-readable values
|
*/

foreach(Config::get("database.connections.mysql") AS $key => $str){

    if(strlen($str) > 40){
        $password = $password = trim(file_get_contents(base_path("app/config/key")));
        $cipher_method = 'AES-256-CBC';
        $val = str_replace(array('-', '_'), array('+', '/'), $str);
        $data = base64_decode($val);
        $iv_length = openssl_cipher_iv_length($cipher_method);
        $body_data = substr($data, $iv_length);
        $iv = substr($data, 0, $iv_length);
        $base64_body_data = base64_encode($body_data);
        try{
            $enc = openssl_decrypt($base64_body_data, $cipher_method, $password, 0, $iv);
            if(!empty($enc)){
                Config::set("database.connections.mysql." . $key, $enc);
            }
        }catch(Exception $e){
            return null;
        }
    }
}


/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/


return $app;

