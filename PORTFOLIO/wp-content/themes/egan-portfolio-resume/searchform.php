<?php
/**
 * The template for displaying search forms in Dry Cleaning Services
 *
* @package Egan_Portfolio_Resume
 */
?>

<form method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search', 'placeholder','egan-portfolio-resume' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
	</label>
    <button type="submit" class="search-submit"><i class="fa-solid fa-magnifying-glass"></i></button>
</form>