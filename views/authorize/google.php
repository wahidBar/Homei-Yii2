<?php
// http://localhost:8085/uts_web/index.php?module=authorize&routes=google
// 793113128601-f5d7ro24m6ktd2sbdue1t44b834f8o27.apps.googleusercontent.com
// ASPmtGj_OIfpzZVoyFzEMzV_

// https://accounts.google.com/o/oauth2/v2/auth?client_id=793113128601-f5d7ro24m6ktd2sbdue1t44b834f8o27.apps.googleusercontent.com&redirect_uri=http://localhost:8085/uts_web/authorize.php&scope=profile email openid&response_type=code&access_type=offline&include_granted_scopes=true

// https://accounts.google.com/o/oauth2/v2/auth?client_id=793113128601-f5d7ro24m6ktd2sbdue1t44b834f8o27.apps.googleusercontent.com&redirect_uri=http://localhost:8085/uts_web/authorize/google&scope=profile email openid&response_type=code&access_type=offline&include_granted_scopes=true
// profile email openid

$GCLIENT_ID = "451233796463-ng74m66j8fgc0v2cp5fdbcfg6s2i9v3f.apps.googleusercontent.com";
$GCLIENT_SECRET ="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";

$response = HttpHelper::postApi("https://www.googleapis.com/oauth2/v4/token",[
    "code"=> $_GET['code'],
    "client_id"=> $GCLIENT_ID,
    "client_secret"=> $GCLIENT_SECRET,
    "redirect_uri"=> 'http://localhost:8085/uts_web/authorize/google',
    "grant_type"=> 'authorization_code'
]);

if (isset($response->error)) {
    print_r($response);
    die;
}

$info = HttpHelper::getApi("https://www.googleapis.com/oauth2/v1/userinfo", [
    "access_token" => $response->access_token,
], [
    'Authorization' => "Bearer " . $response->access_token,
]);

if (isset($response->error)) {
    echo "Terjadi kesalahan ketika mengambil data";
    die;
}

$check = $this->db->findOne([
    "gid" => $info->id,
], 'user');

if ($check) {
    $this->user->login($check);
    Url::redirect('site');
} else {
    $saved = $this->db->insertOne([
        "gid" => $info->id,
        "name" => $info->name,
        "image" => $info->picture,
        "email" => $info->email,
    ], 'user');

    if ($saved) {
        $this->user->login($saved);
        Url::redirect('site');
    } else {
        echo "Terjadi kesalahan ketika menyimpan user:". $this->db->getError();
        die;
    }
}

echo "Terjadi kesalahan";
die;
