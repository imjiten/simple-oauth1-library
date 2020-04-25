<?php

class OAuth1
{
    /**
     * @var string
     */
    private $secret;
    /**
     * @var string
     */
    private $url;
    /**
     * @var array
     */
    private $params;
    /**
     * @var array
     */
    private $oauthParams;
    /**
     * @var string
     */
    private $urlMethod = 'GET';

    /**
     * OAuth1 constructor.
     *
     * @param $consumerKey
     * @param $consumerSecret
     * @param $tokenKey
     * @param $tokenSecret
     * @param $url
     * @param $params
     */
    public function __construct($consumerKey, $consumerSecret, $tokenKey, $tokenSecret, $url, $params)
    {
        $this->oauthParams = array(
            'oauth_consumer_key'     => $consumerKey,
            'oauth_token'            => $tokenKey,
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp'        => time(),
            'oauth_nonce'            => md5(mt_rand()),
            'oauth_version'          => '1.0',
        );
        $this->secret = rawurlencode($consumerSecret) . '&' . rawurlencode($tokenSecret);
        $this->url = $url;
        $this->params = $params;
    }

    /**
     * Get url.
     */
    public function getUrl()
    {
        $queryParams = [];
        foreach ($this->params as $key => $value) {
            $queryParams[] = "$key=$value";
        }

        return $this->url . '?' . implode('&', $queryParams);
    }

    /**
     * @param null|string $realm
     * @return string
     */
    public function getHeader($realm = null)
    {
        $headerArray = [];
        if (!empty($realm)) {
            $headerArray[] = 'realm="' . $realm . '"';
        }
        foreach ($this->oauthParams as $key => $value) {
            $headerArray[] = $key . '="' . $value . '"';
        }
        $headerArray[] = 'oauth_signature="' . rawurlencode($this->generateSignature()) . '"';

        return 'Authorization: OAuth ' . implode(',', $headerArray);
    }

    /**
     * @return string
     */
    private function getBaseString()
    {
        $baseArray = [];
        $params = array_merge($this->params, $this->oauthParams);
        ksort($params);
        foreach ($params as $key => $value) {
            $baseArray[] = rawurlencode($key) . '=' . rawurlencode($value);
        }
        $baseString = implode('&', $baseArray);

        return $this->urlMethod . '&' . rawurlencode($this->url) . '&' . rawurlencode($baseString);
    }

    /**
     * @return string
     */
    private function generateSignature()
    {
        return base64_encode(hash_hmac('sha1', $this->getBaseString(), $this->secret, true));
    }
}
