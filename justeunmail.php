<?php

require 'PHPMailerAutoload.php';
$mail = new PHPMailer;
$mail->isSMTP();
$mail->Host='smtp.gmail.com';
$mail->Port=587;
$mail->SMTPAuth=true;
$mail->SMTPSecure='tls';
$mail->Username='greenhightea@gmail.com';
$mail->Password='7WjTgoLS7WjTgoLS';
$mail->setFrom('greenhightea@gmail.com','ALED');
$mail->addAddress('tdepreux.ir2021@esaip.org');//$mail->addAddress($param_email); A CHANGER 
$mail->isHTML(true);
$mail->Subject="Validation : Changement de mot de passe";
$mail->Body='<p>Vous avez fait la demande de changement de mot de passe. Pour completer l\'operation veuillez vous reconnecter via le lien suivant :</p><br>
<p><a href="vps753611.ovh.net/emailconfirmation.php?q='."oui".'">Lien de validation du changement de mot de passe</a><p> 
<p>Si vous n\'avez pas fait la demande de changement de mot de passe, nous vous conseillons de le changer dès que possible</p>';
$mail->send();

?>