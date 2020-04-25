# Simple OAuth1 Library


## Usage

    include_once 'OAuth1.php';
    
    // initialize credentials
    $url = 'URL';
    $ckey = 'CONSUMER KEY';
    $csecret = 'CONSUMER SECRET';
    $tkey = 'TOKEN KEY';
    $tsecret = 'TOKEN SECRET';
    
    $params = []; // GET PARAMETERS IF REQUIRED
    
    $oauth1 = new OAuth1($ckey, $csecret, $tkey, $tsecret, $url, $params);
    $finalUrl = $oauth1->getUrl();
    
    $header = $oauth1->getHeader();
    
    // if you want to pass the realm
    $header = $oauth1->getHeader('1234567');
    
    $cURLConnection = curl_init($finalUrl);
    curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($cURLConnection, CURLOPT_FRESH_CONNECT, true);
    curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
        $header,
        'Content-Type: application/json',
    ));
    
    $apiResponse = curl_exec($cURLConnection);
    curl_close($cURLConnection);
