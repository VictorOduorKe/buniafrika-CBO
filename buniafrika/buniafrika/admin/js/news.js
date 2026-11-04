function toggleReadMore(button) {
    const card = button.closest('.js-article-card') || button.closest('.news-card');
    if (!card) return;
    const contentDiv = card.querySelector('.js-article-full-content');
    if (!contentDiv) return;

    const isHidden = contentDiv.style.display === 'none' || contentDiv.style.display === '';

    if (isHidden) {
        contentDiv.style.display = 'block';
        button.textContent = 'Collapse Story';
        card.classList.add('is-expanded');
    } else {
        contentDiv.style.display = 'none';
        button.textContent = 'Read Full Story';
        card.classList.remove('is-expanded');
        contentDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
}

    function searchArticles() {
        const query = document.getElementById('search-input').value.toLowerCase().trim();
        const cards = document.querySelectorAll('.js-article-card');
        let count = 0;

        cards.forEach(card => {
            const title = card.dataset.title;
            const summary = card.dataset.summary;
            const category = card.dataset.category;

            if (title.includes(query) || summary.includes(query) || category.includes(query)) {
                card.style.display = 'flex';
                count++;
            } else {
                card.style.display = 'none';
            }
        });

        let emptyMsg = document.getElementById('search-empty-msg');
        if (count === 0) {
            if (!emptyMsg) {
                emptyMsg = document.createElement('p');
                emptyMsg.id = 'search-empty-msg';
                emptyMsg.className = 'text-center';
                emptyMsg.style.padding = '48px';
                emptyMsg.style.color = 'var(--on-surface-variant)';
                emptyMsg.textContent = 'No matching articles found.';
                document.getElementById('articles-container').appendChild(emptyMsg);
            }
        } else {
            if (emptyMsg) emptyMsg.remove();
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const newsForm = document.getElementById('sidebar-newsletter-form');
        if (newsForm) {
            newsForm.addEventListener('submit', (e) => {
                if (typeof handleFormSubmit === 'function') {
                    handleFormSubmit(e, 'process_newsletter.php');
                } else {
                    e.preventDefault();
                    // Fallback if form_handler.js is not loaded
                    const targetUrl = (typeof getApiEndpoint === 'function') ? getApiEndpoint('process_newsletter.php') : 'process_newsletter.php';
                    fetch(targetUrl, {
                        method: 'POST',
                        body: new FormData(newsForm)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert(data.message);
                            newsForm.reset();
                        } else {
                            alert(data.message || 'An error occurred.');
                        }
                    })
                    .catch(() => alert('Network error.'));
                }
            });
        }
    });