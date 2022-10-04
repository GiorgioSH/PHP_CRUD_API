<?php 

$id = (isset($_GET['id']) && !empty($_GET['id'])) ? intval($_GET['id']) : false;

$data = [];

if( $id )
{
    require_once('../pdo.php');
    //Select One soignant
    $sql = "SELECT * FROM soignant WHERE id = :id";
    $req = $pdo->prepare($sql);
    $req->execute(['id' => $id]);

    $message_error = "Aucun soignant avec id =#{$id}";
    $result = $req->fetch(PDO::FETCH_ASSOC);
    $data['soignant'] = $result;

    $sql2 = "SELECT * from animals WHERE type_id = :type_id";
    $req2 = $pdo ->prepare($sql2);
    $req2 ->execute(['type_id' => $result['type_id']]);
    $result2 = $req2->fetchAll(PDO::FETCH_ASSOC);
    //var_dump($result2);
    $data['animals'] = $result2;


}else{
    //Select All soignant
    $req = $pdo->query('SELECT * FROM animals');
    $result = $req->fetchAll(PDO::FETCH_ASSOC);
    $data = $result;
    // $message_error = "";
}

echo json_encode($data);  

?>