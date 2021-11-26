<?php


Artisan::add(new NlimsAunthenticate); //authenticating ready for user creation at nlims
Artisan::add(new NlimsCreateUser); //create national lims user
Artisan::add(new NlimsSync); //for syncing to national lims
/*
|--------------------------------------------------------------------------
| Register The Artisan Commands
|--------------------------------------------------------------------------
|
| Each available Artisan command must be registered with the console so
| that it is available to be called. We'll register every command so
| the console gets access to each of the command object instances.
|
*/

