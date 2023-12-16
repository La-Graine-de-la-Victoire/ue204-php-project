<?php
  $name = $_POST['name'];
  $email = $_POST['email'];
  $message = $_POST['message'];

  if(!empty($email) && !empty($message)){
    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
      $receiver = "assiaikerchalene@gmail.com"; 
      $subject = "De: $name <$email>";
      $body = "Nom: $name\nEmail: $email\nMessage:\n$message\n\nCordialement,\n$name";
      $sender = "De: $email";
      if (mail($receiver, $subject, $body, $sender)) {
        echo '<div class="container success">Votre message a bien été envoyé.</div>';
    } else {
        echo '<div class="container error">Désolé, nous n\'avons pas réussi à envoyer votre message !</div>';
    }
    }else{
      echo '<div class="container error">Votre adresse mail n est pas valide.</div>';
    }
  }else{
    echo '<div class="container error">Le champ e-mail et message est obligatoire !</div>';
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Succès de l'envoie</title>
</head>
<body>
    
</body>
</html>