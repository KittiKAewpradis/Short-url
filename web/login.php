<?php 
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
session_start();

if(isset($_POST['username'])){

    $curl = curl_init();
    
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://wac.haadthip.com/ldap_if/logincheck',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>'{
        "username":"'.$_POST['username'].'",
        "password":"'.$_POST['password'].'",
        "system":"aka"
    }',
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
      ),
    ));
    
    $response = curl_exec($curl);
    $result = json_decode($response, true);
    
    curl_close($curl);

    if($result["status"] === "success"){
        $_SESSION["username"]   = $result["ad_username"];
        $_SESSION["name"]       = $result["name"];
        $_SESSION["sname"]      = $result["surname"];
        $_SESSION["empid"]      = $result["emp_id"];
        $_SESSION["mail"]       = $result["mail"];
        Header("Location: links");
    }else{
        echo '
            <script>
                alert("'.$result["desc"].'");
                window.location.replace("welcome");
            </script>
        ';
    }
    
}else{
        echo '
        <script>
            alert("user & password incorrect login again");
            window.location.replace("welcome");
        </script>
        ';
}
?>