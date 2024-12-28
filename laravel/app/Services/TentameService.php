<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class TentameService
{
    protected $client;
    protected $apiCreateUser;
    protected $apiGetProject;

    public function __construct()
    {
        $this->client = app(Client::class);
        $this->apiCreateUser = config('receipt.CREATE_USER');
        $this->apiGetProject = config('receipt.GET_PROJECT');
        $this->clientKey = config('receipt.CLIENT_KEY');
        $this->clientSecret = config('receipt.CLIENT_SECRET');
    }

    /**
     * Logic to handle the data
     */
    public function handle()
    {

    }

    /**
     * Register user
     *
     * @param $params (required: client_key, client_secret, user_id, gender, birthday, prefecture, timestamp, signature)
     * @return array
     */
    public function registerUser($params)
    {
        return $this->send($params, $this->apiCreateUser);
    }

    /**
     * get item project
     *
     * @param $params (required: client_key, client_secret, project_id, iframe, device, timestamp, signature)
     * @return array
     */
    public function getItemProject($params)
    {
        return $this->send($params, $this->apiGetProject);
    }

    /**
     * Make request to Omise
     *
     * @param $action
     * @param $params
     * @return array
     */
    protected function send($params, $url)
    {
        $result = [
            'status'       => false,
            'errorMessage' => null,
            'data'         => null,
        ];

        try {
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
            $output = curl_exec($curl);
            $response = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
            if ($response == Response::HTTP_OK) {
                $response = json_decode($output);
                if ($response->result) {
                    $result['status'] = true;
                }

                $result['data'] = $response;
                $result['errorMessage'] = $response->error ?? '';
            }
        } catch (\Exception $e) {
            \Log::error('SEND API PUSH: ' . $e->getMessage());
            $result['status'] = false;
            $result['errorMessage']  = $e->getMessage();
        }

        return $result;
    }
}
