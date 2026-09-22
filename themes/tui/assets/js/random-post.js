import * as params from '@params';

function getRandomPost() {
  const posts = (params.posts || []).filter((p) => p !== location.pathname);
  if (!posts.length) return;
  const randomIndex = Math.floor(Math.random() * posts.length);
  window.location.href = posts[randomIndex] + '?ref=random';
}

document.querySelectorAll('.random-post-button').forEach((button) => {
  button.addEventListener('click', getRandomPost);
});
