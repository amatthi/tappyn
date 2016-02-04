<?php

// #### Delete Web Profile
// Use this call to delete a web experience profile.
// Documentation available at https://developer.paypal.com/webapps/developer/docs/api/#delete-a-web-experience-profile

// We are going to re-use the sample code from CreateWebProfile.php.
// If you have not visited the sample yet, please visit it before trying GetWebProfile.php
// The CreateWebProfile.php will create a web profile for us, and return a CreateProfileResponse,
// that contains the web profile ID.
/** @var \PayPal\Api\CreateProfileResponse $result */
$createProfileResponse = require_once 'CreateWebProfile.php';

// Create a new instance of web Profile ID, and set the ID.
$webProfile = new \PayPal\Api\WebProfile();
$webProfile->setId($createProfileResponse->getId());

try {
    // Execute the delete method
    $webProfile->delete($apiContext);
} catch (\PayPal\Exception\PPConnectionException $ex) {
    ResultPrinter::printError("Deleted Web Profile", "Web Profile", $createProfileResponse->getId(), null, $ex);
    exit(1);
}

ResultPrinter::printResult("Deleted Web Profile", "Web Profile", $createProfileResponse->getId(), null, null);
