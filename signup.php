<?php
include('connection.php');

$username = $_POST['username'];
$password = $_POST['password'];
$first_name = $_POST['F_Name'];
$last_name = $_POST['L_Name'];

$check_username = $mysqli->prepare('SELECT username FROM users WHERE username = ?');
$check_username->bind_param('s', $username);
$check_username->execute();
$check_username->store_result();
$username_exists = $check_username->num_rows();

$response = array();

if ($username_exists == 0) {
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $query = $mysqli->prepare('INSERT INTO users (username, password, F_Name, L_Name) VALUES (?, ?, ?, ?)');
    $query->bind_param('ssss', $username, $hashed_password, $first_name, $last_name);
    $query->execute();

    $response['success'] = true;
    $response['message'] = "Successfully created account";
} else {
    $response['success'] = false;
    $response['message'] = "Username already exists";
}

echo json_encode($response);
?>
