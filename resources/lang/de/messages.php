<?php

return [
    'validation_failed'      => 'Die Cap-Verifizierung ist fehlgeschlagen. Bitte lösen Sie die Aufgabe und versuchen Sie es erneut.',
    'form_failed'            => 'Die Cap-Verifizierung ist fehlgeschlagen. Bitte versuchen Sie es erneut.',
    'settings_saved'         => 'Cap-Einstellungen gespeichert.',

    'field_endpoint'         => 'Cap-Endpunkt-URL',
    'field_endpoint_hint'    => 'Vollständige URL Ihrer selbst gehosteten Cap-Instanz, einschließlich des Site-Keys. Beispiel: https://cap.example.com/ihr-site-key/',
    'field_secret'           => 'Geheimer Schlüssel',
    'field_secret_hint'      => 'Der geheime Schlüssel aus Ihrem Cap-Dashboard. Niemals öffentlich zugänglich machen.',
    'field_token_field'      => 'Name des Token-Felds',
    'field_token_field_hint' => 'Name des versteckten Felds, das das Cap-Widget in das übergeordnete Formular einfügt.',
    'field_timeout'          => 'Zeitlimit (Sekunden)',
    'field_timeout_hint'     => 'Zeit in Sekunden, bevor die Anfrage an /siteverify abgebrochen wird.',
    'field_fail_open'        => 'Fail Open',
    'field_fail_open_hint'   => 'Wenn aktiviert, lässt jeder Kommunikationsfehler mit Cap die Anfrage durch. Ein explizit ungültiges Token wird immer abgelehnt.',
    'save'                   => 'Speichern',
];
