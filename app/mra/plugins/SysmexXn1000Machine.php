<?php
namespace KBLIS\Plugins;

class SysmexXn1000Machine extends \KBLIS\Instrumentation\AbstractInstrumentor
{
    /**
     * Returns information about an instrument
     *
     * @return array('name' => '', 'description' => '', 'testTypes' => array())
     */
    public function getEquipmentInfo()
    {
        return array(
            'code' => 'SysmexXn1000',
            'name' => 'Sysmex Xn1000',
            'description' => 'Automatic analyzer with 22 parameters Hematology Analyzer',
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

        $RESULTS_STRING = file_get_contents($DUMP_URL);
        if ($RESULTS_STRING === FALSE){
            print "Something went wrong with getting the File";

            return;
        };

        $results = array();

        $json = json_decode($RESULTS_STRING, true);

        foreach($json[$specimen_id] as $key => $RESULT) {

            $results[$key] = $RESULT;

        }

        return $results;

    }

}
