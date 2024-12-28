<?php
return [
    'status' => [App\AffReward::REWARDED_STATUS => '配布済み',
        App\AffReward::CANCELED_STATUS => 'キャンセル',
        App\AffReward::WAITING_STATUS => '配布待ち',
        App\AffReward::ERROR_STATUS => '異常',
        App\AffReward::ACTIONED_STATUS => '発生',
        App\AffReward::AUTO_CANCELED_STATUS => '自動キャンセル'
    ],
    'error' => [App\AffReward::FORMAT_ERROR_CODE => '書式エラー',
        App\AffReward::USER_NOT_EXIST_CODE => 'ユーザー取得失敗',
        App\AffReward::AFFIRIATE_NOT_EXIST_CODE => 'アフィリエイト取得失敗'
    ],
];
