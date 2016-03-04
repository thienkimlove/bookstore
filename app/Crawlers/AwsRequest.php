<?php

namespace App\Crawlers;


class AwsRequest
{
   /*
    * http://webservices.amazon.com/onca/xml?
  Service=AWSECommerceService
  &Operation=ItemLookup
  &ResponseGroup=Large
  &SearchIndex=All
  &IdType=ISBN
  &ItemId=076243631X
  &AWSAccessKeyId=[Your_AWSAccessKeyID]
  &AssociateTag=[Your_AssociateTag]
  &Timestamp=[YYYY-MM-DDThh:mm:ssZ]
  &Signature=[Request_Signature]
    */

   protected static function awsQuery($extraParams) {
        $private_key = env('AWSSecretKey');

        $method = "GET";
        $host = "webservices.amazon.com";
        $uri = "/onca/xml";

        $params = array(
            "Service" => "AWSECommerceService",
            "AWSAccessKeyId" => env('AWSAccessKeyId'),
            "Timestamp" => gmdate("Y-m-d\TH:i:s\Z"),
            "SignatureMethod" => "HmacSHA256",
            "SignatureVersion" => "2",
            "Version" => "2013-08-01"
        );

        foreach ($extraParams as $param => $value) {
            $params[$param] = $value;
        }

        ksort($params);

        // sort the parameters
        // create the canonicalized query
        $canonicalized_query = array();
        foreach ($params as $param => $value) {
            $param = str_replace("%7E", "~", rawurlencode($param));
            $value = str_replace("%7E", "~", rawurlencode($value));
            $canonicalized_query[] = $param . "=" . $value;
        }
        $canonicalized_query = implode("&", $canonicalized_query);

        // create the string to sign
        $string_to_sign =
            $method . "\n" .
            $host . "\n" .
            $uri . "\n" .
            $canonicalized_query;

        // calculate HMAC with SHA256 and base64-encoding
        $signature = base64_encode(
            hash_hmac("sha256", $string_to_sign, $private_key, True));

        // encode the signature for the equest
        $signature = str_replace("%7E", "~", rawurlencode($signature));

        // Put the signature into the parameters
        $params["Signature"] = $signature;
        uksort($params, "strnatcasecmp");

        // TODO: the timestamp colons get urlencoded by http_build_query
        //       and then need to be urldecoded to keep AWS happy. Spaces
        //       get reencoded as %20, as the + encoding doesn't work with
        //       AWS
        $query = urldecode(http_build_query($params));
        $query = str_replace(' ', '%20', $query);

        $string_to_send = "https://" . $host . $uri . "?" . $query;

        return $string_to_send;
    }

   public static function awsItemLookup($itemId) {
        return self::awsQuery(array (
            "AssociateTag" => "Books",
            "Operation" => "ItemLookup",
            "IdType" => "ISBN",
            "ItemId" => $itemId,
            "SearchIndex" => "All",
            "ResponseGroup" => "Large"
        ));
    }
}