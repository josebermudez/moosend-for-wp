<?php
	if ( ! defined( 'WPINC' ) ) {
		die;
	}

    global $endpoint;
    global $registration_endpoint;
    global $user_site;
    global $valid_user_endpoint;

    $endpoint = 'https://gateway.services.moosend.com/';
    $user_site = "https://users-managementservice.services.moosend.com/api/site/user-id/";
    $registration_endpoint = "https://users-managementservice.services.moosend.com/api/user";
    $valid_user_endpoint = "https://users-managementservice.services.moosend.com/api/user/api-key/";

?>