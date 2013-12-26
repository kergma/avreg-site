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
    onvifProfiles.onProfileSelect(function(profile){
        var request = $.ajax({
            type: "POST",
            url: '/avreg/admin/cam_params_replace.php',
            data: $.extend({}, _cam_tune_info, onvifConnector.getConnectionInfo(), {
                profile_token: profile['token']
            }),
            dataType: 'json'
        });

        request
            .done(function(){
                // todo reload iframe
            })
            .always(function(){
            onvifProfiles.hideUi();
        })
    });

    onvifProfiles.connect()
        .done(function () {
            onvifProfiles.showUi();
        })
        .fail(function () {
            // could not get profiles - connection data missing or incorrect
            onvifConnector.setConnectionInfo(_cam_tune_info);
            onvifConnector.showUi(function () {
                // on connection success renew connection data
                onvifProfiles.setConnectionInfo(onvifConnector.getConnectionInfo());
                // get profiles from server
                onvifProfiles.connect()
                    .done(function () {
                        onvifProfiles.showUi();
                        onvifConnector.hideUi();
                    })
                    .fail(function() {
                        onvifConnector.setValidationMessage('Не удалось получить медиа профили с камеры. ' +
                            'Проверьте правильность заполнения формы и повторите запрос.')
                    })
            });
        })
}
/* vim: set expandtab smartindent tabstop=4 shiftwidth=4: */

