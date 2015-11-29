<?php

die('this file contains a tutorial and is not intended for execution!');

/**
 * This tutorial helps you getting started with the BillSAFE SDK.
 *
 * We assume that you have read the "BillSAFE Integration Guide" which explains
 * all methods provided by the BillSAFE API as well as the required parameters
 * and the responses.
 *
 * The BillSAFE SDK encapsulates the NVP protocol that is internally used for
 * communication with the BillSAFE API server. This means you don't have to
 * deal with the NVP protocol at all. In order to invoke an API method all
 * you have to do is assemble the required parameters and use the SDK's
 * callMethod()-function. callMethod() returns a stdClass-object which contains
 * the API response in a structured manner.
 *
 * The SDK requires PHP 5 (or above) with support for SSL. It has no
 * dependencies to third party libraries.
 *
 * To get up and running with the SDK follow these steps:
 *
 * 1.
 * Place the "Billsafe" folder somewhere on your webserver and make sure it is
 * not accessible directly from the web.
 *
 * 2.
 * Add the path to the "Billsafe" folder to your PHP include path.
 */
require('vendor/autoload');

/**
 * 3.
 * Edit the billsafe_ini.php file which was shipped with the SDK and insert your
 * API credentials etc. Leave "isLiveMode" set to false whilst you are developing
 * and testing your application! Only set this value to true after everything is
 * working in sandbox-mode and BillSAFE has explicitly approved your application.
 * Make sure to update the "applicationVersion" value every time you release a
 * new version of your application. This helps BillSAFE to localize errors
 * that might occur only in specific releases. BillSAFE recommends to include
 * a release number as well as a release date, e.g. "v.1.2 beta (2010-12-29)".
 *
 * 4.
 * Create a Sdk object. Optionally you may provide the path to the
 * billsafe_ini.php file in the constructor. If you omit it, the default
 * billsafe_ini.php file located in the sdk folder will be used.
 */
$bs = new ErnestWisniewski\Billsafe\Sdk('/path/to/billsafe_ini.php');

/**
 * If you do not want to make use of the billsafe_ini.php file, you may
 * alternatively stick your API credentials into setCredentials().
 */
$bs = new ErnestWisniewski\Billsafe\Sdk();

$bs->setCredentials(array(
    'merchantId'             => '12345678',
    'merchantLicenseSandbox' => '05798d947329d390efaa9f4d5416de4b',
    'merchantLicenseLive'    => '28d849adee95f5583b94aeab523be325',
    'applicationSignature'   => 'bfa72dbb71729f3cecd83523882d0f66',
    'applicationVersion'     => 'v1.7 beta (2010-12-31)'));

/**
 * To see what information is sent to the BillSAFE API server and what the
 * response looks like, you can inject a logger object. This might be useful
 * for debugging during development. You can either provide your own logger
 * class (which must implement the interface Billsafe_Logger) or use one of the
 * Billsafe_LoggerXXX classes that come with the SDK.
 */
$bs->setLogger(new ErnestWisniewski\Billsafe\Logger\LoggerEcho());

#require 'Billsafe/LoggerFile.php';
#$bs->setLogger(new Billsafe\Logger\LoggerFile('/path/to/your/logfile.log'));

#require 'Billsafe/LoggerMail.php';
#$bs->setLogger(new Billsafe\Logger\LoggerMail('you@your-shop.com'));

/**
 * 5.
 * To invoke an API method simply use the callMethod() function which requires
 * two arguments.
 *   1. The name of the API method to invoke
 *   2. An array or object containing the parameters for that API method
 * The API parameters can be specified in various ways. The simplest but
 * probably not the most convenient way is to specify the parameters exactly
 * as mentioned in the "BillSAFE Integration Guide". Here is an example for
 * the prepareOrder() API method:
 */
$params = array(
    'order_number'              => '12345',
    'order_amount'              => 27.35,
    'order_taxAmount'           => 4.37,
    'order_currencyCode'        => 'EUR',
    'customer_id'               => '55555',
    'customer_gender'           => 'm',
    'customer_firstname'        => 'Max',
    'customer_lastname'         => 'Mustermann',
    'customer_street'           => 'Musterweg',
    'customer_houseNumber'      => '12 a',
    'customer_postcode'         => '12345',
    'customer_city'             => 'Musterstadt',
    'customer_country'          => 'DE',
    'customer_dateOfBirth'      => '1982-12-31',
    'customer_email'            => 'max.muster@example.de',
    'customer_phone'            => '0123/12345678',
    'product'                   => 'invoice',
    'url_return'                => 'http://your-shop.com/return',
    'url_cancel'                => 'http://your-shop.com/cancel',
    'url_image'                 => 'http://your-shop.com/logo.jpg',
    'articleList_0_number'      => '1A',
    'articleList_0_name'        => 'Testartikel 1A',
    'articleList_0_description' => 'Testbeschreibung von Artikel 1A',
    'articleList_0_type'        => 'goods',
    'articleList_0_quantity'    => 2,
    'articleList_0_netPrice'    => 5.19,
    'articleList_0_tax'         => 19,
    'articleList_1_number'      => '2B',
    'articleList_1_name'        => 'Testartikel 2B',
    'articleList_1_description' => 'Testbeschreibung von Artikel 2B',
    'articleList_1_type'        => 'goods',
    'articleList_1_quantity'    => 1,
    'articleList_1_netPrice'    => 8.82,
    'articleList_1_tax'         => 19,
    'articleList_2_number'      => 'VERSAND',
    'articleList_2_name'        => 'Verpackungs- und Versandpauschale',
    'articleList_2_description' => 'versicherter Versand mit DHL',
    'articleList_2_type'        => 'shipment',
    'articleList_2_quantity'    => 1,
    'articleList_2_netPrice'    => 3.78,
    'articleList_2_tax'         => 19,
    'custom_0'                  => 'Erster benutzerdefinierter Wert',
    'custom_1'                  => 'Zweiter benutzerdefinierter Wert',
    'custom_2'                  => 'Dritter benutzerdefinierter Wert');

$response = $bs->callMethod('prepareOrder', $params);

/**
 * As you can see, some of the flat parameters actually have a structured
 * meaning. The hierarchy levels are seperated by underscores "_". Thus
 * "customer_firstname" contains the firstname of the customer and
 * "articleList_2_name" contains the name of the third article of the article
 * list. If you prefer to provide the parameters in a structured way, you can
 * easily do so. In the following example we keep most of the previous snippet
 * but provide customer and articleList as structured arrays instead. Note that
 * we could use objects as well.
 */
$params = array(
    'order_number'        => '12345',
    'order_amount'        => 27.35,
    'order_taxAmount'     => 4.37,
    'order_currencyCode'  => 'EUR',
    'customer'            => array(
        'id'              => '55555',
        'gender'          => 'm',
        'firstname'       => 'Max',
        'lastname'        => 'Mustermann',
        'street'          => 'Musterweg',
        'houseNumber'     => '12 a',
        'postcode'        => '12345',
        'city'            => 'Musterstadt',
        'country'         => 'DE',
        'dateOfBirth'     => '1982-12-31',
        'email'           => 'max.muster@example.de',
        'phone'           => '0123/12345678'),
    'product'             => 'invoice',
    'url_return'          => 'http://your-shop.com/return',
    'url_cancel'          => 'http://your-shop.com/cancel',
    'url_image'           => 'http://your-shop.com/logo.jpg',
    'articleList'         => array(
        array(
            'number'      => '1A',
            'name'        => 'Testartikel 1A',
            'description' => 'Testbeschreibung von Artikel 1A',
            'type'        => 'goods',
            'quantity'    => 2,
            'netPrice'    => 5.19,
            'tax'         => 19),
        array(
            'number'      => '2B',
            'name'        => 'Testartikel 2B',
            'description' => 'Testbeschreibung von Artikel 2B',
            'type'        => 'goods',
            'quantity'    => 1,
            'netPrice'    => 8.82,
            'tax'         => 19),
        array(
            'number'      => 'VERSAND',
            'name'        => 'Verpackungs- und Versandpauschale',
            'description' => 'versicherter Versand mit DHL',
            'type'        => 'shipment',
            'quantity'    => 1,
            'netPrice'    => 3.78,
            'tax'         => 19)),
    'custom_0'            => 'Erster benutzerdefinierter Wert',
    'custom_1'            => 'Zweiter benutzerdefinierter Wert',
    'custom_2'            => 'Dritter benutzerdefinierter Wert');

$response = $bs->callMethod('prepareOrder', $params);
var_dump($response);

/**
 * 6.
 * callMethod() returns a stdClass which contains the API response in a
 * structured manner. The output of the above var_dump($response) looks
 * like this:
 *
 * object(stdClass)#1 (2) {
 *    ["ack"]=>
 *    string(2) "OK"
 *    ["token"]=>
 *    string(26) "4d2db2648e7155d2db2648e7ef"
 *  }
 *
 * In case an error occurred, the output would look something like this:
 *
 * object(stdClass)#1 (2) {
 *   ["ack"]=>
 *   string(5) "ERROR"
 *   ["errorList"]=>
 *   array(1) {
 *     [0]=>
 *     object(stdClass)#2 (2) {
 *       ["code"]=>
 *       string(3) "215"
 *       ["message"]=>
 *       string(30) "Parameters of customer missing"
 *     }
 *   }
 * }
 *
 * The "BillSAFE Integration Guide" describes the responses of API
 * methods without considering the hierachy. For example the
 * getTransactionResult API method is described as returning the
 * following elements:
 *
 * - status
 * - declineReason_code
 * - declineReason_message
 * - transactionId
 * - customer_gender
 * - customer_firstname
 * - customer_lastname
 * - customer_street
 * - customer_houseNumber
 * - customer_postcode
 * - customer_city
 * - customer_country
 * - customer_dateOfBirth
 * - customer_email
 * - customer_phone
 * - custom_{n}
 *
 * However the SDK converts the flat elements back into the intended
 * structure by interpreting the underscores as separators of the
 * hierarchy levels. Therefore a var_dump() of the response of the
 * getTransactionResult API method will look something like this:
 *
 * object(stdClass)#1 (2) {
 *   ["ack"]=>
 *   string(5) "OK"
 *   ["status"]=>
 *   string(8) "ACCEPTED"
 *   ["transactionId"]=>
 *   string(10) "9876543210"
 *   ["customer"]=>
 *   object(stdClass)#2 (11) {
 *     ["gender"]=>
 *     string(1) "m"
 *     ["firstname"]=>
 *     string(3) "Max"
 *     ["lastname"]=>
 *     string(10) "Mustermann"
 *     ["street"]=>
 *     string(9) "Musterweg"
 *     ["houseNumber"]=>
 *     string(4) "12 a"
 *     ["postcode"]=>
 *     string(5) "12345"
 *     ["city"]=>
 *     string(11) "Musterstadt"
 *     ["country"]=>
 *     string(2) "DE"
 *     ["dateOfBirth"]=>
 *     string(10) "1982-12-31"
 *     ["email"]=>
 *     string(26) "max.mustermann@example.com"
 *     ["phone"]=>
 *     string(12) "0123/4567890"
 *   }
 *   ["custom"]=>
 *   array(3) {
 *     [0]=>
 *     string(19) "Benutzerdefiniert 1"
 *     [1]=>
 *     string(19) "Benutzerdefiniert 2"
 *     [2]=>
 *     string(19) "Benutzerdefiniert 3"
 *   }
 * }
 *
 * Note that the customer_XXX elements have become attributes of a
 * customer stdClass object and that custom_{n} has been transformed
 * into an array of strings.
 * The declineReason_XXX elements are not part of the response here,
 * since they are optional and depend on the "status" attribute.
 *
 *
 * A response always contains the attribute "ack" as an
 * acknowledgement status which is either "OK" or "ERROR".
 *
 * The following example shows how to check whether the API method was
 * executed successfully and how to print out any error message that might
 * have occurred.
 */
if ($response->ack == 'OK')
{
    echo "method was executed successfully!";
}
else
{
    echo "at least one error occurred! <br />\n";
    
    foreach ($response->errorList as $error)
    {
        echo "Error Code " . $error->code . " / " . $error->message . "<br />\n";
    }
}

/**
 * Please note that callMethod() might throw exceptions, e.g. if the network connection
 * could not be established. Therefore it's a good idea to put it inside a try/catch
 * block as shown in the next example. The example also demonstrates how the result of
 * a transaction can be retrieved using the token that has previously been returned by
 * prepareOrder:
 */
try
{
    $params = array('token' => '4d2db2648e7155d2db2648e7ef');
    
    $response = $bs->callMethod('getTransactionResult', $params);
    
    if ($response->status == 'ACCEPTED')
    {
        //... store order etc.
        
        echo "BillSAFE has accepted the transaction. Thank you for your order!";
    }
    else 
    {
        echo "Currently BillSAFE cannot be chosen as a payment method. Please pick another one!";
    }
}
catch (ErnestWisniewski\Billsafe\Exception\BillsafeException $e)
{
    echo "an exception occurred! Message: " . $e->getMessage();

    //log exception, inform admin etc.
}

/**
 * Apart from the callMethod function the SDK contains one convenience function
 * which performs an HTTP redirect onto the BillSAFE Payment Gateway. The redirection
 * is part of the payment workflow as described in the "BillSAFE Integration Guide".
 * Simply provide the token that was contained in the response of the prepareOrder
 * function.
 */
$bs->redirectToPaymentGateway($token);