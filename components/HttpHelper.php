<?php

namespace app\components;

class HttpHelper
{

    private static function request($url, $fields, $headers = [], $method = "GET")
    {

        $valid_header = [];

        foreach ($headers as $key => $val) {
            $valid_header[] = "$key: " . $val;
        }


        if ($fields != [] && $method == "GET") {
            $url .= "?";
            foreach ($fields as $key => $val) {
                $url .= "$key=$val&";
            }

            $url = substr($url, 0, strlen($url) - 1);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $valid_header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        if (empty($fields) == false && $method == "POST") :
            curl_setopt($ch, CURLOPT_POST, true);
            if (isset($headers['Content-Type']) == false || strtolower($headers['Content-Type']) == "application/json") :
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            else :
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
            endif;
        endif;

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        // if ($method == "GET") {
        //     dd($output);
        // }
        return $output;
    }

    public static function get($url, $fields = null, $headers = [])
    {
        $response = static::request($url, $fields, $headers, "GET");

        return $response;
    }

    public static function post($url, $fields = null, $headers = [])
    {
        $response = static::request($url, $fields, $headers, "POST");

        return $response;
    }

    public static function getApi($url, $fields = null, $headers = [])
    {
        $response = json_decode(static::request($url, $fields, $headers, "GET"));

        return $response;
    }

    public static function postApi($url, $fields = [], $headers = [])
    {
        $response = json_decode(static::request($url, $fields, $headers, "POST"));

        return $response;
    }
}
