<?php

$recievermail = "post@plant2plast.dk";
$subject = "Fra App | Kontakt";
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

$headers .= "From: <P2P APP>" . "\r\n";
//$headers .= 'Cc: myboss@example.com' . "\r\n";

$content = $_POST['contactInputName'];
$content.= "<br>";
$content.= $_POST['contactInputEmail'];
$content.= "<br>";
$content.= $_POST['contactInputTextares'];

mail($recievermail,$subject,$content,$headers);
$_SESSION['thanksforyourmail'] = "1";

header('Location: ../contact.php');