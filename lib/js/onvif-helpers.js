/* onvif client's helper functions */

/**
 * Make onvif requests to get media URI.
 * @param netcam_info camera info: number, host, http_port, login, password
 */
function do_onvif_uri_req(_cam_tune_info) {
    // DEBUG start
/*    _cam_tune_info = {
     InetCam_IP: "91.202.204.81",
     InetCam_PASSWORD: "antiplaka",
     InetCam_USER: "noidea",
     InetCam_http_port: 80
    }*/
    // DEBUG end

    onvifProfiles.setConnectionInfo(_cam_tune_info);
    onvifConnector.setConnectionInfo(_cam_tune_info);
    onvifProfiles.onProfileSelect(function (profile) {
        var request = $.ajax({
            type: "POST",
            url: '/avreg/admin/cam_params_replace.php',
            data: $.extend({}, _cam_tune_info, onvifConnector.getConnectionInfo(), {
                profile_token: profile['token']
            }),
            dataType: 'json'
        });

        request
            .always(function () {
                if ($("#iframe-index2").size()) {
                    $("#iframe-index2")[0].contentWindow.location.reload();
                } else {
                    // if not an iframe
                    window.location.reload();
                }
            })
    });

    onvifProfiles.connect(true)
        .done(function () {
            onvifProfiles.showUi();
        })
        .fail(function () {
            // could not get profiles - connection data missing or incorrect
            onvifConnector.showUi(function () {
                // on connection success renew connection data
                onvifProfiles.setConnectionInfo(onvifConnector.getConnectionInfo());
                // hide connection ui
                onvifConnector.hideUi();
                // query profiles from server
                onvifProfiles.connect(true)
                    .done(function () {
                        onvifProfiles.showUi();
                    })
                    .fail(function () {
                        onvifConnector.showUi();
                        onvifConnector.setValidationMessage('Не удалось получить медиа профили с камеры. ' +
                            'Проверьте правильность заполнения формы и повторите запрос.')
                    })
            });
        })
}
/* vim: set expandtab smartindent tabstop=4 shiftwidth=4: */

