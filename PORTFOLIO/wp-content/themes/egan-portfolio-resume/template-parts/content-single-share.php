<?php
    $post_url = get_permalink();
    $time_read = get_post_meta(get_the_ID(), 'crt_manage_post_metabox_time_read', true);
    $time_read = $time_read ? $time_read:'1';
?>
<div class="single-share">
    <div class="d-flex flex-row flex-sm-column justify-content-center align-content-center">
        <div id="post-progress">
            <div class=""><?php printf( esc_html__( '%1$s Min Read','egan-portfolio-resume' ), $time_read ); ?></div>
        </div>
        <div class="single-share__icon d-flex justify-content-center align-content-center mb-0 mb-sm-4">
            <ul>
                <li>
                    <a class="facebook button circle" rel="nofollow noopener" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_attr($post_url) ?>" target="_blank"><i class="fa-brands fa-facebook-f fa-fw"></i></a>
                </li>
                <li>
                    <a class="twitter button circle" rel="nofollow noopener" href="http://twitter.com/share?text=Far+far+away%2C+behind+the+word+mountains&amp;url=<?php echo esc_attr($post_url) ?>" target="_blank"><i class="fa-brands fa-x-twitter"></i></a>
                </li>
                <li>
                    <a class="email button circle" rel="nofollow noopener" href="mailto:?subject=Far+far+away%2C+behind+the+word+mountains&amp;body=<?php echo esc_attr($post_url) ?>" target="_blank"><i class="fa fa-envelope"></i></a>
                </li>
                <li>
                    <a class="whatsapp button circle" rel="nofollow noopener" href="https://api.whatsapp.com/send?text=<?php echo esc_attr($post_url) ?>" data-action="share/whatsapp/share" target="_blank"><i class="fa-brands fa-whatsapp"></i></a>
                </li>
                <li>
                    <a class="single-share__btn-js" rel="nofollow noopener" target="_blank"><i class="fa-solid fa-link"></i></a>
                </li>
            </ul>
        </div>
        <input id="single-share-url" class="single-share__url" value="<?php echo esc_html($post_url); ?>" />
    </div>
</div>
