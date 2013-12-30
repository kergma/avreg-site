<?php

require '../lib/OnvifClient/OnvifClient.php';

$onvifClient = new OnvifClient(
    "http://$_POST[InetCam_IP]:$_POST[InetCam_http_port]/onvif/device_service",
    "http://$_SERVER[HTTP_HOST]/avreg/lib/OnvifClient/wsdl" // todo - remove hardcode
);

$onvifClient->setCredentials($_POST['InetCam_USER'], $_POST['InetCam_PASSWORD']);

// todo - store new camera params
