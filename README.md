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

Les réglages sont sauvegardés dans `storage/statamic/addons/statamic-cap.yaml`.

### Via variables d'environnement

Les variables d'environnement servent de valeurs par défaut et sont surchargées par les réglages CP.

```env
CAP_ENDPOINT=https://cap.example.com/votre-site-key/
CAP_SECRET=votre-cle-secrete
CAP_TOKEN_FIELD=cap-token
CAP_TIMEOUT=5
CAP_FAIL_OPEN=false
```

---

## Utilisation

### Tags Antlers

Ajouter le widget Cap dans un formulaire Statamic :

```antlers
{{ form:create handle="contact" }}

    {{ cap }}

    <button type="submit">Envoyer</button>

{{ /form:create }}
```

Charger les assets dans le layout :

```antlers
<head>
    {{ cap:styles }}
</head>
<body>
    ...
    {{ cap:scripts }}
</body>
```

#### Avec nonce CSP

```antlers
{{ cap:scripts nonce="{ $cspNonce }" }}
{{ cap nonce="{ $cspNonce }" }}
```

### Validation automatique

La vérification du token est automatique : l'addon écoute l'événement `FormSubmitted` de Statamic et rejette la soumission si le token est invalide ou absent. Aucune configuration supplémentaire n'est nécessaire.

En cas d'échec, une erreur de validation est retournée avec le message `statamic-cap::messages.validation_failed`.

---

## CSP strict et WASM local

Par défaut, le widget charge son module WASM depuis jsDelivr (`cdn.jsdelivr.net`). Si vous avez une CSP stricte avec `connect-src 'self'`, téléchargez le WASM localement :

```bash
php artisan cap:publish-wasm
```

Le fichier est sauvegardé dans `storage/app/statamic-cap/cap_wasm_bg.wasm` et servi automatiquement via la route `/vendor/statamic-cap/cap_wasm_bg.wasm`. Le tag `{{ cap:scripts }}` injecte `window.CAP_CUSTOM_WASM_URL` pour que le widget l'utilise sans configuration supplémentaire.

---

## Traductions

Les traductions sont disponibles en anglais et en français. Les chaînes couvrent la validation, les messages d'erreur et les libellés de la page de réglages CP.

Pour les personnaliser, copier et modifier `resources/lang/{locale}/messages.php` dans votre projet :

```
lang/vendor/statamic-cap/{locale}/messages.php
```

---

## Licence

MIT
