
# Acceptez les paiements avec Stripe Checkout en utilisant PHP

Cette intégration vous montre comment accepter les paiements avec [Stripe Checkout](https://stripe.com/docs/payments/checkout) .

Cet exemple de serveur contient à la fois les parties client et serveur nécessaires pour mettre en place une intégration Checkout.

![home](https://github.com/SamM-64/stripe-prebuilt-checkout-page-php/assets/71389760/39293db8-506b-4306-acb2-9182b8147f4b)

## Prérequis

* PHP

## Comment exécuter

1. **Confirmez la configuration du fichier `.env`**

   Copiez `.env.example` depuis la racine vers `.env` dans le répertoire de ce serveur, remplacez-le par vos clés d'API Stripe :

   ```sh
   cp .env.example .env
   ```

2. Installez la CLI.
https://stripe.com/docs/stripe-cli


2. Créez un prix

Vous pouvez créer des produits et des prix dans le tableau de bord ou avec l'API. Cet exemple nécessite l'exécution d'un prix. Une fois que vous avez créé un prix et ajoutez son identifiant à votre .env.

PRICE est l'ID d'un prix pour votre produit. Un prix a un montant unitaire et une devise.

Vous pouvez créer rapidement un prix avec la CLI Stripe comme ceci :

```bash
stripe prices create --unit-amount 500 --currency usd -d "product_data[name]=demo"
```

Ce qui renverra le json :

```bash
{
  "id": "price_1Hh1ZeCZ6qsJgndJaX9fauRl",
  "object": "price",
  "active": true,
  "billing_scheme": "per_unit",
  "created": 1603841250,
  "currency": "usd",
  "livemode": false,
  "lookup_key": null,
  "metadata": {
  },
  "nickname": null,
  "product": "prod_IHalmba0p05ZKD",
  "recurring": null,
  "tiers_mode": null,
  "transform_quantity": null,
  "type": "one_time",
  "unit_amount": 500,
  "unit_amount_decimal": "500"

```
Prenez le Price ID, dans le cas d'exemple price_1Hh1ZeCZ6qsJgndJaX9fauRl, et définissez la variable d'environnement dans.env :

```bash
PRICE=price_1Hh1ZeCZ6qsJgndJaX9fauRl
```
Installez les dépendances avec Composer

Depuis le répertoire qui contient composer.json, exécutez :

```bash
composer install
```
Exécutez le serveur en local

Démarrez le serveur depuis le répertoire public avec :

```bash
cd public
php -S localhost:4242
```
Accédez à localhost:4242 pour voir la démo.

4. [Facultatif] Exécutez un webhook localement

Vous pouvez utiliser Stripe CLI pour lancer facilement un webhook local.

```bash
stripe listen --forward-to localhost:4242/webhook
```
La CLI imprimera une clé secrète de webhook sur la console. Définissez STRIPE_WEBHOOK_SECRETcette valeur dans votre .envfichier.

Vous devriez voir les événements enregistrés dans la console sur laquelle la CLI est exécutée.


5. Page de paiement
La démo s'exécute en mode test : utilisez un  numéro de carte de  [test](https://stripe.com/docs/testing#cards) avec n'importe quel CVC + date d'expiration future.

![Payment](https://github.com/SamM-64/stripe-prebuilt-checkout-page-php/assets/71389760/d1b957b0-62c5-436e-b08f-aafc12ba1dbe)



