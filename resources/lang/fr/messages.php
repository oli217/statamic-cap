<?php

return [
    'validation_failed'    => 'La vérification Cap a échoué. Veuillez compléter le défi et réessayer.',
    'form_failed'          => 'La vérification Cap a échoué. Veuillez réessayer.',
    'settings_saved'       => 'Réglages Cap enregistrés.',

    'field_endpoint'       => 'URL de l\'instance Cap',
    'field_endpoint_hint'  => 'URL complète de votre instance Cap auto-hébergée, incluant le site-key. Exemple : https://cap.example.com/votre-site-key/',
    'field_secret'         => 'Clé secrète',
    'field_secret_hint'    => 'La clé secrète de votre tableau de bord Cap. Ne jamais l\'exposer publiquement.',
    'field_token_field'    => 'Nom du champ token',
    'field_token_field_hint' => 'Nom du champ hidden injecté par le widget Cap dans le formulaire parent.',
    'field_timeout'        => 'Timeout (secondes)',
    'field_timeout_hint'   => 'Délai en secondes avant abandon de la requête vers /siteverify.',
    'field_fail_open'      => 'Fail Open',
    'field_fail_open_hint' => 'Activé : toute erreur de communication avec Cap laisse passer la requête. Un token explicitement invalide est toujours refusé.',
    'save'                 => 'Enregistrer',
];
