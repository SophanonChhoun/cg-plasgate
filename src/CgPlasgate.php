<?php
namespace Cg\Plasgate;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\RequestOptions;

class CgPlasgate
{
    protected $method;
    protected $params;
    protected $key_param;

    public function sendSms($text, $phone_number)
    {
        $this->params['text'] = $text;

        if(is_string($phone_number))
            $this->params['number'] = $phone_number;
        else if(is_array($phone_number))
            $this->params['number'] = implode(',', $phone_number);

        return $this->request(true);
    }

    public function request($is_array = false)
    {
        try {
            $request = new Client([
                'headers' => [
                    'Accept' => 'application/json'
                ],
                'http_errors' => false,
                'verify' => false
            ]);

            $response = $request->post(config('url') . 'authorize', [
                RequestOptions::JSON => [
                    'username' => config('username'),
                    'password' => config('password'),
                ]
            ]);

            $response = json_decode($response->getBody(), $is_array);

            if(!isset($response['status']))
                return false;

            $response = $request->post(config('url') . 'accesstoken', [
                RequestOptions::JSON => [
                    'authorization_code' => $response['data']['authorization_code'],
                ]
            ]);

            $response = json_decode($response->getBody(), $is_array);

            if(!isset($response['status']))
                return false;

            $response = $request->post(config('url') . 'send', [
                RequestOptions::JSON => [
                    [
                        'number' => $this->params['number'],
                        'senderID' => config('sender_id'),
                        'type' => 'sms',
                        'text' => $this->params['text']
                    ]
                ],
                'headers' => [
                    'X-Access-Token' => $response['data']['access_token']
                ]
            ]);

            $response = json_decode($response->getBody(), $is_array);

            if(!isset($response['status']))
                return false;

            return $response;
        } catch (ConnectException $e) {
            return $e->getMessage();
        }
    }

    public function getEnv()
    {
        return [
          'username' => config('username'),
          'password' => config('password'),
          'senderId' => config('sender_id'),
          'url'      => config('url'),
        ];
    }
}
