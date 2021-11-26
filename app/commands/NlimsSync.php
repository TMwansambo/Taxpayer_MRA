<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class NlimsSync extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'nlims:sync';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$testMapping = config::get('nlims_test_mapping');	
		$measureMapping = config::get('nlims_measures_mapping');		
		$url = config::get('nlims_connection.nlims_controller_ip');	
		$nlims_username = config::get('nlims_connection.nlims_custome_username');	
		$nlims_password = config::get('nlims_connection.nlims_custome_password');	
	
		$ch = curl_init($url."/api/v1/re_authenticate/".$nlims_username."/".$nlims_password);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Accept: application/json')
		);

		$res = json_decode(curl_exec($ch));
	//var_dump($res);exit;
		if($res->message == "re authenticated successfuly")
			{	$token = $res->data->token; }
	
	   	$res = DB::select("SELECT specimens.drawn_by_id AS drawn_id, specimens.drawn_by_name AS drawn_name,specimens.id AS specimen_id,
	   						specimens.tracking_number,specimens.priority,specimens.date_of_collection,specimen_types.name AS specimen_type ,
							specimen_statuses.name AS sample_status FROM unsync_orders                        
							INNER JOIN specimens ON specimens.id = unsync_orders.specimen_id 
							INNER JOIN specimen_types ON specimens.specimen_type_id = specimen_types.id
							INNER JOIN specimen_statuses ON specimen_statuses.id = specimens.specimen_status_id           
							WHERE (data_level='specimen' AND sync_status='not-synced') AND data_not_synced='new order'");

	   if ($res)
	   {
		   foreach($res as $order){
			  	$tracking_number = $order->tracking_number;
				$priority = $order->priority;
				$date_of_collection = $order->date_of_collection;
				$sample_status = str_replace("-","_",$order->sample_status);
				$sample_type = $order->specimen_type;
				$sample_id = $order->specimen_id;
				$drawn_first_name = "";
				$drawn_last_name = "";
				if ($order->drawn_name != null) {$drawn_first_name = explode(" ",$order->drawn_name)[0]; }
				if ($order->drawn_name != null) {$drawn_last_name = explode(" ",$order->drawn_name)[1];}
				$drawn_id = $order->drawn_id;

					$p_first_name = "";
					$p_last_name = "";
					$p_dob = "";
					$p_gender = "";
					$p_id = "";
					$p_phone = "";
					$ward = "";

				$tests_ = array();
				$test_id = 0;
				$tests =  DB::select("SELECT tests.id AS test_id, test_types.name AS test_name,tests.time_created AS time_created                        
									FROM tests 
									INNER JOIN test_types ON test_types.id = tests.test_type_id
									WHERE tests.specimen_id =".$sample_id                      
								);
				if($tests){
					foreach($tests as $test){
						$test_name = $test->test_name;
						//var_dump(array_key_exists($test_name,$testMapping));exit;
						if (array_key_exists($test_name,$testMapping) == true) {$test_name = $testMapping[$test->test_name];};
						array_push($tests_,$test_name);
						$test_id = $test->test_id;
						$date_of_collection = $test->time_created;

					}
				}
				
				$vst = DB::select("SELECT ward_or_location AS ward, patients.name AS pat_name, patients.dob, patients.gender,
										patients.phone_number, patients.patient_number
										FROM visits INNER JOIN tests ON tests.visit_id =  visits.id 
										INNER JOIN patients ON patients.id = visits.patient_id
										WHERE tests.id =".$test_id
										);
				if($vst){
					foreach($vst as $visit){
						$p_first_name = explode(" ",$visit->pat_name)[0];
						$p_last_name = explode(" ",$visit->pat_name)[1];
						$p_dob = $visit->dob;
						$p_gender = $visit->gender;
						$p_id = $visit->patient_number;
						$p_phone = $visit->phone_number;
						$ward = $visit->ward;
					}
				}
				
				$json = array(
					'tracking_number' 						=> $tracking_number,
					'district' 								=> config::get('kblis.district'),
					'health_facility_name'					=> config::get('kblis.facility_name'),
					'sample_type'							=> $sample_type,
					'date_sample_drawn'						=> $date_of_collection,
					'sample_status' 						=> $sample_status,
					'sample_priority' 						=> $priority,
					'art_start_date' 						=> '',
					'date_received' 						=> $date_of_collection,
					'requesting_clinician' 					=> '',
					'return_json' 							=> 'true',
					'target_lab' 							=> config::get('kblis.facility_name'),
					'tests' 								=>  $tests_,
					'who_order_test_last_name' 				=> $drawn_last_name,
					'who_order_test_first_name' 			=> $drawn_first_name,
					'who_order_test_phone_number' 			=> '',
					'who_order_test_id' 					=> $drawn_id,
					'order_location' 						=> $ward,
					'first_name' 							=> $p_first_name,
					'last_name' 							=> $p_last_name,
					'middle_name' 							=>  '',
					'reason_for_test' 						=>  $priority,
					'date_of_birth' 						=> $p_dob,
					'gender' 								=> $p_gender,
					'patient_residence' 					=> '',
					'patient_location' 						=> '',
					'patient_town' 							=> '',
					'patient_district' 						=> '',
					'national_patient_id' 					=> '',
					'phone_number' 							=> ''
				);
			
				$acc = json_encode($json);
				$ch = curl_init($url."/api/v1/create_order/");
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $acc);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
						'Content-Type: application/json',
						'Accept: application/json',
						'token: '. $token,
						'Content-Length: ' . strlen($acc))
				);
				$res = json_decode(curl_exec($ch));		
	//var_dump($res);exit;			
				if($res->error == false && $res->message == "order created successfuly"){				
					$unsync = UnsyncOrder::where('sync_status', 'not-synced')->where('data_not_synced','new order')->where('specimen_id',$sample_id)->first();
					$unsync->sync_status = "synced";
					$unsync->save();
					dd($res);
				}
			}
	   }



	   //echo 'finishing creating orders'; 

	   	$ch = curl_init($url."/api/v1/re_authenticate/".$nlims_username."/".$nlims_password);
	  	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
	  	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	   	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			   'Content-Type: application/json',
			   'Accept: application/json')
	   	);

	   	$res = json_decode(curl_exec($ch));
	   	if($res->message == "re authenticated successfuly")
		   {	$token = $res->data->token; }

		   	   
		
		$res = DB::select("SELECT specimens.id AS sample_id, specimens.tracking_number, unsync_orders.data_not_synced AS sample_status, 
						unsync_orders.updated_by_name AS updater, unsync_orders.updated_by_id AS updater_id FROM unsync_orders                        
						INNER JOIN specimens ON specimens.id = unsync_orders.specimen_id          
						WHERE (data_level='specimen' AND sync_status='not-synced') AND 
						(data_not_synced='specimen-rejected' OR data_not_synced='verified' OR data_not_synced='specimen-accepted' 
						OR data_not_synced='specimen-collected' OR data_not_synced='accept specimen')");
		
		if($res){
			foreach($res as $order){
				
				$updater_f_name = "";
				$updater_l_name = "";
				$sam = "";
				$sam1 = "";
				$tracking_number = $order->tracking_number;
				$sample_status = str_replace("-","_",$order->sample_status);
				if($order->updater != null) {$updater_f_name = explode(" ",$order->updater)[0];};
				if($order->updater != null) {$updater_l_name = explode(" ",$order->updater)[1];};
				$updater_id = $order->updater_id;
				$sample_id = $order->sample_id;
		
				if ($sample_status == "verified") {$sam =  "verified";};
				if ($sample_status == "accept specimen") {$sam1 = "accept specimen";};
				if ($sample_status == "verified") {$sample_status = "specimen_accepted";};
				if ($sample_status == "accept specimen") {$sample_status = "specimen_accepted";};


				$json = array (
					'tracking_number' 	 => $tracking_number,
					'status'			 => $sample_status,
					'who_updated'		 => array(
						'first_name' => $updater_f_name,
						'last_name'  => $updater_l_name,
						'id'	     => $updater_id
					)				
				);

				if ($sam == "verified") {$sample_status = "verified";};
				if ($sam1 == "accept specimen") {$sample_status = "accept specimen";};				
				$sample_status = str_replace(" ","-",$sample_status);

				
				$acc = json_encode($json);
				$ch = curl_init($url."/api/v1/update_order/");
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $acc);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
						'Content-Type: application/json',
						'Accept: application/json',
						'token: '. $token,
						'Content-Length: ' . strlen($acc))
				);
				$res = json_decode(curl_exec($ch));
			
				if($res->error == false && $res->message == "order updated successfuly"){				
					$unsync = UnsyncOrder::where('sync_status', 'not-synced')->where('data_not_synced',$order->sample_status)->where('specimen_id',$sample_id)->first();
					$unsync->sync_status = "synced";
					$unsync->save();
					dd($res);
				}

			}
		}







		//echo 'finishing updating orders'; 
		
		
		$ch = curl_init($url."/api/v1/re_authenticate/".$nlims_username."/".$nlims_password);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		 curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			 'Content-Type: application/json',
			 'Accept: application/json')
		 );

		 $res = json_decode(curl_exec($ch));
		 if($res->message == "re authenticated successfuly")
		 {	$token = $res->data->token; }

		
		$res = DB::select("SELECT specimens.id AS sample_id,unsync_orders.specimen_id AS test_id ,specimens.tracking_number, 
					unsync_orders.data_not_synced AS test_status, unsync_orders.updated_by_name AS updater, 
					unsync_orders.updated_by_id AS updater_id, unsync_orders.updated_at 
					FROM unsync_orders    
					INNER JOIN tests ON tests.id = unsync_orders.specimen_id                     
					INNER JOIN specimens ON specimens.id = tests.specimen_id          
					WHERE unsync_orders.data_level='test' AND unsync_orders.sync_status='not-synced'");

		if($res){
			foreach ($res AS $order){
				$tst_name = DB::select("SELECT test_types.name AS test_name FROM tests INNER JOIN test_types ON test_types.id = tests.test_type_id WHERE tests.id=".$order->test_id);
				if ($tst_name != null) {$tst_name = $tst_name[0]->test_name;};
				if (array_key_exists($tst_name,$testMapping) == true){$tst_name = $testMapping[$test->test_name];};
				
				$updater_f_name  = "";
				$updater_l_name  = "";
				$xm = "";
				$tracking_number = $order->tracking_number;
				$test_status = str_replace("-","_",$order->test_status);       
				if($order->updater != null) {$updater_f_name = explode(" ",$order->updater)[0];};
				if($order->updater != null) {$updater_l_name = explode(" ",$order->updater)[1];};
				$updater_id = $order->updater_id;
				$sample_id = $order->sample_id;
				$result_date = $order->updated_at;
				if ($test_status == "result") {$xm = "verified";};
				if ($test_status == "result") {$test_status = "verified";};
				if ($test_status != "result") {$result_date = "";};
				
				$json = array(
					"tracking_number" => $tracking_number,
					"test_status" => $test_status,
					"test_name" => $tst_name,
					"result_date" => $result_date,
					"who_updated" => array(
					  "first_name" => $updater_f_name,
					  "last_name" => $updater_l_name,
					  "id" => $updater_id
					)
				);



				if ($order->test_status == "result"){
					
					$t_r = DB::select("SELECT measures.name AS m_name, test_results.result AS result_va FROM test_results 
										INNER JOIN tests ON tests.id = test_results.test_id 
										INNER JOIN measures ON measures.id = test_results.measure_id
										WHERE test_results.test_id=".$order->test_id);

					$measures = array();
					if ($t_r != null){
						foreach ($t_r AS $rs_data){
							$measure_name = $rs_data->m_name;
							if ($measure_name == "Epithelial cell"){
									$measure_name = "Epithelial cells";}
							else if ($measure_name == "Cast"){
									$measure_name = "Casts";}
							else if ($measure_name == "Yeast cell"){
								$measure_name = "Yeast cells";}
							else if ($measure_name == "HepB"){
								$measure_name  = "Hepatitis B";}							
						
							if (array_key_exists($measure_name,$measureMapping) == true){$measure_name = $measureMapping[$measure_name];};
							$result_value = $rs_data->result_va; 
							$measures[$measure_name] = $result_value;							
							
						}
					}
					$json["results"] = $measures;  
				}   
				
				if ($xm  == "verified") {$test_status = "result";};
				
				$acc = json_encode($json);
				$ch = curl_init($url."/api/v1/update_test/");
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $acc);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
						'Content-Type: application/json',
						'Accept: application/json',
						'token: '. $token,
						'Content-Length: ' . strlen($acc))
				);
				$res = json_decode(curl_exec($ch));
				
				
				if($res->error == false && $res->message == "test updated successfuly"){				
					$unsync = UnsyncOrder::where('sync_status', 'not-synced')->where('data_not_synced',$order->test_status)->where('specimen_id',$order->test_id)->first();
					$unsync->sync_status = "synced";
					$unsync->save();
					dd($res);
				}


			}; 

		}
		
				



	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
