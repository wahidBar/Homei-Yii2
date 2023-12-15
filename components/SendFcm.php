<?php
namespace app\components;

class SendFcm
{
    public static function message($token, $data, $callback)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';

        $msg = array
            (
            'body' => $data["body"],
            'title' => $data["title"],
            'image' => $data["image"],
            'vibrate' => 1,
            'sound' => "default",
            'largeIcon' => 'large_icon',
            'smallIcon' => 'small_icon',
        );

        if (isset($token)) {
            // var_dump(strtolower(gettype($token)));
            // die;
            if (strtolower(gettype($token)) == "array") {
                $registration_ids = $token;
            } else {
                $registration_ids = [$token];
            }
        } else {
            $registration_ids = [];
        }
        // var_dump($registration_ids);
        // die;

        if ($registration_ids != []) {

            $fields = [
                'registration_ids' => $registration_ids,
                'priority' => "high",
                'notification' => $msg,
                'data' => $callback($data),
            ];

            $headers = array(
                'Authorization:key = AAAAJT_6GKg:APA91bHroMI1q25BD05F5xHtePCsqtRxZw934Tcl3yT8xCJ9bDWftPJi8ILsJVTrSSA6lhTL5-AfhsUgLM8Nga7ssGrR6wA0Gg-D4-4QVDerioHKat8OqRc8hX5QSaclvD6ypQjTNvfe',
                'Content-Type: application/json',
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            if ($result === false) {
                die('Curl failed: ' . curl_error($ch));
            }
            curl_close($ch);
            return $result;
        }
    }
}
