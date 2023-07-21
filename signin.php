<?php
include('connection.php');

$username = $_POST['username'];
$password = $_POST['password'];

$query = $mysqli->prepare('select ID,username,password,F_Name,L_Name
from users 
where username=?');
$query->bind_param('s', $username);
$query->execute();

$query->store_result();
$query->bind_result($id, $username, $hashed_password, $F_Name, $L_Name);
$query->fetch();

$num_rows = $query->num_rows();
if ($num_rows == 0) {
    $response['status'] = "user not found";
} else {
    if (password_verify($password, $hashed_password)) {
        $response['status'] = 'logged in';
        $response['user_id'] = $id;
        $response['first_name'] = $F_Name;
        $response['last_name'] = $L_Name;
        $response['username'] = $username;
    } else {
        $response['status'] = "wrong password";
    }
}
echo json_encode($response);
