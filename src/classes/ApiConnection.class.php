<?php
class ApiConnection {
    private const API_ENDPOINT = 'https://wbsapi.withings.net';
    private const CLIENT_ID = '1cd36594208f224fcfdbdbfee75c45aed115fe44fe03157ff3f28a2048354446';
    private const SECRET = '445d4c2d9168ff8cbac82b9e00a04290bb9f103a6e2d8681eaa10377ddf27902';

    public function authorize() {
        $url = 'https://account.withings.com/oauth2_user/authorize2';
        $query = http_build_query([
            'response_type' => 'code',  # imposed string by the api
            'client_id' => self::CLIENT_ID,
            'state' => 'STATE',
            'scope' => 'user.metrics',  # see docs for enhanced scope
            'redirect_uri' => 'http://localhost:5000/auth.php',  # URL of this app
            'mode' => 'demo'  # Use demo mode, DELETE THIS FOR REAL APP
        ]);
        $url .= "?$query";
        return $url;
    }

    public function requestTokens($auth_code) {
        $url = self::API_ENDPOINT . '/v2/oauth2';
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([ 
            'action' => 'requesttoken',
            'grant_type' => 'authorization_code',
            'client_id' => self::CLIENT_ID,
            'client_secret' => self::SECRET,
            'code' => $auth_code,
            'redirect_uri' => 'http://localhost:5000/auth.php'
        ]));

        $rsp = curl_exec($ch);
        curl_close($ch);

        return $rsp;
    }
    
    public function getNonce() {
        $url = self::API_ENDPOINT . '/v2/signature';
        $ch = curl_init();
        $timestamp = time();
        
        $signed_params = array(
            'action'     => 'getnonce',
            'client_id'  => self::CLIENT_ID,
            'timestamp'  => $timestamp,
        );   
        ksort($signed_params);
        $data = implode(",", $signed_params);
        // var_dump(date_default_timezone_get());
        $signature = hash_hmac('sha256', $data, self::SECRET);
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([ 
            'action' => 'getnonce',
            'client_id' => self::CLIENT_ID,
            'timestamp' => $timestamp,
            'signature' => $signature
        ]));
        
        $rsp = curl_exec($ch);
        curl_close($ch);

        return $rsp;
    }

    public function getMeas($token) {
        $url = self::API_ENDPOINT . '/measure';
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://wbsapi.withings.net/measure");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $token",
            "cache-control: no-cache"
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([ 
            'action' => 'getmeas',
            'meastype' => 1, // weight
            // 'meastypes' => 'meastypes',
            // 'category' => 1, // 1 for real measures, 2 for user objectives
            // 'startdate' => 'startdate',
            // 'enddate' => 'enddate',
            // 'offset' => 'offset',
            // 'lastupdate' => 'int'
        ]));

        $rsp = curl_exec($ch);
        curl_close($ch);

        return $rsp;
    }
}