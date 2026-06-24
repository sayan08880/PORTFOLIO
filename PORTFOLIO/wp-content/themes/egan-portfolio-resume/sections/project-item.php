<?php
    $item = $args;
    $attr_class = '';
    $attr_href = '';
    $attr_target = '';
    if(isset($item['project_type'])) {
        if($item['project_type'] == 'video') {
            $attr_href = $item['project_url_video'];
            if(!empty($item['project_url_video'])) {
                $attr_class = 'button-iframe';
            }
        } elseif ($item['project_type'] == 'image') {
            $attr_href = $item['project_image'];
            if(!empty($item['project_image'])) {
                $attr_class = 'button-image';
            }
        } elseif($item['project_type'] == 'url') {
            $attr_class = '';
            $attr_href = $item['project_url'];
            $attr_target = '_blank';
        }
    } else {
        $attr_href = $item['project_image'];
        if(!empty($item['project_image'])) {
            $attr_class = 'button-image';
        }
    }
?>
<div class="project__item <?php echo isset($item['project_type']) ? 'project__item--' . $item['project_type']:'' ?>">
    <div class="project__item--inner">
        <a class="<?php echo esc_attr($attr_class) ?>" target="<?php echo esc_attr($attr_target) ?>" href="<?php echo esc_attr($attr_href); ?>">
            <figure class="ratio ratio11 lazy" data-src="<?php echo esc_attr($item['project_image']); ?>">
                <?php if($item['project_type'] == 'video'): ?>
                    <i class="fa-solid fa-play"></i>
                <?php elseif($item['project_type'] == 'url'): ?>
                    <i class="fa-solid fa-link"></i>
                <?php else: ?>
                    <i class="fa-solid fa-image"></i>
                <?php endif; ?>
            </figure>
            <div class="d-flex justify-content-between align-items-center">
                <div class="project__content">
                    <p class="project__cat mt-3 mb-1"><?php echo esc_html($item['project_category']); ?></p>
                    <h3 class="project__name mt-0 mb-2"><?php echo esc_html($item['project_name']); ?></h3>
                </div>
                <i class="project__item--arrow fa-solid fa-arrow-right"></i>
            </div>
        </a>
    </div>
</div>
