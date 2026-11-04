 function filterGallery(category) {
        const cards = document.querySelectorAll('.js-gallery-card');
        const buttons = document.querySelectorAll('.js-gallery-filter');

        buttons.forEach(btn => {
            btn.classList.toggle('active', btn.dataset.galleryCategory === category);
        });

        cards.forEach(card => {
            const cardCat = card.dataset.category.toLowerCase();
            if (category === 'all' || cardCat === category.toLowerCase()) {
                card.style.display = 'block';
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'scale(1)';
                }, 10);
            } else {
                card.style.opacity = '0';
                card.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    card.style.display = 'none';
                }, 150);
            }

document.querySelectorAll('.js-gallery-filter').forEach(button => {
            button.addEventListener('click', () => filterGallery(button.dataset.galleryCategory || 'all'));
});
        });
    }
