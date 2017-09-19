<?php


namespace Tebian;

use Tebian\Exceptions\ApiException;

class Api
{

    private $config;
    private $response;
    private $res;

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
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curl_errno = curl_errno($curl);
        $curl_error = curl_error($curl);
        $this->res['response'] = $response;
        $this->res['code'] = $code;
        $this->res['curl_errno'] = $curl_errno;
        $this->res['curl_error'] = $curl_error;

        if($response===false){
            throw new ApiException('Tebian Api has error');
        }

        if((string)$code!=='200'){
            $r = json_decode($response);
            throw new ApiException(html_entity_decode($r['ExceptionMessage']));
        }


        return true;

    }

    public function getRawRes(){
        return $this->res;
    }
}
?>
