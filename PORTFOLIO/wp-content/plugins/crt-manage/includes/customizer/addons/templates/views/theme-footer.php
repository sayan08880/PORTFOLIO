<?php
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$conditions = json_decode( get_option('crt_footer_conditions', '[]'), true );
//$template_slug = CRT_Conditions_Manager::header_footer_display_conditions($conditions);
$template_id = Utilities::crt_manage_get_header_footer_id('crt_manage_footer');
Utilities::render_elementor_template_id($template_id);

wp_footer();

?>

</body>
</html> 