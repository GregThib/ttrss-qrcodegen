<?php
use chillerlan\QRCode;

class QrcodeGen extends Plugin {
	private $host;

	function about() {
		return array(null,
			"Adds buttons that generate QR-Codes on each article",
			"GregT");
	}

	function init($host) {
		$this->host = $host;

		$host->add_hook($host::HOOK_ARTICLE_BUTTON, $this);
	}

	function get_js() {
		return file_get_contents(__DIR__ . "/init.js");
	}

	function hook_article_button($line) {
		return '<img src="plugins.local/qrcodegen/qrcode.png"
			class="tagsPic" style="cursor : pointer"
			onclick="Plugins.QRcodeGen.send('.$line["id"].')"
			title="'.__('Generate a QR Code').'" />';
	}

	function getQr() {
		?>

		<section>
			<div class='panel text-center'>
				<?php

		$sth = $this->pdo->prepare("SELECT link
			FROM ttrss_entries, ttrss_user_entries
			WHERE id = ? AND ref_id = id AND owner_uid = ?");
		$sth->execute([clean($_REQUEST['id']), $_SESSION['uid']]);

		if ($row = $sth->fetch()) {
			print '<img src="' . (new \chillerlan\QRCode\QRCode())->render($row['link']) . '" style="display: block; margin: 0 auto; width: 250px;">';
		} else {
			print '<i> '.__('Ooops! Something get wrong!').'</i>' . PHP_EOL;
		}
		?>

			</div>
		</section>

		<footer class='text-center'>
			<?= \Controls\submit_tag(__('Close this dialog')) ?>
		</footer>
		<?php
	}

	function api_version() {
		return 2;
	}
}
