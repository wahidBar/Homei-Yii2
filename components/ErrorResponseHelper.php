<?php

namespace app\components;

use app\models\UserAccessLog;
use Throwable;
use Yii;
use yii\base\Event;

class ErrorResponseHelper
{
    const allowed_keys = ["success", "data", "message", "code", 'token'];

    public static function beforeResponseSend(Event $event)
    {
        $except = [
            "site/get-kota",
            "site/get-kecamatan",
            "site/get-desa",
            "site/get-material",
            "site/get-isian",
            "site/get-supplier",
            "site/get-sub-material",
            "site/get-barang",
            "proyek-kemajuan/get-parent",
            "user/get-user",
            "user/get-user-tukang",
            "home/api/get-sub-material",
            "user/list-pengguna-android",
            "api/v1/smarthome/data-kontrol",
            "api/v1/smarthome/sync-data",
            "api/v1/smarthome-master-produk/get-pairing-code",
            "api/v1/smarthome-master-produk/check",
            "api/v1/smarthome-master-produk/reset",
            "api/v1/smarthome-master-produk/check-reset",
            // "api/v1/smarthome/sync-data",
        ];

        $response = $event->sender;
        $request = Yii::$app->request;

        if (in_array($request->pathInfo, $except)) :
            return $event;
        endif;

        $url = str_replace($request->getBaseUrl(), "", $request->getUrl());

        $content_type = strtolower(Yii::$app->request->headers->get("content-type"));

        try {
            //code...
            if ($response->format == "json" || $content_type == "application/json" || is_int(strpos($url, "/api/"))) :
                $response->format = "json";

                $logged = SSOToken::checkToken();
                // dd($response);
                if ($logged['success']) {
                    // logging
                    $log = new \app\models\AccessLog();
                    $log->ip = Yii::$app->request->userIP;
                    $log->controller = get_class(Yii::$app->controller);
                    $log->request = json_encode(Yii::$app->request->bodyParams);
                    $log->method = Yii::$app->request->method;
                    $log->type = "api";

                    $user = Constant::getUser();
                    if ($user) {
                        $log->user_id = $user->id;
                        $log->username = $user->username;
                        $log->role = $user->role->name;
                    } else {
                        $log->user_id = null;
                        $log->username = null;
                        $log->role = null;
                    }

                    $log->save();
                }

                // dd($response);

                if ($response->statusCode != 200 && $response->statusText != "OK") :
                    if (is_array($response->data)) :
                        if ($response->data['message']) :
                            $message = $response->data['message'];
                        else :
                            $message = $response->statusText;
                        endif;
                    else :
                        $message = $response->statusText;
                    endif;

                    $response->data = [
                        'success' => false,
                        "message" => $message,
                        'code' => $response->statusCode,
                    ];
                else :
                    if (is_array($response->data)) :
                        self::handleResponseArray($response);
                    else :
                        self::handleResponseObject($response);
                    endif;
                endif;
            endif;
        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
        }

        return $event;
    }

    /**
     * handle array
     * @param Object $response
     */
    private static function handleResponseArray(&$response)
    {
        if (!isset($response->data['success']) && !(isset($response->data['code']) || isset($response->data['status']))) :
            $message = self::getMessage($response);

            if (isset($response->data['data']) && isset($response->data['_meta'])) {
                $response->data = array_merge([
                    'success' => true,
                    "message" => $message,
                    "data" => [],
                    'code' => 200,
                ], $response->data);
            } else {
                $response->data = [
                    'success' => true,
                    "message" => $message,
                    "data" => $response->data,
                    'code' => 200,
                ];
            }
        elseif ($response->data['code'] == null || $response->data['status'] == null || $response->data['code'] == 0) :
            self::removeKeys($response, 'array');
            $message = self::getMessage($response);

            $response->data["success"] = $response->data["success"] ?? true;
            $response->data["message"] = $message;
            $response->data['data'] = $response->data['data'] ?? [];
            $response->data["code"] = 200;
        endif;
    }

    /**
     * handle object
     * @param Object $response
     */
    private static function handleResponseObject(&$response)
    {
        $res = $response->data;
        if (!isset($res->success) && !(isset($res->code) || isset($res->status))) :
            $code = $res->code ?? $res->status;
            $message = self::getMessage($response, 'object');

            $response->data = [
                'success' => true,
                "message" => $message,
                "data" => $response->data,
                'code' => $code ?? 200,
            ];
        elseif ($res->code == null || $res->status == null || $res->code == 0) :
            self::removeKeys($response, 'object');
            $message = self::getMessage($response, 'object');

            $res->success = $res->success ?? true;
            $res->message = $message;
            $res->data = $res->data ?? [];
            $res->code = 200;
        endif;
    }

    /**
     * Gettig message
     * @param \yii\web\Response $response
     * @param string $type
     *
     * @return string
     */
    private static function getMessage($response, $type = "array")
    {
        $message = $response->statusText;
        if (strtolower($type) == 'array') :
            if (isset($response->data['message'])) :
                $message = $response->data['message'];
                unset($response->data['message']);
            endif;
        else :
            $res = $response->data;
            if (isset($res->message)) :
                $message = $res->message;
                unset($res->message);
            endif;
        endif;

        return $message;
    }

    protected static function removeKeys(&$response, $key = "object")
    {
        if (strtolower($key) == "object") :
            $keys = array_keys((array) $response->data);
            foreach ($keys as $key) :
                if (in_array($key, static::allowed_keys) == false) :
                    unset($response->data->$key);
                endif;
            endforeach;
        else :
            $keys = array_keys($response->data);
            foreach ($keys as $key) :
                if (in_array($key, static::allowed_keys) == false) :
                    unset($response->data[$key]);
                endif;
            endforeach;
        endif;
    }
}
