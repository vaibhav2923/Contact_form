<?php
$host = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "contact";

$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
$ipaddr = get_client_ip(); 

$name = $_POST["name"];
$email = $_POST['email'];
$phone = $_POST ["phone"]; 
$subject = $_POST['subject'];
$message = $_POST['message'];
$length = strlen ($phone); 

if (!preg_match ("/^[a-zA-z]*$/", $name) ) {  
    $ErrMsg = "Only alphabets and whitespace are allowed.";  
    echo $ErrMsg;  
} else if(!preg_match ("/^[0-9]*$/", $phone) ){ 
    $ErrMsg = "Only numeric value is allowed.";  
    echo $ErrMsg;  
} else if ( $length < 10 && $length > 10){
    $ErrMsg = "Mobile must have 10 digits.";  
    echo $ErrMsg;  
}else{
$sql = "INSERT INTO contact_messages (Name, Email, Contact, Subject, Message, ipAdder) VALUES ('$name', '$email','$phone', '$subject', '$message',  '$ipaddr')";

if ($conn->query($sql) === TRUE) {

    ini_set("sendmail_from", "choubeyvaibhav842@gmail.com");  
    $to = "vaibhavchoubey842@gmail.com";
    $subject = "New Contact Form Submission";
    $messageBody = "Name: $name\nEmail: $email\nSubject: $subject\nMessage: $message";
    mail($to, $subject, $messageBody);
    
    header('Location: thankyou.html'); 
 
    
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
}
$conn->close();
?>
