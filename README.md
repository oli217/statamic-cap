# statamic-cap

Addon Statamic pour intégrer [Cap](https://github.com/tiagozip/cap) — un CAPTCHA proof-of-work auto-hébergé — dans les formulaires Statamic.

Basé sur [oliweb/laravel-cap](https://github.com/oli217/laravel-cap).

---

## Prérequis

- PHP 8.2+
- Statamic 5.x ou 6.x
- Une instance Cap auto-hébergée

---

## Installation

```bash
composer require oliweb/statamic-cap
```

Les assets (JS + CSS) sont servis automatiquement par l'addon via des routes dédiées. Aucun `vendor:publish` n'est nécessaire.

---

## Configuration

### Via le panneau Statamic CP

Accéder à **Tools > Cap CAPTCHA** dans le panneau d'administration Statamic.

| Champ | Description |
|-------|-------------|
| Cap Endpoint URL | URL complète de votre instance Cap, incluant le site-key (ex : `https://cap.example.com/votre-site-key/`) |
| Secret Key | Clé secrète générée dans le tableau de bord Cap |
| Token Field Name | Nom du champ hidden injecté par le widget (défaut : `cap-token`) |
| Timeout (seconds) | Délai avant abandon de la requête vers `/siteverify` (défaut : `5`) |
| Fail Open | Si activé, laisse passer la requête en cas d'erreur de communication avec Cap |
| Hide Attribution Link | Si activé, masque le lien « Cap » en bas à droite du widget |

Les réglages sont sauvegardés dans `storage/statamic/addons/statamic-cap.yaml`.

### Via variables d'environnement

Les variables d'environnement servent de valeurs par défaut et sont surchargées par les réglages CP.

```env
CAP_ENDPOINT=https://cap.example.com/votre-site-key/
CAP_SECRET=votre-cle-secrete
CAP_TOKEN_FIELD=cap-token
CAP_TIMEOUT=5
CAP_FAIL_OPEN=false
CAP_HIDE_ATTRIBUTION=false
```

---

## Utilisation

### Tags Antlers

| Tag | Description |
|-----|-------------|
| `{{ cap }}` | Rendu du `<cap-widget>` avec l'endpoint configuré |
| `{{ cap:scripts }}` | Injecte `window.CAP_CUSTOM_WASM_URL` + `<script type="module">` pour le widget |
| `{{ cap:styles }}` | Balise `<link>` CSS du widget |
| `{{ cap:config }}` | `<script>` exposant `window.CAP_API_ENDPOINT` et `window.CAP_TOKEN_FIELD` |

#### Mode widget standard

Charger les assets dans le layout et ajouter le widget dans un formulaire Statamic :

```antlers
<head>
    {{ cap:styles }}
</head>
<body>

    {{ form:create handle="contact" }}
        {{ cap }}
        <button type="submit">Envoyer</button>
    {{ /form:create }}

    {{ cap:scripts }}
</body>
```

Le widget injecte automatiquement un champ hidden `cap-token` dans le formulaire parent lors de la vérification.

`{{ cap:scripts }}` injecte toujours `window.CAP_CUSTOM_WASM_URL` pointant vers la route locale du WASM, sans requête externe au runtime.

#### Mode programmatic

Utilisez `{{ cap:config }}` pour exposer l'endpoint en JavaScript, puis instanciez `Cap` directement sans afficher de widget visible :

```antlers
<head>
    {{ cap:styles }}
</head>
<body>

    {{ cap:config }}
    {{ cap:scripts }}

    <form method="POST" action="/contact">
        <input type="hidden" name="cap-token" id="cap-token">
        <button type="submit" id="submit-btn">Envoyer</button>
    </form>

    <script type="module">
    document.getElementById('submit-btn').addEventListener('click', async (e) => {
        e.preventDefault();

        const cap = new Cap({ apiEndpoint: window.CAP_API_ENDPOINT });
        const { token } = await cap.solve();

        document.getElementById('cap-token').value = token;
        e.target.closest('form').submit();
    });
    </script>

</body>
```

`Cap` crée automatiquement un élément `cap-widget` masqué en arrière-plan. Aucun widget visuel n'est rendu.

`window.CAP_API_ENDPOINT` et `window.CAP_TOKEN_FIELD` sont définis par `{{ cap:config }}` depuis la configuration PHP, sans hard-coding côté JavaScript.

#### Avec nonce CSP

```antlers
{{ cap:config nonce="{ $cspNonce }" }}
{{ cap:scripts nonce="{ $cspNonce }" }}
{{ cap nonce="{ $cspNonce }" }}
```

#### Headers CSP

Le widget utilise des Web Workers et WebAssembly. Une CSP stricte doit inclure :

```
Content-Security-Policy:
  script-src 'nonce-{nonce}' 'strict-dynamic';
  worker-src blob:;
  wasm-unsafe-eval;
  connect-src 'self';
```

`worker-src blob:` — requis car le widget crée des workers via des URLs `Blob`.
`wasm-unsafe-eval` — requis pour le calcul WebAssembly.
`connect-src 'self'` — suffisant si le WASM est hébergé localement (voir ci-dessous).

### Validation automatique

La vérification du token est automatique : l'addon écoute l'événement `FormSubmitted` de Statamic et rejette la soumission si le token est invalide ou absent. Aucune configuration supplémentaire n'est nécessaire.

En cas d'échec, une erreur de validation est retournée avec le message `statamic-cap::messages.validation_failed`.

---

## WASM local (CSP stricte)

Par défaut, `{{ cap:scripts }}` injecte `window.CAP_CUSTOM_WASM_URL` pointant vers la route `/vendor/statamic-cap/cap_wasm_bg.wasm`. Cette route sert le fichier local s'il est présent, sinon redirige automatiquement vers le CDN jsDelivr.

Pour une auto-hébergement complet sans aucune requête externe, téléchargez le WASM localement :

```bash
php artisan cap:publish-wasm
```

Le fichier est sauvegardé dans `storage/app/statamic-cap/cap_wasm_bg.wasm` et servi automatiquement. La CSP peut alors se limiter à `connect-src 'self'` sans whitelister jsDelivr.

---

## Traductions

Les traductions sont disponibles en anglais et en français. Les chaînes couvrent la validation, les messages d'erreur, les libellés du widget et la page de réglages CP.

Pour les personnaliser, copier et modifier le fichier dans votre projet :

```
lang/vendor/statamic-cap/{locale}/messages.php
```

---

## Licence

MIT
