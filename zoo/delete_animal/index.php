<?php

header('Content-Type: application/json');

// echo 'Test'

$IdSoignant;
$IdAnimal = (isset($_GET['id']) && !empty($_GET['id'])) ? intval($_GET['id']) : false;

// $id = (isset($_GET['id']) && !empty($_GET['id'])) ? intval($_GET['id']) : false;

if($IdAnimal)
{
    $headers = apache_request_headers();

    $ApiKeySoignant = (isset($headers['apikey']) && !empty($headers['apikey'])) ? $headers['apikey'] : false;
    // var_dump($ApiKeySoignant);
    if($ApiKeySoignant){

        require_once '../pdo.php';
    
        $sql = "SELECT admin from soignant WHERE apikey = :apikey";
        $IdSoignant = $pdo->prepare($sql);
        $IdSoignant->execute(['apikey' => $ApiKeySoignant]);
        $result = $IdSoignant->fetch(PDO::FETCH_ASSOC);
        
        $sql2 = "SELECT name from animals WHERE id = :id";
        $req = $pdo->prepare($sql2);
        $req->execute(['id' => $IdAnimal]);
        $result2 = $req->fetch(PDO::FETCH_ASSOC);
        
        var_dump($result2['name']);
        $NameAnimal = $result2['name'];

        if($result['admin'] == 1){
            // echo "IS ADMIN";
        $sql = "DELETE FROM animals WHERE id=?";
        $stmt= $pdo->prepare($sql);
        $stmt->execute([$IdAnimal]);
        $data['succes'] = ['success' => 'success', 'message' => "Animal '$NameAnimal' has been deleted :-)"];
        echo json_encode($data); 
        }else{
            echo json_encode($data=
            [
                'succes'=> false,
                'message'=> "This person does not have the rights to DELETE"
            ]);
        }
    }else{
        echo json_encode($data = 
        [
            'success' => false,
            'message' => "No such api key |:-( "
        ]);
    }

}

?>