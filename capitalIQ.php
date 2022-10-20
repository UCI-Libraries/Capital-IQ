<?php
/**
 ** Refactored code to make S&P Cap IQ 
 **/

//error_log(__FILE__);
define('JSON_FILE', $_SERVER['DOCUMENT_ROOT'] . '/capiqLinks.json');

#=============================================================
# functions 
#=============================================================

/**
 * create the json file
 */ 
function initializeFile() {
    file_put_contents(JSON_FILE , json_encode( [
        'uciLib1' => '', 'uciLib2' => '', 'uciLib3' => '', 
        'uciLib4' => '', 'uciLib5' => '', 'uciLib6' => '',
        'uciLib7' => '', 'uciLib8' => '', 'uciLib9' => '', 
        'uciLib10' => '']) 
    );
}// initializeFile()


/**
 * 2021.03.02 - check and clear the timestamp to
 * decouple the process from cron task to execute
 * clearLinks.php.
 *
 * @param array $jsonArray
 * @return string $json
 */
function getClicks($jsonArray) {
    error_log(__METHOD__);
    foreach( $jsonArray as $key => $value ) {
        if ( !empty($value) ) {
            $dateClicked = new DateTime($value);
            $dateNow = new DateTime('now');

            $timeDiff = date_diff($dateClicked, $dateNow);

            // incorrect usage of DateTimeInterval--
            // cannot just compare the minute part and expect things
            // to function correct. the whole must be account for or
            // things will break down on static old dates.  
            $sum = $timeDiff->y + $timeDiff->m + $timeDiff->d + $timeDiff->h;
            //error_log('$sum: ' . $sum);
            
            //if ( $timeDiff->format('%I') >= 30 ) {
            if ( ! empty($sum) || $timeDiff->i >= 30 ) {
                // reset clicked value    
                $jsonArray[$key] = '';
            } //fi

        }//fi
    }//foreach    

    $data = json_encode($jsonArray);
    file_put_contents(JSON_FILE, $data);

    return $data;
}// clearClickTrac()

/**
 * store the linked clicked in the json file with date-time
 *
 * @param string $userLink
 * @param array $jasonArray
 */
function setClick($userLink, $jsonArray) {
    error_log(__METHOD__ . ' $userLink: ' . $userLink);
    foreach( $jsonArray as $key => $value ) {
        error_log('  - $key: ' . $key);
        if ( "$key" == "$userLink" ) {
            $jsonArray[$key] = date('Y-m-d H:i:s');
        }
    }//fi

    file_put_contents(JSON_FILE, json_encode($jsonArray));
}// setClick()

#=============================================================
# main
#=============================================================
// 2021.01.26 create json file if not exists
if(file_exists(JSON_FILE)) {
    $jsonArray = json_decode(file_get_contents(JSON_FILE), true);
    if($jsonArray == null){
        initializeFile();
    }
} else {    
    initializeFile();
}

$jsonArray = json_decode(file_get_contents(JSON_FILE), true);
$userLink  = $_REQUEST['userLink'] ? $_REQUEST['userLink'] : '';


// click action by detecting URL query string variables
if( $userLink && !empty($userLink) ) {
    setClick($userLink, $jsonArray);
} else {
    //echo contents click log file
    header('Content-type: application/json');
    header('Access-Control-Allow-Origin: *');
    echo getClicks($jsonArray);
}//efi
