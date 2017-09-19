<?php


namespace Tebian;

use Tebian\Exceptions\ApiException;

class Api
{

    private $config;
    private $response;

    /**
     * Api constructor.
     *
     * @param null $config
     * @throws ApiException
     */
    public function __construct($config = null) {
        if(!isset($config))
            throw new ApiException('Cant generate Tebian Api Object: config not set yet!!!');
        $this->config = $config;
    }

    /**
     * this function executes api call and retrieves result
     *
     * @param $to
     * @param $msg
     * @return mixed
     * @throws ApiException
     * @internal param $api_call
     * @internal param $message
     */
    public function send($to, $msg) {

        $msg = htmlentities($msg);
        $msg = str_replace(' ', '+', $msg);
        $url = $this->config['base_url'].'?Message='.$msg.'&Receiver='.$to;
        $headers = [
            'userToken: '.$this->config['user_token']
        ];

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_exec($curl);

        $response = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curl_errno = curl_errno($curl);
        $curl_error = curl_error($curl);
        if( $curl_errno ) {
            throw new ApiException($curl_error, $curl_errno);
        }
        else if( $code != 200 ) {
            throw new ApiException("Request has errors", $code);
        }

        $this->response = $response;
        $res = json_decode($response, TRUE);
        if( isset($res['type']) && $res['type'] == 'error' ) {
            throw new ApiException($res['message'], '500');
        }

        return $res;

    }

    public function getRawRes(){
        return $this->response;
    }
}
?>
