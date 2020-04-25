<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

ob_start();
include __DIR__ . '/settings-field-advanced.php';
$content = ob_get_clean();
$lines = explode( "\n", $content );
?>
<?php foreach ( $lines as $line ) : ?>
	html += '<?php echo $line; ?>';
<?php endforeach; ?>