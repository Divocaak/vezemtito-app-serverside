<?php
require_once "config.php";
$_POST = json_decode(file_get_contents("php://input"), true);

$toRet;
$sql = "SELECT is_admin, id_status FROM user WHERE id='" . $_POST["id"] . "';";
if ($result = mysqli_query($link, $sql)) {
    // TODO zpracovat devId
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_row($result)) {
            $toRet = [
                "isAdmin" => intval($row[0]),
                "idStatus" => intval($row[1])
            ];
        }
    } else {
        $sql = "INSERT INTO user (id) VALUES ('" . $_POST["id"] . "');";
        if (!mysqli_query($link, $sql)) {
            http_response_code(400);
            $toRet = [
                "message" => "Při registraci se stala chyba.",
                "sql" => $sql,
                "error" => mysqli_error($link)
            ];
        } else {
            $toRet = [
                "isAdmin" => 0,
                "idStatus" => 1
            ];
        }
    }
    
    mysqli_free_result($result);
} else{
    http_response_code(503);
    $toRet = [
        "message" => "Někde se stala chyba, zkuste to prosím později",
        "sql" => $sql,
        "error" => mysqli_error($link)
    ];
}

mysqli_close($link);
echo json_encode($toRet);
?>