<?php

$user = $_REQUEST["name"];
$email = $_REQUEST["email"];
$message = $_REQUEST["message"];
//$phone = $_REQUEST["phone"];

echo "user: " . $user . "<br>";
echo "email: " . $email . "<br>";
echo "message: " . $message . "<br>";
//echo "phone: " . $phone . "<br>";

// Email validation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        list($user, $domain) = explode('@', $email);
        if (checkdnsrr($domain, 'MX')) {
            echo "Valid Email with a working domain.<br>";
        } else {
            echo "Invalid Email Domain.<br>";
        }
    } else {
        echo "Invalid Email Format.<br>";
    }
}

   // Phone validation
   /*if (!empty($phone)) {
    if (ctype_digit($phone) && strlen($phone) >= 7 && strlen($phone) <= 15) {
        echo "Valid phone number. <br>";
    } else {
        echo "Invalid phone number.<br>";
    }
} else {
    echo "Phone number is required.<br>";
}*/


?>