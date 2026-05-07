# statamic-cap

Addon Statamic pour intégrer [Cap](https://github.com/tiagozip/cap) — un CAPTCHA proof-of-work auto-hébergé — dans les formulaires Statamic.

Basé sur [oliweb/laravel-cap](https://github.com/oli217/laravel-cap).

---

## Prérequis

- PHP 8.2+
- Statamic 5.x
- Une instance Cap auto-hébergée

---

## Installation

```bash
composer require oliweb/statamic-cap
```

Publier la configuration :

```bash
php artisan vendor:publish --tag=statamic-cap-config
```

Publier les assets du widget (JS + CSS) :

```bash
php artisan vendor:publish --tag=cap-assets
```

---

## Configuration

### Via le panneau Statamic CP

Accéder à **Réglages > Cap** dans le panneau d'administration Statamic.

| Champ | Description |
|-------|-------------|
| Cap Endpoint URL | URL complète de votre instance Cap, incluant le site-key (ex : `https://cap.example.com/votre-site-key/`) |
| Secret Key | Clé secrète générée dans le tableau de bord Cap |
| Token Field Name | Nom du champ hidden injecté par le widget (défaut : `cap-token`) |
| Timeout (seconds) | Délai avant abandon de la requête vers `/siteverify` (défaut : `5`) |
| Fail Open | Si activé, laisse passer la requête en cas d'erreur de communication avec Cap |

### Via variables d'environnement

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

En cas d'échec, une erreur de validation est retournée avec le message du fichier de traduction `statamic-cap::messages.validation_failed`.

---

## Traductions

Les traductions sont disponibles en anglais et en français.

Pour les personnaliser, publier les fichiers de langue :

```bash
php artisan vendor:publish --tag=cap-lang
```

---

## Licence

MIT
