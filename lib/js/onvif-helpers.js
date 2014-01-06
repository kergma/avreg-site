/* onvif client's helper functions */

/**
 * Make onvif requests to get media URI.
 * @param {Object} _cam_tune_info   camera info: number, host, http_port, login, password
 */
function do_onvif_uri_req(_cam_tune_info) {

    onvifProfiles.setConnectionInfo(_cam_tune_info);
    onvifConnector.setConnectionInfo(_cam_tune_info);

    onvifProfiles.onProfileSelect(function (profile) {
        var camTuneInfo = $.extend({}, _cam_tune_info, onvifConnector.getConnectionInfo());

        var jqxhrGetMediaUri = $.ajax({
            type: "POST",
            url: WwwPrefix + '/lib/OnvifClientController.php',
            data: {
                method: 'getMediaUri',
                data: {
                    origin: 'http://' + camTuneInfo['InetCam_IP'] + ':' + camTuneInfo['InetCam_http_port'],
                    username: camTuneInfo['InetCam_USER'],
                    password: camTuneInfo['InetCam_PASSWORD'],
                    ProfileToken: profile['token'],
                    TransportProtocol: 'RTSP',
                    StreamType: 'RTP-Unicast'
                }
            },
            dataType: 'json'
        });

        jqxhrGetMediaUri
            .error(function(){
                // todo handle error
            })
            .done(function(response) {
                var jqxhrSaveParams = $.ajax({
                    type: "POST",
                    url: WwwPrefix + '/admin/cam_params_replace.php',
                    data: $.extend({}, camTuneInfo, {
                        media_uri: response['MediaUri']['Uri']
                    }),
                    dataType: 'json'
                });

                jqxhrSaveParams
                    .error(function(){
                        // todo handle error
                    })
                    .done(function (response) {
                        if ($("#iframe-index2").size()) {
                            $("#iframe-index2")[0].contentWindow.location.reload();
                        } else {
                            // if not an iframe
                            window.location.reload();
                        }
                    })
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

