<?php
include('connection.php');


$username = $_POST['username'];

$password = $_POST['password'];
$first_name = $_POST['F_Name'];
$last_name = $_POST['L_Name'];

$check_username = $mysqli->prepare('select username from users where username=?');
$check_username->bind_param('s', $username);
$check_username->execute();
$check_username->store_result();
$username_exists = $check_username->num_rows();

if ($username_exists == 0) {
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $query = $mysqli->prepare('insert into users(username,password,F_Name,L_Name) values(?,?,?,?)');
    $query->bind_param('ssss', $username, $hashed_password, $first_name, $last_name);
    $query->execute();

    $response['status'] = "success";
    $response['message'] = "Successfully created account";
} else {
    $response['status'] = "failed";
    $response['message'] = "Username already exists";
}

echo json_encode($response);
