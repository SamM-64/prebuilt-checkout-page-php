<?php
require_once 'shared.php';
// Retrieve the Checkout Session for the successful payment flow that just
// completed. This will be displayed in a `pre` tag as json in this file.
$checkout_session = $stripe->checkout->sessions->retrieve(
$_GET['session_id']
);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Stripe Checkout Sample</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="css/normalize.css" />
    <link rel="stylesheet" href="css/global.css" />
  </head>

  <body>
    <div class="sr-root">
      <div class="sr-main">
        <div class="sr-payment-summary completed-view">
          <h1>Votre paiement est réussi</h1>
        </div>
        <button onclick="window.location.href = '/';">Revenir à la page d'accueil</button>
      </div>
      <div class="sr-content">
        <div class="pasha-image-stack">
          <img src="https://picsum.photos/280/320?random=1" width="140" height="160" />
          <img src="https://picsum.photos/280/320?random=2" width="140" height="160" />
          <img src="https://picsum.photos/280/320?random=3" width="140" height="160" />
          <img src="https://picsum.photos/280/320?random=4" width="140" height="160" />
        </div>
      </div>
    </div>
  </body>
</html>

