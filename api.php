<?php
//API USES MARIADB 10 compatible queries, may not work with older mySQL databases.
require_once 'db_cfg.php';

//CONNECT TO DB
$con = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
$response = array();  

//HELPER FUNCTION FOR ERROR RESPONSE
function exitApi($code, $status) {
	$response['result'] = 'error'; 
	$response['code'] = $code; 
	$response['info'] = $status;
	echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);	
	exit();
}

//EXIT WITH IF CONNECTION FAILED
if(mysqli_connect_errno())
{
	
	exitApi(1, '1: Connection failed');
}

//SINGLE REQUEST VARIABLE FOR ANY REQUEST METHOD
switch($_SERVER['REQUEST_METHOD'])
{
case 'GET': $rq = &$_GET; $method = 'GET'; break;
case 'PUT': $method = 'PUT'; parse_str(file_get_contents('php://input'), $rq); break;
default: exitApi(2, '2: Bad request method');
}

//CHECK THAT ALL REQUIRED DATA IS SENT
if (!isset( $rq['user_id']) || !isset( $rq['service_id'])) {
	exitApi(3, '3: Not all details provided');
}

//CHECK THAT DATA IS VALID
if (!is_numeric($rq['user_id']) && !is_numeric($rq['service_id'])) {
	exitApi(4, '4: Non-numeric IDs');
} else {
	$user_id = $rq['user_id'];
	$service_id = $rq['service_id'];
	
//SECURITY CHECK	
$securityquery = "SELECT * FROM services WHERE ID='" . $service_id . "' AND user_id='" . $user_id . "';";	
$securitycheck = mysqli_query($con, $securityquery) or exitApi(5,'5: SECURITY query failed');
if(mysqli_num_rows($securitycheck) < 1) 
{
	exitApi(6, '6: service_id/user_id PAIRS not found');
} 	
}

//QUERY RELATED TARIFS FROM DB
if($method == 'GET') {
$servicesquery = "SELECT tarif_id FROM services WHERE ID='" . $service_id . "';";	
$servicescheck = mysqli_query($con, $servicesquery) or exitApi(5,'5: SERVICES query failed');
if(mysqli_num_rows($servicescheck) < 1) 
{
	exitApi(6, '6: Requested SERVICES not found');
} else {
	$service = mysqli_fetch_assoc($servicescheck);
	$tarif_id = $service['tarif_id'];
}

$tgroupquery = "SELECT tarif_group_id FROM tarifs WHERE ID='" . $tarif_id . "';";
$tgroupcheck = mysqli_query($con, $tgroupquery) or exitApi(5,'5: TARIFS GROUP query failed');
if(mysqli_num_rows($tgroupcheck) < 1) 
{
	exitApi(6, '6: Requested TARIFS GROUP not found');
} else {
	$tgroup = mysqli_fetch_assoc($tgroupcheck);
	$tarif_group_id = $tgroup['tarif_group_id'];
}

$tarifsquery = "SELECT * FROM tarifs WHERE tarif_group_id='" . $tarif_group_id . "';";

$tarifcheck = mysqli_query($con, $tarifsquery) or exitApi(5,'5: TARIFS query failed');
if(mysqli_num_rows($tarifcheck) < 1) 
{
	exitApi(6, '6: Requested TARIFS not found');
} else {
	//$tarif = mysqli_fetch_assoc($tarifcheck);
	$tarifs = mysqli_fetch_all($tarifcheck, MYSQLI_ASSOC);
	mysqli_free_result($tarifs);
	$timezone = "+0300"; //IF IT'S USER TIMEZONE - DESCRIBE THE PROCESS OF ACQUIRING IT
}
//SEEMS LIKE THERE WERE NO ERRORS, RETURN RESULT OK AND TARIFS
$response['result'] = 'ok'; 
$response['tarifs']['title'] = $tarifs[0]['title']; 
$response['tarifs']['link'] = $tarifs[0]['link']; 
$response['tarifs']['speed'] = $tarifs[0]['speed']; 
$today = strtotime(date("Y-m-d"));
foreach($tarifs as &$t) {
    unset($t['link']);
	unset($t['tarif_group_id']);
	$newpayday = strtotime(date("Y-m-d", strtotime("+".$t['pay_period']." month", $today))).$timezone;
	$t['new_payday'] = $newpayday;
}
unset($t); // unset reference
$response['tarifs']['tarifs'] = $tarifs; 
echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);	
exit();
}

//SET CHOSEN TARIF IN USER DB
if($method == 'PUT') {	

if(!isset($rq['tarif_id']) || !is_numeric($rq['tarif_id'])) {
	exitApi(4, '4: Non-numeric IDs');
} else {
	$tarif_id = $rq['tarif_id'];
}

$servicesquery = "SELECT * FROM services WHERE ID='" . $service_id . "';";
$servicescheck = mysqli_query($con, $servicesquery) or exitApi(5,'5: SERVICES query failed'); 

if (mysqli_num_rows($servicescheck) < 1) 
{
	exitApi(6, '6: Requested SERVICE not found');
} else {
	$service = mysqli_fetch_assoc($servicescheck);
}

$tarifquery = "SELECT pay_period FROM tarifs WHERE ID='" .$tarif_id. "';";
$tarifcheck = mysqli_query($con, $tarifquery) or exitApi(5,'5: TARIF query failed'); 

if(mysqli_num_rows($tarifcheck) < 1) 
{
	exitApi(6, '6: Requested TARIF not found');
} 
else {
	$tarif = mysqli_fetch_assoc($tarifcheck);
}
$timezone = "+0300"; //IF IT'S USER TIMEZONE - DESCRIBE THE PROCESS OF ACQUIRING IT
$today = strtotime(date("Y-m-d"));
$newpayday = strtotime(date("Y-m-d", strtotime("+".$tarif['pay_period']." month", $today)));
$updatequery = "UPDATE services SET tarif_id = ".$tarif_id.", payday = FROM_UNIXTIME(".$newpayday.") WHERE ID='" . $service_id . "';";
$update = mysqli_query($con, $updatequery) or exitApi(7,'7: UPDATE services query failed'); 
//SEEMS LIKE THERE WERE NO ERRORS, RETURN RESULT OK
$response['result'] = 'ok'; 
echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);	
exit();
}
?>