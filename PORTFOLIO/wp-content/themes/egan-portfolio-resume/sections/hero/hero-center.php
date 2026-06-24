<?php
    $prefix = 'crt_manage_hero_';
    $hero_img = get_theme_mod($prefix . 'img');
    $hero_label = get_theme_mod($prefix . 'label');
    $hero_works = get_theme_mod($prefix . 'works');
    $hero_description = get_theme_mod($prefix . 'description');
    $hero_btn_text = get_theme_mod($prefix . 'btn_text');
    $hero_btn_url = get_theme_mod($prefix . 'btn_url');
    $hero_works = egan_portfolio_resume_array_to_string($hero_works);
?>
<div class="col-12 col-md-8 offset-0 offset-md-2">
    <div class="">
        <figure class="hero__img ratio11 lazy w-25 mx-auto" data-src="<?php echo esc_attr($hero_img); ?>"></figure>
    </div>
    <div class="ps-0 ps-md-4 text-center mt-3">
        <h2 class="hero__name"><?php echo esc_html($hero_label); ?></h2>
        <div class="hero__works d-block type--js" data-period="2000" data-type='[<?php echo esc_html($hero_works); ?>]'></div>
        <div class="hero__intro"><?php echo wp_kses_post($hero_description); ?></div>
        <div class="hero__button">
            <a href="<?php echo esc_attr($hero_btn_url); ?>"><?php echo esc_html($hero_btn_text); ?></a>
        </div>
    </div>
</div>