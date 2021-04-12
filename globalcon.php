<?php
//SYSTEM SETTINGS
$base_url = 'http://' . $_SERVER['SERVER_NAME'];
$signin_url = substr($base_url . $_SERVER['PHP_SELF'], 0, -(6 + strlen(basename($_SERVER['PHP_SELF']))));

//DO NOT CHANGE
$ip_address = $_SERVER['REMOTE_ADDR'];

 $months = array(
    '01'=>'January',
    '02'=>'February',
    '03'=>'March',
    '04'=>'April',
    '05'=>'May',
    '06'=>'June',
    '07'=>'July ',
    '08'=>'August',
    '09'=>'September',
    '10'=>'October',
    '11'=>'November',
    '12'=>'December',
);

function dateformat($date){
	$months = array(
    '01'=>'January',
    '02'=>'February',
    '03'=>'March',
    '04'=>'April',
    '05'=>'May',
    '06'=>'June',
    '07'=>'July ',
    '08'=>'August',
    '09'=>'September',
    '10'=>'October',
    '11'=>'November',
    '12'=>'December',
);
	$dateformt = explode("/",$date);
	if($dateformt[1]>12) {
		$dateform1 = $months[$dateformt[0]].' '.$dateformt[1].' '.$dateformt[2];
		} else {
		$dateform1 = $months[$dateformt[1]].' '.$dateformt[0].' '.$dateformt[2];	
			}
	
	 
	return $dateform1;
}


function formatMoney($number, $fractional=false) { 
    if ($fractional) { 
        $number = sprintf('%.2f', $number); 
    } 
    while (true) { 
        $replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1,$2', $number); 
        if ($replaced != $number) { 
            $number = $replaced; 
        } else { 
            break; 
        } 
    } 
    return $number; 
}