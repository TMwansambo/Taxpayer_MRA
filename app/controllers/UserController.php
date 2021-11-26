<?php

use Illuminate\Support\MessageBag;

/**
 *Contains functions for managing users 
 *
 */
class UserController extends Controller {
    
    //Function for user authentication logic
    public function loginAction(){

        if (Input::server("REQUEST_METHOD") == "POST") 
        {
            $validator = Validator::make(Input::all(), array(
                "username" => "required|min:4",
                "password" => "required|min:6",
            ));

            $username = Input::get("username");
            $password = Input::get("password");       
            $apikey = "3fdb48c5-336b-47f9-87e4-ae73b8036a1c";
            $candidateid = "tikhalamwansambo@gmail.com";
            $message = trans('messages.invalid-login');

            $json = array(
                'Email' 						=> $username,
                'Password' 						=> $password,          
            );
        
            $credentials = array(
                "username" => "administrator",
                "password" =>  "kchlims"
            );
              
            $url = "https://www.mra.mw/sandbox/";
            $ch = curl_init($url."programming/challenge/webservice/auth/login");
            $acc = json_encode($json);           
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $acc);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'candidateid:'. $candidateid,
                    'apikey:'. $apikey,
                    'Content-Length: ' . strlen($acc))
            );
            $res = json_decode(curl_exec($ch));	
         
            if ($res) {
                
                if ($res->Remark == "Successful"){       
                    $acess =  $res->Token->Value;
                       
                        $jsonn = array(
                            'TPIN' 					                        	=> "TPIN1088997650",
                            'BusinessCertificateNumber' 						=> "MW50M509099",    
                            'TradingName'                                       => "MWANSAhh",
                            'BusinessRegistrationDate'                          => "201905jh12",
                            'MobileNumber'                                      => "0978926266",
                            'Email'                                             => "mwa@gmail.com",
                            'PhysicalLocation'                                  => "Falls",
                            'Username'                                          => $candidateid
                        );
                        
                        $chh = curl_init($url."programming/challenge/webservice/Taxpayers/add");
                        $accc = json_encode($jsonn);           
                        
                        curl_setopt($chh, CURLOPT_CUSTOMREQUEST, "POST");
                        curl_setopt($chh, CURLOPT_POSTFIELDS, $accc);
                        curl_setopt($chh, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($chh, CURLOPT_HTTPHEADER, array(
                                'Content-Type: application/json',
                                'Accept: application/json',
                                'candidateid:'. $candidateid,
                                'apikey:'. $res->Token->Value,
                                'Content-Length: ' . strlen($accc))
                        );                      
                        $ress = json_decode(curl_exec($chh));	
                      
                        return Redirect::route("user.home");
                    
                }else{
                    return Redirect::route('user.login')->withInput(Input::except('password'))
                        ->withErrors($validator)
                        ->with('message', $res->Remark);
                }
            }
          
            
        }
      
        return View::make("user.login");
    }

    public function logoutAction(){
        Auth::logout();
        Session::forget("location_id");
        return Redirect::route("user.login");
    }
   
    public function homeAction(){
    	return View::make("user.home");	
    }


    /**
     * Display a listing of the users.
     *
     * @return Response
     */
    public function index()
    {
        // List all the active users
            $users = User::orderBy('name', 'ASC')->get();

        // Load the view and pass the users
        return View::make('user.index')->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //Show a user
        $user = User::find($id);

        //Show the view and pass the $user to it
        return View::make('user.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function updateOwnPassword($id)
    {
        //
     
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage (soft delete).
     *
     * @param  int  $id
     * @return Response
     */
    public function delete($id)
    {
      
    }

    public function labsections(){

        $user = $me;
    }
}
