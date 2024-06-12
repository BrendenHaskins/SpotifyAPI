<?php

/**
 * LinkHandler enables requests to Spotify's v1 API, passing correct settings to cUrl
 */
class LinkHandler extends BaseHandler
{
private string $_targetUrl;

    /**
     * @param string $targetUrl the endpoint to send the request to
     * @param string $token an API token to use
     */
    public function __construct(string $targetUrl, string $token)
    {
        $this->_targetUrl = $targetUrl;
        parent::__construct(true,'',10,0,true,
            CURL_HTTP_VERSION_1_1,'GET',array('Authorization: Bearer '.$token));
    }

    public function execute() : array {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->_targetUrl,
            CURLOPT_RETURNTRANSFER => $this->isReturnTransfer(),
            CURLOPT_ENCODING => $this->getEncoding(),
            CURLOPT_MAXREDIRS => $this->getMaxRedirects(),
            CURLOPT_TIMEOUT => $this->getTimeout(),
            CURLOPT_FOLLOWLOCATION => $this->isFollowLocation(),
            CURLOPT_HTTP_VERSION => $this->getVersion(),
            CURLOPT_CUSTOMREQUEST => $this->getRequest(),
            CURLOPT_HTTPHEADER => $this->getHeader(),
        ));

        $response = curl_exec($curl);

        if(is_string($response)){
            return json_decode($response, true);
        } else {
            echo "FATAL LinkHandler ERROR";
            return array('LinkHandler ERROR'=>null);
        }

    }



}