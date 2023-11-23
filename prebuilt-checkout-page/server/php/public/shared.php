<?php
require '../vendor/autoload.php';

// Vérifier la présence du fichier de configuration .env
if (!file_exists('../.env')) {
  http_response_code(400);
  // Afficher un message indiquant comment configurer le fichier .env
  exit;
}

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Vérifier la configuration du fichier .env
if (!$_ENV['STRIPE_SECRET_KEY']) {
  http_response_code(400);
  // Afficher un message indiquant comment configurer le fichier .env correctement
  exit;
}

// Vérifier la présence d'un prix dans le fichier .env
$price = $_ENV['PRICE'];
if (!$price || $price == 'price_12345...') {
  http_response_code(400);
  // Afficher un message indiquant comment créer un prix avec le CLI de Stripe
  exit;
}

// Configuration optionnelle pour le support et le débogage
\Stripe\Stripe::setAppInfo(
  "stripe-samples/accept-a-payment/prebuilt-checkout-page",
  "0.0.1",
  "https://github.com/stripe-samples"
);

// Initialiser l'API Stripe
$stripe = new \Stripe\StripeClient($_ENV['STRIPE_SECRET_KEY']);
?>

