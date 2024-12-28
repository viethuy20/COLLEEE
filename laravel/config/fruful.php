<?php
$env = env('APP_ENV');
if ($env == 'local' || $env == 'development') {
    $base_data = [
        'pc_site_id'         => 'V32h2mOfiXpVzoxTap2JmlMlyiDo3N1z',
        'sp_site_id'         => 'VgKDgTXomrsRGlp8iIYC7rRjNbC2NiEI'
    ];

    return array_merge($base_data, [
        'pc_url' => 'https://colleee.staging.dropgame.jp',
        'sp_url' => 'https://colleee-sp.staging.dropgame.jp',
    ]);
} else {
    $base_data = [
        'pc_site_id'         => 'nLBCRBE45Fl25QsPEcM0dXfltb4t3CZH',
        'sp_site_id'         => 'my98a7yBBijaVvePJ3de77wSKNnVQiBc'
    ];

    return array_merge($base_data, [
        // APIリクエストURL
        'pc_url' => 'https://colleee.dropgame.jp',
        'sp_url' => 'https://colleee-sp.dropgame.jp',
    ]);
}
