# Inspection des échecs de l'intégration continue (CI)

## Tests de capture d'écran avec Playwright

Les tests s'exécutent dans la tâche `snapshot_test` du fichier `ci.yml`. Chaque test prend simplement une capture d'écran de chaque page de paiement et la compare avec celle prise à l'avance (sauvegardée sous le répertoire `*-snapshots/`). S'il y a des différences entre les deux images, le test échoue.

Lorsque certains tests échouent, nous pouvons inspecter les images suivantes en tant qu'artefact de la page d'exécution CI (comme [ici](https://github.com/hibariya/accept-a-payment/actions/runs/3327875035#artifacts)).

- La capture d'écran attendue
- La capture d'écran réelle
- La différence visuelle

Les captures d'écran attendues sont stockées sous le répertoire `playwright/*.spec.ts-snapshots/`. Elles peuvent être mises à jour en passant l'option `--update-snapshots` à Playwright. Pour exécuter Playwright localement, consultez la section suivante, "Exécution des tests sur des environnements locaux".

# Exécution des tests sur des environnements locaux

## Configuration initiale

1. Exportez la variable d'environnement `COMPOSE_FILE` :
    ```bash
    export COMPOSE_FILE=docker-compose.yml:docker-compose.override.yml:docker-compose.playwright.yml
    ```
2. Clonez le référentiel sample-ci en passant à la branche `playwright` :
    ```bash
    git clone --branch playwright ssh://git@github.com/stripe-samples/sample-ci
    ```

## Démarrage d'une application d'exemple

Les étapes suivantes installent et lancent une application d'exemple particulière avec des implémentations spécifiques.

1. Générez les paramètres Docker Compose pour une combinaison particulière d'applications serveur et cliente.
    1. Syntaxe : `./sample-ci/setup_development_environment <nom-de-l'exemple> <application-serveur> <application-cliente> [<image-serveur>]`
        1. `<nom-de-l'exemple>` : le nom du répertoire de l'application d'exemple (par exemple, `custom-payment-flow`)
        2. `<application-serveur>` : le nom du répertoire de l'application serveur (par exemple, `node` pour `custom-payment-flow/server/node`)
        3. `<application-cliente>` : le chemin relatif du répertoire de l'application cliente par rapport à l'application serveur (par exemple, `../../client/react-cra`)
        4. `<image-serveur>` : l'image Docker de l'application serveur (optionnel)
    2. Exemple 1 : nom-de-l'exemple : `custom-payment-flow` / application-serveur : `node` / application-cliente : `react-cra`
        ```bash
        ./sample-ci/setup_development_environment custom-payment-flow node ../../client/react-cra node:lts
        ```
    3. Exemple 2 : nom-de-l'exemple : `custom-payment-flow` / application-serveur : `ruby` / application-cliente : `html`
        ```bash
        ./sample-ci/setup_development_environment custom-payment-flow ruby ../../client/html ruby:3.1
        ```
2. Créez le fichier `.env` à la racine du répertoire du référentiel de l'exemple. Le `DOMAIN` devrait être `http://web:4242` pour les applications HTML et `http://frontend:3000` pour les applications React/Vue. Le `STRIPE_WEBHOOK_SECRET` peut être obtenu avec la commande `docker compose run --rm stripe`.
    ```bash
    // .env
    STRIPE_PUBLISHABLE_KEY
    STRIPE_SECRET_KEY
    STRIPE_WEBHOOK_SECRET # docker compose run --rm stripe affichera un secret de webhook disponible
    DOMAIN=http://frontend:3000 # ou http://web:4242 pour les clients/HTML
    PRICE=price_xxxx # l'ID du prix que vous avez
    ```
3. (Uniquement pour les applications React/Vue) remplacez la destination du proxy par `http://web:4242` dans le `package.json` de l'application cliente comme suit.
    ```bash
    sed -i -E 's/("proxy":\s*)"http:\/\/localhost:4242"/\1"http:\/\/web:4242"/' custom-payment-flow/client/react-cra/package.json
    ```
4. Lancez l'application d'exemple
    ```bash
    docker compose --profile=frontend up
    ```
5. Puis ouvrez `http://localhost:3000`.

## Exécution des tests Playwright

Après avoir démarré une application d'exemple, exécutez `npm run test` sur le service `playwright` comme suit :

```bash
docker compose exec playwright npm install # nécessaire uniquement la première fois
docker compose exec playwright npm run test -- playwright/custom-payment-flow-e2e-react-cra.spec.ts
