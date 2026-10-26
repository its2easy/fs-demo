document.addEventListener('DOMContentLoaded', () => {
    // Send request on button click, update a counter
    // No state indication (liked, disliked) by design
	const likeButtons = document.querySelectorAll('[data-js="like-action"]');
    const ajaxAction = 'theme_post_likes';
    const negative_likes_class = 'negative';

	likeButtons.forEach((button) => {
		button.addEventListener('click', () => {
			const likesBlock = button.closest('[data-js="post-likes"]');
			const countEl = likesBlock.querySelector('[data-js="like-count"]');

			const formData = new FormData();
			formData.append('action', ajaxAction);
			formData.append('post_id', likesBlock.dataset.postId);
			formData.append('action_type', button.dataset.action);

			fetch(phpData.ajax_url, {
				method: 'POST',
				body: formData,
			})
				.then((response) => {
					if (!response.ok) {
						console.error(response);
						return null;
					}
					return response.text();
				})
				.then((data) => {
					if (data === null)  return;

					const likesCount = parseInt(data, 10);
					countEl.textContent = likesCount;
					countEl.classList.toggle(negative_likes_class, likesCount < 0);
				})
				.catch((error) => console.error(error));
		});
	});
});
