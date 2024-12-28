<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Services\SurveyService;


class SurveyController extends Controller
{
    protected $surveyService;
    public function __construct()
    {
        $this->surveyService = new SurveyService();
    }

    public function getSurveyFromCeres($user_id, Request $request)
    {
        $client = new Client();
        $response = $client->request('GET', 'https://api.ad-research.jp/v2/user/' . $user_id . '/items', [
            'query' => ['api_key' => config('ceres.api_key')]
        ]);
        return $response->getBody()->getContents();
    }

    // アイブリッジからアンケートを取得
    public function getSurveyFromIBridge($user_id, Request $request)
    {
        $client = new Client();
        $response = $client->request('GET', config('i_bridge.url').'api/v2/getresearch.php', [
            'query' => ['uid'=>$user_id,'mid' => config('i_bridge.mid'), 'syid' => hash('sha256',config('i_bridge.syid').config('i_bridge.mid').$user_id)]
        ]);
        $xml = $response->getBody()->getContents();
        return $this->surveyService->get_xml_survey($xml);
    }
}
