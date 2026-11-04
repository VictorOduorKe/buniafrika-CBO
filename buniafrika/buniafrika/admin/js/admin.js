

document.addEventListener('DOMContentLoaded', () => {
    initConfirmModal();
});

function initConfirmModal() {
    const modal = document.getElementById('confirm-modal');
    const modalMessage = document.getElementById('confirm-modal-message');
    const modalConfirmBtn = document.getElementById('confirm-modal-confirm');
    const modalCancelBtn = document.getElementById('confirm-modal-cancel');
    const deleteButtons = document.querySelectorAll('.js-delete-btn');

    if (!modal || !modalConfirmBtn || !modalCancelBtn) {
        return;
    }

    const openModal = (targetUrl, message) => {
        modalConfirmBtn.setAttribute('href', targetUrl);
        if (message) {
            modalMessage.textContent = message;
        }
        modal.classList.add('active');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    };

    const closeModal = () => {
        modal.classList.remove('active');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        modalConfirmBtn.setAttribute('href', '#');
    };

    deleteButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const targetUrl = btn.getAttribute('href');
            const message = btn.getAttribute('data-message');
            openModal(targetUrl, message);
        });
    });

    modalCancelBtn.addEventListener('click', closeModal);
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeModal();
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            closeModal();
        }
    });
}

function toggleFeaturedMediaSection() {
    const toggle = document.getElementById('toggle-featured-media');
    const section = document.getElementById('featured-media-section');
    if (toggle && section) {
        section.style.display = toggle.checked ? 'block' : 'none';
    }
}

function removeExistingFeaturedMedia(btn) {
    const thumb = btn.closest('.featured-media-thumb-container');
    if (thumb) {
        thumb.remove();
    }
    const fileInput = document.getElementById('featured-media-files');
    if (fileInput) {
        validateFeaturedMediaFiles(fileInput);
    }
}

function validateFeaturedMediaFiles(input) {
    const errDiv = document.getElementById('featured-media-error');
    const previewDiv = document.getElementById('new-featured-previews');
    if (!errDiv || !previewDiv) return;

    errDiv.style.display = 'none';
    errDiv.innerHTML = '';
    previewDiv.innerHTML = '';

    const files = input.files;
    if (files.length === 0) return;

    const existingMedia = document.querySelectorAll('#current-featured-list .featured-media-thumb-container');
    const existingCount = existingMedia.length;
    const totalCount = existingCount + files.length;

    if (totalCount > 5) {
        errDiv.innerHTML = `You can only have up to 5 featured media files. Currently you have ${existingCount} saved, and selected ${files.length} new files. Total limit is 5.`;
        errDiv.style.display = 'block';
        input.value = '';
        return;
    }

    const maxImageBytes = 12 * 1024 * 1024; // 12MB
    const maxVideoBytes = 40 * 1024 * 1024; // 40MB
    let hasError = false;
    let errorMsgs = [];

    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const isVideo = file.type.startsWith('video/');
        const limitBytes = isVideo ? maxVideoBytes : maxImageBytes;
        const limitLabel = isVideo ? "40MB" : "12MB";
        if (file.size > limitBytes) {
            errorMsgs.push(`File "${file.name}" exceeds the ${limitLabel} size limit.`);
            hasError = true;
        }

        if (!file.type.startsWith('image/') && !file.type.startsWith('video/')) {
            errorMsgs.push(`File "${file.name}" is not a valid image or video.`);
            hasError = true;
        }
    }

    if (hasError) {
        errDiv.innerHTML = errorMsgs.join('<br>');
        errDiv.style.display = 'block';
        input.value = '';
        return;
    }

    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const reader = new FileReader();

        const thumbContainer = document.createElement('div');
        thumbContainer.className = 'new-featured-thumb-container';
        thumbContainer.style.position = 'relative';
        thumbContainer.style.width = '100px';
        thumbContainer.style.height = '80px';
        thumbContainer.style.borderRadius = 'var(--radius-default)';
        thumbContainer.style.border = '1px solid var(--outline-variant)';
        thumbContainer.style.overflow = 'hidden';
        thumbContainer.style.background = '#000';

        if (file.type.startsWith('image/')) {
            const img = document.createElement('img');
            img.style.width = '100%';
            img.style.height = '100%';
            img.style.objectFit = 'cover';
            reader.onload = function(e) {
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
            thumbContainer.appendChild(img);
        } else if (file.type.startsWith('video/')) {
            const video = document.createElement('video');
            video.style.width = '100%';
            video.style.height = '100%';
            video.style.objectFit = 'cover';
            reader.onload = function(e) {
                video.src = e.target.result;
            };
            reader.readAsDataURL(file);
            thumbContainer.appendChild(video);
        }

        const badge = document.createElement('span');
        badge.textContent = 'NEW';
        badge.style.position = 'absolute';
        badge.style.bottom = '2px';
        badge.style.left = '2px';
        badge.style.background = 'var(--primary)';
        badge.style.color = '#fff';
        badge.style.fontSize = '8px';
        badge.style.padding = '2px 4px';
        badge.style.borderRadius = '2px';
        badge.style.fontWeight = 'bold';
        thumbContainer.appendChild(badge);

        previewDiv.appendChild(thumbContainer);
    }
}

