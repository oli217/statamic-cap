<?php

return [
    'validation_failed'    => 'The Cap verification failed. Please complete the challenge and try again.',
    'form_failed'          => 'Cap verification failed. Please try again.',
    'settings_saved'       => 'Cap settings saved.',

    'field_endpoint'       => 'Cap Endpoint URL',
    'field_endpoint_hint'  => 'Full URL of your self-hosted Cap instance, including the site key. Example: https://cap.example.com/your-site-key/',
    'field_secret'         => 'Secret Key',
    'field_secret_hint'    => 'The secret key from your Cap dashboard. Never expose this publicly.',
    'field_token_field'    => 'Token Field Name',
    'field_token_field_hint' => 'Name of the hidden field injected by the Cap widget into the parent form.',
    'field_timeout'        => 'Timeout (seconds)',
    'field_timeout_hint'   => 'Time in seconds before abandoning the request to /siteverify.',
    'field_fail_open'      => 'Fail Open',
    'field_fail_open_hint' => 'When enabled, any communication error with Cap lets the request through. An explicitly invalid token is always rejected.',
    'save'                 => 'Save',
];
