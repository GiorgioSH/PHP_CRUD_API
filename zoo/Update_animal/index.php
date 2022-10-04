<?php

header('Content-Type: application/json');

$data = [];
$result = [];
$animalName = "";

$_PATCH = json_decode(file_get_contents('php://input'));
    if (isset($_GET['id']) && $_GET['id']!="" && is_numeric( $_GET['id'])) {
        if (!is_null($_PATCH) && count($_PATCH)> 0){
            if (isset($_PATCH->name) && $_PATCH->name!=""){
                $data['name'] = $_PATCH->name;
                $data['birthday'] = $_PATCH->birthday;
                $data['enclos_id'] = $_PATCH->enclos_id;
                $data['type_id'] = $_PATCH->type_id;
                $data['id'] = $_GET['id'];
                $animalName = $data['name'];
            }
        }
    }else{
        echo json_encode($result['success'] = 
        ['success' => 'failed', 'message' => "Wrong Id/enter an ID please !"]);
    }

    //Check if soignat has rights to update 

$headers = apache_request_headers();

$ApiKeySoignant = (isset($headers['apikey']) && !empty($headers['apikey'])) ? $headers['apikey'] : false;

if($ApiKeySoignant){
    require_once '../pdo.php';

    $sql = "SELECT admin from soignant WHERE apikey = :apikey";
    $IdSoignant = $pdo->prepare($sql);
    $IdSoignant->execute(['apikey' => $ApiKeySoignant]);
    $result = $IdSoignant->fetch(PDO::FETCH_ASSOC);
    
    if($result['admin'] == 1){
        // echo "IS ADMIN";
    $sql = "UPDATE animals SET name=?, birthday=?, enclos_id=?, type_id=? WHERE id= ?";
    $stmt= $pdo->prepare($sql);
    $stmt->execute([$data['name'], $data['birthday'], $data['enclos_id'], $data['type_id'], $data['id']]);
    $data['succes'] = ['success' => 'success', 'message' => "Animal '$animalName' has been updated :-)"];
    echo json_encode($data); 
    }else{
        echo json_encode($data=
        [
            'succes'=> false,
            'message'=> "This person does not have the rights to UPDATE"
        ]);
    }
}else{
    echo json_encode($data = 
    [
        'success' => false,
        'message' => "No such api key |:-( "
    ]);
}

?>