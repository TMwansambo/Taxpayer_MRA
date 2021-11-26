<?php
namespace KBLIS\Plugins;

class  SysmexXp300 extends \KBLIS\Instrumentation\AbstractInstrumentor
{
    /**
     * Returns information about an instrument
     *
     * @return array('name' => '', 'description' => '', 'testTypes' => array())
     */
    public function getEquipmentInfo()
    {
        return array(
            'code' => ' SysmexXP300',
            'name' => 'sysmex-xp-300-017',
            'description' => 'Automatic Analyzer for Full Blood Count (FBC)',
            'testTypes' => array("FBC")
        );
    }


    /**
     * Fetch Test Result from machine and format it as an array
     *
     * @return array
     */
public function getResult($specimen_id = 0,$tracking_number = 0)
    {

        /*
        * 1. Read result file stored on the local machine (Use IP/host to verify that I'm on the correct host)
        * 2. Parse the data
        * 3. Return an array of key-value pairs: measure_name => value
        */

        $base = realpath(".");

        $remote_ip = ''; //$this->ip . '/';

        $DUMP_URL = "$base/data/$remote_ip$specimen_id.json";
        $results = array();
        $RESULTS_STRING = TRUE;
        try {
            $RESULTS_STRING = file_get_contents($DUMP_URL);                 
        } catch (\Exception $e) {
            $RESULTS_STRING = FALSE;
        }

        if ($RESULTS_STRING === FALSE){

            $DUMP_URL_ = "$base/data/$remote_ip$tracking_number.json";
            $RESULTS_STRING = file_get_contents($DUMP_URL_);

            if ($RESULTS_STRING === FALSE){
                print "Something went wrong with getting the File";
                return;
            }else{
                $json = json_decode($RESULTS_STRING, true);

                foreach($json[$tracking_number] as $key => $RESULT) {
        
                    $results[$key] = $RESULT;
        
                }
            }
        }else
        {
            $json = json_decode($RESULTS_STRING, true);

            foreach($json[$specimen_id] as $key => $RESULT) {
    
                $results[$key] = $RESULT;
    
            }
        };       

				if (!empty($json["machine_name"])){
					$results["machine_name"] = $json["machine_name"];
				}

        return $results;

    }

}
