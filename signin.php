<?php
include('connection.php');

$username = $_POST['username'];
$password = $_POST['password'];

$query = $mysqli->prepare('SELECT ID, username, password, F_Name, L_Name
FROM users
WHERE username = ?');
$query->bind_param('s', $username);
$query->execute();

$query->store_result();
$query->bind_result($id, $username, $hashed_password, $F_Name, $L_Name);
$query->fetch();

$num_rows = $query->num_rows();
if ($num_rows == 0) {
    $response = array(
        'success' => false,
        'message' => 'User not found',
    );
} else {
    if (password_verify($password, $hashed_password)) {
        $response = array(
            'success' => true,
            'message' => 'Logged in',
            'user_id' => $id,
            'first_name' => $F_Name,
            'last_name' => $L_Name,
            'username' => $username,
        );
    } else {
        $response = array(
            'success' => false,
            'message' => 'Wrong password',
        );
    }
}

echo json_encode($response);
?>
