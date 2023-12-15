<?php
namespace app\components;

class SendExpoNotification
{
    public static function message($token, $data, $callback=null)
    {
        $url = 'https://exp.host/--/api/v2/push/send';

        $data = [
            'to' => $token,
            'body' => $data["body"],
            'title' => $data["title"],
            'data' => $data,
            'priority' => "high",
        ];

        $response = HttpHelper::postApi($url, json_encode($data), [
            "content-type" => "Application/json",
        ]);

        return $response;

    }
}
