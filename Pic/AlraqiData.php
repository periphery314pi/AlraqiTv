<?php
include 'configs.php';
require 'vendor/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

$App_Sha1Key = '61:ed:37:7e:85:d3:86:a8:df:ee:6b:86:4b:d8:5b:0b:fa:a5:af:81';
$secretKey = 'T05486O59920FI';
$secretKey2 = 'MAFITOFIPLAYMARW';
$headers = getallheaders(); 
$Ref = '';
if ($headers !== null && isset($headers['User-Agent'])&&isset($headers['metadata'])&&isset($headers['ref'])) {
      $userAgent = $headers['User-Agent'];
      $requestSha = decrypt($headers['metadata'],$secretKey2);
      $Ref = $headers['ref'];
      if(strpos($userAgent, 'tofi') === 0&&$requestSha==$App_Sha1Key){
         
header('Status_msg: Success');
header('key:'.$randomString);
header('thcdn2:'.$secretKey);
header('Content-Type: application/json');

$firebase = (new Factory)->withServiceAccount($keey)->withDatabaseUri('https://alraqitv-v3-default-rtdb.firebaseio.com');

$database = $firebase->createDatabase();

/*foreach ($headers as $key => $value) {
    echo "$key: $value\n";
}
*/
try {
$data_page = $database->getReference($Ref)->getSnapshot()->getValue();

$json = '{"":{}}';
if ($data_page !== null) {
$json =json_encode($data_page, JSON_PRETTY_PRINT);
}

} catch (\Kreait\Firebase\Exception\Auth\AuthenticationError $e) {
     echo encrypt($json, $secretKey);// 'Authentication error: ' . $e->getMessage();
} catch (\Kreait\Firebase\Exception\DatabaseException $e) {
    echo encrypt($json, $secretKey);//  'Database error: ' . $e->getMessage();
} catch (Exception $e) {
    echo encrypt($json, $secretKey);//  'Error: ' . $e->getMessage();
}
$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
$randomString = substr(str_shuffle($characters), 0, 12); // Randomly shuffle and take 12 characters


echo encrypt($json, $secretKey);
}else{
  hackerResponse();  
}
}else{
    showInvalidResponse();
}
function showInvalidResponse(){
    header_remove();
header('Content-Type: application/json');
header('X-Frame-Options: DENY');

// Ensure headers are sent and not cached by the browser
header('Expires: 0');
header('Pragma: no-cache');
header('X-Error-Message: Invalid request data');
echo '{"error_msg":"Invalid request"}';

}
   function hackerResponse(){
       header_remove();
       header('Content-Type: application/json');
       header('X-ERROR-Message: Unknown Error type');
       echo 'Something went wrong, Unknown error occurred';
   }
   function decrypt($enc, $key) {
    // Decode base64-encoded string
    $decodedString = base64_decode($enc);

    $result = '';
    $keyLength = strlen($key);

    // XOR each character with the key
    for ($i = 0; $i < strlen($decodedString); $i++) {
        $result .= chr(ord($decodedString[$i]) ^ ord($key[$i % $keyLength]));
    }

    return $result;
}
     function encrypt($input, $key) {
    $result = '';
    
    // Apply XOR operation with the key
    for ($i = 0; $i < strlen($input); $i++) {
        $result .= chr(ord($input[$i]) ^ ord($key[$i % strlen($key)]));
    }

    // Convert the result to a Base64 encoded string
    return base64_encode($result);
}

    ?>