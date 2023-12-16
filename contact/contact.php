<?php
require_once 'recaptcha/autoload.php';
if(isset($_POST['submitpost'])) {
  if(isset($_POST['g-recaptcha-response'])){
    $recaptcha = new \ReCaptcha\ReCaptcha('6Ld7t0klAAAAAPKSFp_VOavLNgwnK2rrCSQkq7Ei');
    $resp = $recaptcha->setExpectedHostname('recaptcha-demo.appspot.com')
    ->verify($_POST['g-recaptcha-response']);
    if ($resp->isSuccess()) {
    // Verified!
      var_dump('Captcha Valide');
    } else {
      $errors = $resp->getErrorCodes();
      var_dump('Captcha Invalide');
      var_dump($errors);
    }
  } else{
    var_dump('Captcha non rempli');
  }
}?>

<!DOCTYPE html>
<html lang="fr">
<head>

      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" href="../assets/css/style.css">
      <title>JoueTopia</title>

  <title>Contact</title>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>



  <div class="body_contact">
    <div class="background">
      <div id="containere">
        <div class="screen">

          <div class="screen-body">
            <div class="screen-body-item left">
              <div class="app-title">
                <span>CONTACTEZ-</span>
                <span>NOUS</span>
              </div>
            </div>
            <form action="message.php" method="POST">
            <div class="screen-body-item">
              <div class="app-form">
                <div class="app-form-group">
                  <input class="app-form-control" name="name" placeholder="NOM">
                </div>
                <div class="app-form-group">
                  <input class="app-form-control" name="email" placeholder="EMAIL">
                </div>
                <div class="app-form-group message">
                  <textarea class="app-form-control" name="message" placeholder="MESSAGE"></textarea>
                </div>
                <div class="app-form-group buttons">
                  <div class="g-recaptcha" data-sitekey="6Ld7t0klAAAAAPveapRBDQFO0UTyK-_K_IpDnv1_"></div>
                  <br><br>
                  <div>
                    <button type="submit" class="app-form-button">ENVOYER</button>
                  </div>
                  <span></span>
                </div>
              </div>
            </div>
          </form>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>


</div>
</body>

</html>