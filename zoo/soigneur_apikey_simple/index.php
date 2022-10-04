<?php

$headers = apache_request_headers();

$ApiKeySoignant = (isset($headers['apikey']) && !empty($headers['apikey'])) ? $headers['apikey'] : false;
// var_dump($ApiKeySoignant);
if($ApiKeySoignant){

    require_once('../pdo.php');

    $sql = "SELECT type_id from soignant WHERE apikey = :apikey";
    $IdSoignant = $pdo->prepare($sql);
    $IdSoignant->execute(['apikey' => $ApiKeySoignant]);
    $result = $IdSoignant->fetch(PDO::FETCH_ASSOC);
    
    var_dump($result);

    $sql2 = "SELECT * from animals WHERE type_id = :temp";
    $req = $pdo ->prepare($sql2);
    $req ->execute(['temp' => $result['type_id']]);
    $data = $req->fetchAll(PDO::FETCH_ASSOC);

}else{
    $data = 
    [
        'success' => false,
        'message' => "No such api key |:-( "
    ];
}

echo json_encode($data);

?>