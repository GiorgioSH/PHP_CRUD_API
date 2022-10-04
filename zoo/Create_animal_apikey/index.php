<?php 

$headers = apache_request_headers();

$ApiKeySoignant = (isset($headers['apikey']) && !empty($headers['apikey'])) ? $headers['apikey'] : false;

if($ApiKeySoignant){
    require_once('../pdo.php');
    // echo "SELECT admin,id from soignant WHERE apikey = :apikey";
    $sql = "SELECT admin,id from soignant WHERE apikey = :apikey ;";
    $req = $pdo->prepare($sql);
    $req->execute(["apikey" => $ApiKeySoignant]);
    $IsAdmin = $req->fetch(PDO::FETCH_ASSOC);

    if($IsAdmin['admin'] == 1){
        var_dump("is admin = true");
        $AnimalName = (isset($_POST['name']) && !empty($_POST['name'])) ? $_POST['name'] : false ;
        $AnimalBirthday = (isset($_POST['birthday']) && !empty($_POST['birthday'])) ? $_POST['birthday'] : false ;
        $AnimalEnclos_id = (isset($_POST['enclos_id']) && !empty($_POST['enclos_id'])) ? intval($_POST['enclos_id']) : false ;
        $AnimalType_id = (isset($_POST['type_id']) && !empty($_POST['type_id'])) ? intval($_POST['type_id']) : false ;

        $SqlCreate = "INSERT INTO animals (name, birthday, enclos_id, type_id) 
                            VALUES (?,?,?,?)";
        $stmt= $pdo->prepare($SqlCreate);
        $stmt->execute([$AnimalName,$AnimalBirthday,$AnimalEnclos_id,$AnimalType_id]);
        var_dump($stmt);
        $data['id'] = [$pdo->lastInsertId()];
        $data['succes'] = ['success' => 'success', 'message' => "New animal created : '$AnimalName'"];

    }else{
        var_dump("is admin = false");
        $data['success'] = ['success' => 'failed', 'message' => "This user dont have the right to create an animal"];
       
    }

}else{
    $data = 
    [
        'success' => false,
        'message' => "No such api key |:-( "
    ];
}

echo json_encode($data);

?>