<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FriendReferralBonusSchedule;
use App\Services\Meta;

class FriendsController extends Controller
{
    private $meta;

    public function __construct(Meta $meta)
    {
        $this->meta = $meta;
    }

    public function index()
    {
        $set_date = date("Y/m/d");
        $friend_referral_bonus = FriendReferralBonusSchedule::Enable()->GetDate($set_date)->OrderByDescId()->first();

        $arr_breadcrumbs = $this->meta->setBreadcrumbs(null);
        $application_json = '';
        $position = 1;
        foreach($arr_breadcrumbs as $key => $val) {
            $application_json .= '{"@type": "ListItem","position":' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
            $position++;
        }
        $link = route('friends.index');
        $application_json .= '{"@type": "ListItem","position":' . $position . ', "name": "友達紹介", "item": "' . $link . '"}';

        return view('friends', [
            'friend_referral_bonus' => $friend_referral_bonus,
            'application_json' => $application_json
        ]);
    }

}
