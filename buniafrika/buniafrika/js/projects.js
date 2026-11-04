 function filterProjects(category) {
        const cards = document.querySelectorAll('.js-project-card');
        const buttons = document.querySelectorAll('.js-filter-btn');

        buttons.forEach(btn => btn.classList.remove('active'));

        const activeBtn = Array.from(buttons).find(btn =>
            btn.getAttribute('onclick').includes("'" + category + "'")
        );
        if (activeBtn) activeBtn.classList.add('active');

        cards.forEach(card => {
            const cardCat = card.dataset.category.toLowerCase();
            if (category === 'all' || cardCat === category.toLowerCase()) {
                card.style.display = 'flex';
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'scale(1)';
                }, 10);
            } else {
                card.style.opacity = '0';
                card.style.transform = 'scale(0.95)';
                setTimeout(() => { card.style.display = 'none'; }, 150);
            }
        });
    }

    // Mirrors news.js toggleReadMore() — uses closest()+querySelector() for reliability
    function toggleProjectDesc(button) {
        const block    = button.closest('.js-proj-block');
        const excerpt  = block.querySelector('.proj-excerpt');
        const fullDesc = block.querySelector('.proj-full');
        const isHidden = fullDesc.style.display === 'none' || fullDesc.style.display === '';

        if (isHidden) {
            fullDesc.style.display = 'block';
            excerpt.style.display  = 'none';
            button.textContent     = 'Collapse Description';
        } else {
            fullDesc.style.display = 'none';
            excerpt.style.display  = '';
            button.textContent     = 'Full Description';
            button.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const cat = urlParams.get('cat');
        if (cat) filterProjects(cat);

        const newsForm = document.getElementById('project-newsletter-form');
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