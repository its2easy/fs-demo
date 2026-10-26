<?php
$post_id       = get_the_ID();
$votes_summary = $args['votes_summary'] ?? null;
if ($votes_summary !== null) {
	$likes_sum = ($votes_summary[$post_id]['likes'] ?? 0) - ($votes_summary[$post_id]['dislikes'] ?? 0);
} else {
	$likes_sum = Theme_Likes_Repository::get_sum($post_id); // fallback
}
?>
<article <?php post_class('post-preview'); ?>>
	<?php if (has_post_thumbnail()) : ?>
        <a class="post-preview__image-link" href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail('large', ['class' => 'post-preview__image']); ?>
        </a>
	<?php endif; ?>

    <div class="post-preview__body">
        <h2 class="post-preview__title">
            <a class="post-preview__title-link" href="<?php the_permalink(); ?>">
				<?php the_title(); ?>
            </a>
        </h2>

        <div class="post-preview__excerpt">
			<?php the_excerpt(); ?>
        </div>

        <div class="post-preview__meta">
            <div class="post-preview__author">
                Автор:
                <span class="post-preview__author-name"><?php the_author(); ?></span>
            </div>

            <div class="post-preview__likes" data-js="post-likes" data-post-id="<?= $post_id; ?>">
                <button class="post-preview__likes-btn post-preview__likes-btn_plus" type="button" aria-label="Нравится"
                        title="Нравится" data-js="like-action" data-action="like">+
                </button>
                <span class="post-preview__likes-count<?= $likes_sum < 0 ? ' negative' : ''; ?>" data-js="like-count">
                    <?= $likes_sum; ?>
                </span>
                <button class="post-preview__likes-btn post-preview__likes-btn_minus" type="button"
                        aria-label="Не нравится" title="Не нравится" data-js="like-action" data-action="dislike">&minus;
                </button>
            </div>
        </div>
    </div><!--/.post-preview__body-->
</article>
