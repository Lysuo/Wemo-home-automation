<?php

/* Callback for available device */
function device_proxy_available_cb($proxy, $arg)
{
    $info = gupnp_device_info_get($proxy);

    $type = $info['device_type'];
    $location = $info['location'];

    printf("Device available:\n");
    printf("\ttype:     %s\n", $type);
    printf("\tlocation: %s\n", $location);
}

/* Callback for available service */
function service_proxy_available_cb($proxy, $arg)
{
    $info = gupnp_service_info_get($proxy);

    $type = $info['service_type'];
    $location = $info['location'];

    printf("Service available:\n");
    printf("\ttype:     %s\n", $type);
    printf("\tlocation: %s\n", $location);
}

/* Create the UPnP context */
$context = gupnp_context_new();
if (!$context) {
    printf("Error creating the GUPnP context\n");
    exit(-1);
}

/* We're interested in everything */
$cp = gupnp_control_point_new($context, "ssdp:all");

/* Set callbacks */
gupnp_control_point_callback_set($cp, 
    GUPNP_SIGNAL_DEVICE_PROXY_AVAILABLE, 'device_proxy_available_cb');
gupnp_control_point_callback_set($cp, 
    GUPNP_SIGNAL_SERVICE_PROXY_AVAILABLE, 'service_proxy_available_cb');

/* Start for browsing (infinite loop, hit Ctrl-C to interrupt) */
gupnp_control_point_browse_start($cp);

?>