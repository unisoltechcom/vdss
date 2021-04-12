<?php
/*//step1
$cSessiona = curl_init(); 
//step2
curl_setopt($cSessiona,CURLOPT_URL,"http://valleydrivertraining.ca/virtuemartusers.php?password=546872Whsgf67hGfzs");
curl_setopt($cSessiona, CURLOPT_FRESH_CONNECT, true);
curl_setopt($cSessiona,CURLOPT_RETURNTRANSFER,true);
curl_setopt($cSessiona,CURLOPT_HEADER, 0); 
curl_setopt($cSessiona, CURLOPT_VERBOSE, true);
//step3
$result=curl_exec($cSessiona);
//step4

curl_close($cSessiona);
//step5
print_r( $result);*/
function query_inserted($table, $data){
			$query = "INSERT INTO `".$table."` ";
			$k = '';
			$v = '';
			foreach ($data as $key => $val)
			{
				$k .= $key.', ';
				if($val == '')
					$v .= "'', ";
					else if(strtolower($val) == 'null')
					$v .= 'NULL, ';
					else if(strtolower($val) == 'now()')
					$v .= 'NOW(), ';
				else
					$v .= "'".($val)."', ";
			}
			$query .= "(".rtrim($k, ', ').") VALUES (".rtrim($v, ', ').");";
			return $query;
		}

function query_updated($table, $data, $where ='1')
		{   
			$q = "UPDATE `".$table."` SET "; 
			foreach($data as $key=>$val)
			{   
				if(strtolower($val)=='null' ) $q .= "`$key`='NULL', ";
				else if(strtolower($val)=='now()') $q .= "`$key`=NOW(), ";
				else $q .= "`$key`='".$val."', ";
			}
		   $q = rtrim($q, ', ')." WHERE ".$where.';';
		   return $q;
		}  
		
function get_remote_data($url, $post_paramtrs = false) {
    $c = curl_init();
    curl_setopt($c, CURLOPT_URL, $url);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    if ($post_paramtrs) {
        curl_setopt($c, CURLOPT_POST, TRUE);
        curl_setopt($c, CURLOPT_POSTFIELDS, "var1=bla&" . $post_paramtrs);
    } curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; rv:33.0) Gecko/20100101 Firefox/33.0");
    curl_setopt($c, CURLOPT_COOKIE, 'CookieName1=Value;');
    curl_setopt($c, CURLOPT_MAXREDIRS, 10);
    $follow_allowed = ( ini_get('open_basedir') || ini_get('safe_mode')) ? false : true;
    if ($follow_allowed) {
        curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
    }curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 9);
    curl_setopt($c, CURLOPT_REFERER, $url);
    curl_setopt($c, CURLOPT_TIMEOUT, 60);
    curl_setopt($c, CURLOPT_AUTOREFERER, true);
    curl_setopt($c, CURLOPT_ENCODING, 'gzip,deflate');
    $data = curl_exec($c);
    $status = curl_getinfo($c);
    curl_close($c);
    preg_match('/(http(|s)):\/\/(.*?)\/(.*\/|)/si', $status['url'], $link);
    $data = preg_replace('/(src|href|action)=(\'|\")((?!(http|https|javascript:|\/\/|\/)).*?)(\'|\")/si', '$1=$2' . $link[0] . '$3$4$5', $data);
    $data = preg_replace('/(src|href|action)=(\'|\")((?!(http|https|javascript:|\/\/)).*?)(\'|\")/si', '$1=$2' . $link[1] . '://' . $link[3] . '$3$4$5', $data);
    if ($status['http_code'] == 200) {
        return $data;
    } elseif ($status['http_code'] == 301 || $status['http_code'] == 302) {
        if (!$follow_allowed) {
            if (empty($redirURL)) {
                if (!empty($status['redirect_url'])) {
                    $redirURL = $status['redirect_url'];
                }
            } if (empty($redirURL)) {
                preg_match('/(Location:|URI:)(.*?)(\r|\n)/si', $data, $m);
                if (!empty($m[2])) {
                    $redirURL = $m[2];
                }
            } if (empty($redirURL)) {
                preg_match('/href\=\"(.*?)\"(.*?)here\<\/a\>/si', $data, $m);
                if (!empty($m[1])) {
                    $redirURL = $m[1];
                }
            } if (!empty($redirURL)) {
                $t = debug_backtrace();
                return call_user_func($t[0]["function"], trim($redirURL), $post_paramtrs);
            }
        }
    } return "ERRORCODE22 with $url!!<br/>Last status codes<b/>:" . json_encode($status) . "<br/><br/>Last data got<br/>:$data";
}
$data =  array();
$rand = rand();
$url = "http://valleydrivertraining.ca/virtuemartuser.php?v=$rand";
$result = json_decode(get_remote_data($url));
//print_r($result);exit;
$rows=array();
$servername = "localhost";
$username = "valleydr_valley2";
$password = "hT8Wru5VD2";
$dbname = "valleydr_driving";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

foreach($result as $rw){
$array = array();
$name='';	
	$name = $rw->last_name;
$check=$conn->query("SELECT COUNT(*) as total FROM `student_information` WHERE LastName = '$name' LIMIT 1");
//print_r( $check->fetch_assoc());
//print_r( $check->fetch_assoc()['total']);
if( $check->fetch_assoc()['total'] == 0)
    {   
	    $payment = number_format($rw->payment, 2, '.', '');
	    $installment = serialize(array('installment'=> $payment ,'paid1'=> 'credit card' ));
		$array = array('LastName'=> $rw->last_name,'FirstName'=> $rw->first_name,'Phone'=> $rw->phone_1 ? $rw->phone_1 : $rw->phone_2,'Address'=> $rw->address_1,'City'=> $rw->city,'PostalCode'=> $rw->zip , 'StudentEmail' => $rw->email,'CourseFee'=> $payment, 'Installment' => $installment );
		$query = query_inserted("student_information", $array);	
		$check1 = $conn->query($query);
	 if($check1->num_rows == 0)
     { $userid = $rw->virtuemart_order_userinfo_id;
		$rand = rand();
		$url = "http://valleydrivertraining.ca/virtuemartuser.php?v=$rand&userid=$userid";		
		get_remote_data($url);
		$data['result']='true';
	 }
	}
	 else {
		$userid = $rw->virtuemart_order_userinfo_id;
		$rand = rand();
		$url = "http://valleydrivertraining.ca/virtuemartuser.php?v=$rand&userid=$userid";		
		get_remote_data($url);
		$data['result']='false';
	 }
}



print json_encode($data);
exit;
?>
<!--?userid=50&password=546872Whsgf67hGfzs-->