<?php
get_header();
?>
<div class="front">
    <div class="container">
        <div class="front__columns">
            <div class="front__content">
                <h1 class="front__title">Статьи</h1>
                <div class="front__post-list">
                    <?php
                    $front_query = new WP_Query(
                        [
                            'post_type'           => 'post',
                            'posts_per_page'      => 10,
                            'orderby'             => 'date',
                            'order'               => 'DESC',
                            'paged'               => max(1, (int) get_query_var('paged')),
                            'ignore_sticky_posts' => true,
                        ]
                    );

                    if ($front_query->have_posts()) :
                        while ($front_query->have_posts()) :
                            $front_query->the_post();
                            get_template_part('partials/post-preview');
                        endwhile;
                        wp_reset_postdata();
                    endif;
                    ?>
                </div>
                <div class="front__pagination">
                    <?php theme_the_pagination(['wp_query' => $front_query]); ?>
                </div>
            </div><!--/.front-content-->
            <aside class="front__sidebar">
                <h2 class="page-section-title front-sidebar__title">Sidebar</h2>
            </aside>
        </div><!--/.front__columns-->
    </div>
</div>
<?php
get_footer();
