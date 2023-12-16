<?php
function __confirm_form__() {
    $errors = [];

    $country = htmlspecialchars($_POST['__country']);
    $city = htmlspecialchars($_POST['__city']);
    $zip = htmlspecialchars($_POST['__zip']);
    $address = htmlspecialchars($_POST['__address']);

    if (empty($country) || empty($city) || empty($zip) || empty($address)) {
        $errors[] = 'Veuillez remplir tous les champs d\'information de livraison.';
    } else {
        $clientOrder = new ClientOrderController();
        $clientOrder->purchase();
    }

    return $errors;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../controllers/ClientOrderController.php';

    $errors = [];

    if (!empty($_POST['__sendPaypal'])) {
        $paypalEmail = htmlspecialchars($_POST['__paypal_email']?? '');
        $paypalPassword = htmlspecialchars($_POST['__paypal_password']?? '');
        if (empty($paypalEmail) || empty($paypalPassword)) {
            $errors[] = 'Veuillez renseigner votre email et votre mot de passe de connexion à Paypal.';
        } else {
            if (!filter_var($paypalEmail, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Veuillez renseigner un email valide.';
            } else {
                $errors = __confirm_form__();
            }
        }
    } else if (!empty($_POST['__sendBankCard'])) {
        $bankCardNumber = htmlspecialchars($_POST['__bank_card_number']?? '');
        $bankCardSecurity = htmlspecialchars($_POST['__bank_card_security']?? '');
        $bankCardProprietary = htmlspecialchars($_POST['__bank_card_proprietary']?? '');

        if (empty($bankCardNumber) || empty($bankCardSecurity) || empty($bankCardProprietary)) {
            $errors[] = 'Veuillez renseigner votre numéro de carte bancaire, votre code de sécurité et l\'identité du détenteur de la carte.';
        } else {
            if (strlen($bankCardNumber)!= 16 && ctype_digit($bankCardNumber)) {
                $errors[] = 'Veuillez renseigner un numéro de carte bancaire valide à 16 chiffres.';
            } else {
                if ((strlen($bankCardSecurity) != 3 && strlen($bankCardSecurity) != 4) || !ctype_digit($_POST['__bank_card_security'])) {
                    $errors[] = 'Veuillez renseigner un code de sécurité valide à 3 ou 4 chiffres.';
                } else {
                    $errors = __confirm_form__();
                }
            }
        }
    } else {
        $errors[] = 'Veuillez choisir votre mode de paiement.';
    }

    header('Location: /achat/panier.php?result='.urlencode(json_encode($errors)));
} else {
//    header('Location: /');
//    var_dump($_SERVER['REQUEST_METHOD']);
}