/* global Plugins, xhr, dojo, fox, __ */

Plugins.QRcodeGen = {
	send: function (id) {
		try {
			const dialog = new fox.SingleUseDialog({
				title: __("QR code for article"),
				content: __("Loading, please wait...")
			});

			const tmph = dojo.connect(dialog, 'onShow', function () {
				dojo.disconnect(tmph);
				xhr.post("backend.php", App.getPhArgs("qrcodegen", "getQr", {id: id}), (reply) => {
					if (reply) {
						dialog.attr('content', reply);
					} else {
						Notify.error("<strong>Error encountered while initializing the QrCodeGen Plugin!</strong>", true);
					}
				});
			});

			dialog.show();
		} catch (e) {
			Notify.error("QRcodeGen", e);
		}
	}
};
