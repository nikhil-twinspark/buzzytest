<?php

/**
 * This file use for authanticate to get rewards list from amazon.
 */
App::uses('Component', 'Controller');

/**
 * This controller use for authanticate to get rewards list from amazon.
 */
class ApiAmazonComponent extends Component {
    /**
     * Function for hit curl request for given url to get record.
     * @param type $data
     * @param type $api_url
     * @return type
     */
    function submit_cURL($data, $api_url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        // turn off verification of SSL for testing:
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        // Send query to server:
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    /**
     * aws authonticate request to get reward list from amazon.
     * @param type $region
     * @param type $params
     * @param type $public_key
     * @param type $private_key
     * @param type $associate_tag
     * @param type $version
     * @return string
     */
    public function aws_signed_request($region, $params, $public_key, $private_key, $associate_tag = NULL, $version = '2011-08-01') {


        /*
          Parameters:
          $region - the Amazon(r) region (ca,com,co.uk,de,fr,co.jp)
          $params - an array of parameters, eg. array("Operation"=>"ItemLookup",
          "ItemId"=>"B000X9FLKM", "ResponseGroup"=>"Small")
          $public_key - your "Access Key ID"
          $private_key - your "Secret Access Key"
          $version (optional)
         */

        // some paramters
        $method = 'GET';
        $host = 'webservices.amazon.' . $region;
        $uri = '/onca/xml';

        // additional parameters
        $params['Service'] = 'AWSECommerceService';
        $params['AWSAccessKeyId'] = $public_key;
        // GMT timestamp
        $params['Timestamp'] = gmdate('Y-m-d\TH:i:s\Z');
        // API version
        $params['Version'] = $version;
        if ($associate_tag !== NULL) {
            $params['AssociateTag'] = $associate_tag;
        }

        // sort the parameters
        ksort($params);

        // create the canonicalized query
        $canonicalized_query = array();
        foreach ($params as $param => $value) {
            $param = str_replace('%7E', '~', rawurlencode($param));
            $value = str_replace('%7E', '~', rawurlencode($value));
            $canonicalized_query[] = $param . '=' . $value;
        }
        $canonicalized_query = implode('&', $canonicalized_query);

        // create the string to sign
        $string_to_sign = $method . "\n" . $host . "\n" . $uri . "\n" . $canonicalized_query;

        // calculate HMAC with SHA256 and base64-encoding
        $signature = base64_encode(hash_hmac('sha256', $string_to_sign, $private_key, TRUE));

        // encode the signature for the request
        $signature = str_replace('%7E', '~', rawurlencode($signature));

        // create request
        $request = 'http://' . $host . $uri . '?' . $canonicalized_query . '&Signature=' . $signature;

        return $request;
    }

}

/////////////////apiAmazon end here
?>
