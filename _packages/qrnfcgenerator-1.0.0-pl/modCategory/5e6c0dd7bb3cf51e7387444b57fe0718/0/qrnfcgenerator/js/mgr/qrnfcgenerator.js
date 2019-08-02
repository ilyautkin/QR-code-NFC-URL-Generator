var QRNFCGenerator = function(config) {
    config = config || {};

    QRNFCGenerator.superclass.constructor.call(this, config);
};

Ext.extend(QRNFCGenerator, Ext.Component, {
    page    : {},
    window  : {},
    grid    : {},
    tree    : {},
    panel   : {},
    combo   : {},
    config  : {}
});

Ext.reg('qrnfcgenerator', QRNFCGenerator);

QRNFCGenerator = new QRNFCGenerator();

MODx.window.NFCUrlCopyResponseWindow = function (config) {
    config = config || {};

    Ext.applyIf(config, {
        id            : 'qrnfcgenerator-response-window',
        title         : _('qrnfcgenerator.window.nfc.title'),
        html          : '<p>' + _('qrnfcgenerator.window.nfc.copy_response.success') + '</p>',
        buttons       : [{
            text: _('close'),
            handler: function () {
                this.hide();
            },
            scope: this
        }],

        saveBtnText   : 'qrnfcgenerator.import.window.btn_save',
        cancelBtnText : 'qrnfcgenerator.import.window.btn_cancel',
        autoHeight    : true,
        keys: []
    });

    MODx.window.NFCUrlCopyResponseWindow.superclass.constructor.call(this, config);
};

Ext.extend(MODx.window.NFCUrlCopyResponseWindow, MODx.Window);
Ext.reg('qrnfcgenerator-response-window', MODx.window.NFCUrlCopyResponseWindow);

Ext.onReady(function () {
    var buttons = Ext.getCmp('modx-abtn-menu-list');

    buttons.insert(4, {
        text: _('qrnfcgenerator.button.generate_qr') + '<i class="icon icon-qrcode"></i>',
        handler: function () {
            var config = {
                'action'      : 'mgr/resource/qr',
                'resource'    : MODx.request.id,
                'HTTP_MODAUTH': MODx.siteId
            };

            var parameters = [];

            for (var key in config) {
                if (config.hasOwnProperty(key)) {
                    parameters.push(key + '=' + encodeURIComponent(config[key]));
                }
            }

            // location.href = Newsletter.config.connector_url + '?action=' + this.exportListWindow.baseParams.action + '&download=1&HTTP_MODAUTH=' + MODx.siteId;

            window.open(location.origin + QRNFCGenerator.config.connector_url + '?' + parameters.join('&'),'_blank');
        }
    });

    buttons.insert(5, {
        text: _('qrnfcgenerator.button.generate_nfc') + '<i class="icon icon-rss"></i>',
        handler: function () {
            if (QRNFCGenerator.window.copyUrlWindow) {
                QRNFCGenerator.window.copyUrlWindow.destroy();
            }

            MODx.Ajax.request({
                url: QRNFCGenerator.config.connector_url,
                params: {
                    action: 'mgr/resource/nfc',
                    resource: MODx.request.id
                },
                listeners: {
                    success: {
                        fn: function (response) {
                            /* Display window with the generated NFC Tag URL. */
                            QRNFCGenerator.window.copyUrlWindow = MODx.load({
                                xtype: 'modx-window',
                                blankValues: true,
                                fields : [{
                                    xtype      : 'textfield',
                                    fieldLabel : _('qrnfcgenerator.window.nfc.url'),
                                    name       : 'nfc_url',
                                    id         : 'nfc_url',
                                    anchor     : '100%',
                                    value      : response.object.url.replace(/&amp;/g, '&'),
                                    maxLength  : 100,
                                    hidden     : false
                                }],
                                width: 500,
                                buttons: [{
                                    text: _('close'),
                                    handler: function () {
                                        QRNFCGenerator.window.copyUrlWindow.hide();
                                    }
                                },{
                                    text: _('qrnfcgenerator.copy_close'),
                                    cls: 'primary-button',
                                    handler: function () {
                                        var copyText = document.querySelector("#nfc_url");
                                        copyText.select();

                                        var copiedResponse = document.execCommand('copy');
                                        if (QRNFCGenerator.window.copyUrlResponseWindow) {
                                            QRNFCGenerator.window.copyUrlResponseWindow.destroy();
                                        }

                                        if (QRNFCGenerator.window.UnknownErrorWindow) {
                                            QRNFCGenerator.window.UnknownErrorWindow.destroy();
                                        }

                                        if (copiedResponse) {
                                            QRNFCGenerator.window.copyUrlWindow.hide();

                                            QRNFCGenerator.window.copyUrlResponseWindow = MODx.load({
                                                xtype: 'qrnfcgenerator-response-window'
                                            });

                                            QRNFCGenerator.window.copyUrlResponseWindow.show();
                                        } else {
                                            QRNFCGenerator.window.copyUrlResponseWindow = MODx.load({
                                                xtype: 'qrnfcgenerator-response-window',
                                                html: '<p>' + _('qrnfcgenerator.window.nfc.copy_response.error') + '</p>'
                                            });

                                            QRNFCGenerator.window.copyUrlResponseWindow.show();
                                        }
                                    }
                                }]
                            });

                            QRNFCGenerator.window.copyUrlWindow.show();
                        },
                        scope:this
                    },
                    failure: {
                        fn: function (response) {
                            if (QRNFCGenerator.window.UnknownErrorWindow) {
                                QRNFCGenerator.window.UnknownErrorWindow.destroy();
                            }

                            /* Show a general error if processor failed. */
                            if (!response.success) {
                                QRNFCGenerator.window.UnknownErrorWindow = MODx.load({
                                    xtype: 'qrnfcgenerator-response-window',
                                    html: '<p>' + _('qrnfcgenerator.window.error_unknown') + '</p>',
                                });

                                QRNFCGenerator.window.UnknownErrorWindow.show();

                                return;
                            }
                        }
                    }
                }
            });
        }
    });
});