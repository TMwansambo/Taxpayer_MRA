<?php

use Illuminate\Database\QueryException;

/**
 *Contains functions for managing patient records 
 *
 */
class PayerController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
		{
		$search = Input::get('search');
		
		$candidateid_ = "tikhalamwansambo@gmail.com";
		$apikey_ = "52de4c22-2eda-479a-a69b-2fae7d07a5b0";
	
		$url = "https://www.mra.mw/sandbox/";
		$chh = curl_init($url."programming/challenge/webservice/Taxpayers/getAll");	       
		
		curl_setopt($chh, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($chh, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($chh, CURLOPT_HTTPHEADER, array(
				'candidateid:'.$candidateid_,
				'apikey:'. $apikey_)
		);                      
		$ress = json_decode(curl_exec($chh));	
		
		// Load the view and pass the patients
		return View::make('payer.index')->with('taxPayers', $ress)->withInput(Input::all());
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//Create Patient
		$lastInsertId = DB::table('patients')->max('id')+1;
		return View::make('payer.create')->with('lastInsertId', $lastInsertId);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$candidateid_ = "tikhalamwansambo@gmail.com";
		$tpin = Input::get('TPIN');
		$BusinessCertificateNumber = Input::get('BusinessCertificateNumber');
		$TradingName = Input::get('TradingName');
		$BusinessRegistrationDate = Input::get('BusinessRegistrationDate');
		$MobileNumber = Input::get('MobileNumber');
		$Email = Input::get('Email');
		$PhysicalLocation = Input::get('PhysicalLocation');
		$Username = $candidateid_;

		
		$apikey_ = "52de4c22-2eda-479a-a69b-2fae7d07a5b0";
	
		$json = array(
			'TPIN' 	=> $tpin,
			'BusinessCertificateNumber' => $BusinessCertificateNumber,
			'TradingName' => $TradingName,
			'BusinessRegistrationDate' => $BusinessRegistrationDate,
			'MobileNumber' => $MobileNumber,
			'Email' => $Email,
			'PhysicalLocation' => $PhysicalLocation,
			'Username' => $Username
		);	


		$url = "https://www.mra.mw/sandbox/";
		$ch = curl_init($url."programming/challenge/webservice/Taxpayers/add");
		$acc = json_encode($json);           
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $acc);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Accept: application/json',
				'candidateid:'. $candidateid_,
				'apikey:'. $apikey_,
				'Content-Length: ' . strlen($acc))
		);

		$res = json_decode(curl_exec($ch));
		return Redirect::route("payer.index")->with('message', 'The Tax Payer is successfully created!');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($taxPayer)
	{			
		//Show the view and pass the $patient to it
		return View::make('payer.show')->with('taxiPayer',$taxPayer );
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($taxPayer)
	{	
		
		return View::make('payer.edit')->with('taxiPayer', $taxPayer);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{			
		$tpin = Input::get('TPIN');
		$BusinessCertificateNumber = Input::get('BusinessCertificateNumber');
		$TradingName = Input::get('TradingName');
		$BusinessRegistrationDate = Input::get('BusinessRegistrationDate');
		$MobileNumber = Input::get('MobileNumber');
		$Email = Input::get('Email');
		$PhysicalLocation = Input::get('PhysicalLocation');
		$Username = Input::get('Username');

		$candidateid_ = "tikhalamwansambo@gmail.com";
		$apikey_ = "52de4c22-2eda-479a-a69b-2fae7d07a5b0";
	
		$json = array(
			'TPIN' 	=> $tpin,
			'BusinessCertificateNumber' => $BusinessCertificateNumber,
			'TradingName' => $TradingName,
			'BusinessRegistrationDate' => $BusinessRegistrationDate,
			'MobileNumber' => $MobileNumber,
			'Email' => $Email,
			'PhysicalLocation' => $PhysicalLocation,
			'Username' => $candidateid_
		);
	

		$url = "https://www.mra.mw/sandbox/";
		$ch = curl_init($url."programming/challenge/webservice/Taxpayers/edit");
		$acc = json_encode($json);           
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $acc);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Accept: application/json',
				'candidateid:'. $candidateid_,
				'apikey:'. $apikey_,
				'Content-Length: ' . strlen($acc))
		);

		$res = json_decode(curl_exec($ch));

		if(isset($res->Remark) && $res->Remark == "Taxpayer does not exist."){
			return Redirect::route("payer.index")
			->with('message', $res->Remark);
		}else{
			return Redirect::route("payer.index")
			->with('message', 'The Tax Payer details were successfully updated!');
		}

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
	public function delete($tpin)
	{
		$candidateid_ = "tikhalamwansambo@gmail.com";
		$apikey_ = "52de4c22-2eda-479a-a69b-2fae7d07a5b0";
	
		$json = array(
			'TPIN' 						=> $tpin			         
		);
	
		$url = "https://www.mra.mw/sandbox/";
		$ch = curl_init($url."programming/challenge/webservice/Taxpayers/delete");
		$acc = json_encode($json);           
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $acc);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Accept: application/json',
				'candidateid:'. $candidateid_,
				'apikey:'. $apikey_,
				'Content-Length: ' . strlen($acc))
		);

		$res = json_decode(curl_exec($ch));	
		return Redirect::route("payer.index")->with('message', 'Tax Payer was successfully deleted!');
	
	}

	/**
	 * Return a Patients collection that meets the searched criteria as JSON.
	 *
	 * @return Response
	 */
	public function search()
	{
        return Patient::search(Input::get('text'))->take(Config::get('kblis.limit-items'))->get()->toJson();
	}

}
