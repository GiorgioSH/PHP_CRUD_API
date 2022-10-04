<?php 

header('Content-Type: application/json');

$data = [];

$id = (isset($_GET['id']) && !empty($_GET['id'])) ? intval($_GET['id']) : false;

if( $id )
{
    require_once '../pdo.php';
        //Select One animal
    $sql = "SELECT * FROM animals WHERE id = :id";
    $req = $pdo->prepare($sql);
    $req->execute(['id' => $id]);


    $result = $req->fetch(PDO::FETCH_ASSOC);
        //Select the type_id to get the name of "soignant" of the animal

    $sql2 = "SELECT * FROM soignant WHERE type_id = :type_id";
    $req2 = $pdo->prepare($sql2);
    $req2->execute(['type_id' => $result['type_id']]);
    $result2 = $req2 ->fetch(PDO::FETCH_ASSOC);

    $data['animal'] = $result;
    $data['soignant'] = $result2;
    
}else{
    $sql = "SELECT * FROM animals";
    $req = $pdo->prepare($sql);
    $req->execute();
    $result = $req->fetchAll(PDO::FETCH_ASSOC);
    $data['animal'] = $result;
 
}

echo json_encode($data);  
?>