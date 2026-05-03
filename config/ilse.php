<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Redes sociales (botón flotante)
    |--------------------------------------------------------------------------
    |
    | URLs opcionales. Si están vacías se pueden definir en Statamic (entrada home)
    | con ilse_social_* o aquí vía variables de entorno.
    |
    */
    'social' => [
        'whatsapp' => env('ILSE_SOCIAL_WHATSAPP'),
        'instagram' => env('ILSE_SOCIAL_INSTAGRAM'),
        'facebook' => env('ILSE_SOCIAL_FACEBOOK'),
        'linkedin' => env('ILSE_SOCIAL_LINKEDIN'),
    ],

];
