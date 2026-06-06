const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
const loader = document.getElementById('global-loader');
const toast = document.getElementById('global-toast');
const pageLoader = document.getElementById('page-loader');
const pageLoaderMessage = document.getElementById('page-loader-message');

const showLoader = (state) => {
    loader?.classList.toggle('hidden', !state);
    if (document.getElementById('filter-form')) {
        pageLoader?.classList.toggle('hidden', !state);
        pageLoaderMessage?.classList.toggle('hidden', !state);
        loader?.classList.add('hidden');
    } else {
        pageLoaderMessage?.classList.add('hidden');
    }
};
const showToast = (message, type = 'error') => {
    if (!toast) return;
    toast.textContent = message;
    toast.classList.remove('hidden', 'bg-rose-600', 'bg-emerald-600');
    toast.classList.add(type === 'success' ? 'bg-emerald-600' : 'bg-rose-600');
    setTimeout(() => toast.classList.add('hidden'), 2500);
};

const renderTitles = (titles) => {
    const grid = document.querySelector('.titles-grid');
    if (!grid) return;
    grid.innerHTML = titles.map((title, index) => `
        <a href="/title/${title.slug}"
           class="group overflow-hidden rounded bg-white shadow transition-all duration-300 hover:scale-105 hover:shadow-lg"
           style="animation: fadeInUp 0.5s ease-out forwards; opacity: 0; animation-delay: ${index * 0.1}s;">
            <img src="${title.cover_image}" class="h-56 w-full object-cover transition-transform duration-300 group-hover:scale-105" alt="${title.title}">
            <div class="p-2 text-sm font-medium">${title.title}</div>
        </a>
    `).join('');
};

const collectFilters = () => {
    const form = document.getElementById('filter-form');
    if (!form) return null;
    const fd = new FormData(form);
    const params = new URLSearchParams();
    fd.forEach((value, key) => {
        if (value) params.append(key, value.toString());
    });
    return params;
};

const fetchTitles = async () => {
    const params = collectFilters();
    if (!params) return;
    showLoader(true);
    try {
        const response = await fetch(`/api/titles?${params.toString()}`, { headers: { Accept: 'application/json' } });
        const data = await response.json();
        renderTitles(data.data || []);
    } catch {
        showToast('Не удалось загрузить каталог');
    } finally {
        showLoader(false);
    }
};


document.getElementById('search-input')?.addEventListener('input', fetchTitles);
document.getElementById('apply-filters')?.addEventListener('click', (e) => {
    e.preventDefault();
    fetchTitles();
});


document.querySelectorAll('.tag-filter-item').forEach(item => {
    item.addEventListener('click', () => {
        const tagId = item.dataset.tagId;
        const hiddenInput = item.querySelector(`input[name="tags[${tagId}]"]`);
        const icon = item.querySelector('.tag-state-icon');
        let currentState = item.dataset.state;


        let newState;
        if (currentState === 'include') {
            newState = 'exclude';
        } else if (currentState === 'exclude') {
            newState = '';
        } else {
            newState = 'include';
        }


        item.dataset.state = newState;
        hiddenInput.value = newState;


        icon.className = 'tag-state-icon inline-flex items-center justify-center w-5 h-5 border rounded text-xs font-bold leading-none';
        if (newState === 'include') {
            icon.classList.add('bg-blue-600', 'text-white', 'border-blue-600');
            icon.textContent = '✓';
        } else if (newState === 'exclude') {
            icon.classList.add('bg-rose-600', 'text-white', 'border-rose-600');
            icon.textContent = '−';
        } else {
            icon.classList.add('bg-white', 'text-transparent', 'border-slate-400');
            icon.textContent = '';
        }
    });
});


document.getElementById('clear-filters')?.addEventListener('click', () => {
    document.querySelectorAll('.tag-filter-item').forEach(item => {
        const tagId = item.dataset.tagId;
        item.dataset.state = '';
        const hiddenInput = item.querySelector(`input[name="tags[${tagId}]"]`);
        if (hiddenInput) hiddenInput.value = '';
        const icon = item.querySelector('.tag-state-icon');
        icon.className = 'tag-state-icon inline-flex items-center justify-center w-5 h-5 border border-slate-400 rounded text-xs font-bold leading-none bg-white text-transparent';
        icon.textContent = '';
    });
    document.getElementById('filter-form')?.reset();
    fetchTitles();
});

// document.getElementById('filters-toggle')?.addEventListener('click', () => {
//     document.getElementById('filters-panel')?.classList.toggle('hidden');
// });

document.getElementById('favorite-btn')?.addEventListener('click', async (e) => {
    const button = e.currentTarget;
    const titleId = button.dataset.titleId;
    const favorited = button.dataset.favorited === '1';
    showLoader(true);
    try {
        await fetch(`/favorites/${titleId}`, {
            method: favorited ? 'DELETE' : 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, Accept: 'application/json' },
        });
        button.dataset.favorited = favorited ? '0' : '1';
        button.textContent = favorited ? 'В избранное' : 'В избранном';
    } catch {
        showToast('Ошибка при обновлении избранного');
    } finally {
        showLoader(false);
    }
});

document.querySelectorAll('.favorite-remove').forEach((button) => {
    button.addEventListener('click', async () => {
        try {
            await fetch(`/favorites/${button.dataset.titleId}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken, Accept: 'application/json' },
            });
            button.closest('div.overflow-hidden')?.remove();
        } catch {
            showToast('Не удалось удалить из избранного');
        }
    });
});

const commentsList = document.getElementById('comments-list');
const commentsAuthenticated = commentsList?.dataset.authenticated === '1';

const commentLikeButtonClass = (liked) => (
    liked
        ? 'comment-like-btn inline-flex h-8 w-8 items-center justify-center rounded-full border-2 border-amber-400 bg-amber-400 text-white'
        : 'comment-like-btn inline-flex h-8 w-8 items-center justify-center rounded-full border-2 border-amber-400 bg-transparent text-amber-400'
);

const loadComments = async () => {
    if (!commentsList) return;
    const titleId = commentsList.dataset.titleId;
    const response = await fetch(`/api/title/${titleId}/comments`);
    const comments = await response.json();
    commentsList.innerHTML = comments.map((comment) => `
        <div class="rounded border p-2">
            <div class="flex items-start justify-between gap-2">
                <div class="text-xs text-slate-500">${comment.user?.name ?? 'Пользователь'} - ${new Date(comment.created_at).toLocaleString()}</div>
                <div class="flex shrink-0 items-center gap-1">
                    ${commentsAuthenticated ? `
                        <button
                            type="button"
                            class="${commentLikeButtonClass(comment.liked_by_user)}"
                            data-comment-id="${comment.id}"
                            data-liked="${comment.liked_by_user ? '1' : '0'}"
                            title="${comment.liked_by_user ? 'Снять лайк' : 'Поставить лайк'}"
                            aria-label="${comment.liked_by_user ? 'Снять лайк' : 'Поставить лайк'}"
                        >★</button>
                    ` : ''}
                    <span class="comment-likes-count text-sm text-slate-600" data-comment-id="${comment.id}">${comment.likes ?? 0}</span>
                </div>
            </div>
            <div class="mt-1">${comment.content}</div>
        </div>
    `).join('');
};
loadComments();

commentsList?.addEventListener('click', async (e) => {
    const button = e.target.closest('.comment-like-btn');
    if (!button) return;

    const commentId = button.dataset.commentId;
    const liked = button.dataset.liked === '1';

    try {
        const response = await fetch(`/comments/${commentId}/like`, {
            method: liked ? 'DELETE' : 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, Accept: 'application/json' },
        });
        if (!response.ok) throw new Error();

        const data = await response.json();
        const isLiked = Boolean(data.liked);
        button.dataset.liked = isLiked ? '1' : '0';
        button.className = commentLikeButtonClass(isLiked);
        button.title = isLiked ? 'Снять лайк' : 'Поставить лайк';
        button.setAttribute('aria-label', button.title);

        const countEl = commentsList.querySelector(`.comment-likes-count[data-comment-id="${commentId}"]`);
        if (countEl) countEl.textContent = String(data.likes ?? 0);
    } catch {
        showToast('Не удалось обновить лайк');
    }
});

document.getElementById('comment-form')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const titleId = commentsList?.dataset.titleId;
    const content = document.getElementById('comment-content')?.value ?? '';
    if (!content.trim()) return;
    try {
        const response = await fetch('/comments', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, Accept: 'application/json' },
            body: JSON.stringify({ title_id: titleId, content }),
        });

        const data = await response.json();

        if (!response.ok) {
            showToast(data.message || 'Ошибка при отправке комментария', 'error');
            return;
        }

        document.getElementById('comment-content').value = '';
        await loadComments();
        showToast('Комментарий добавлен', 'success');
    } catch (error) {
        showToast('Не удалось отправить комментарий', 'error');
    }
});

const chapterImage = document.getElementById('chapter-image');
const chapterImageContainer = document.getElementById('chapter-image-container');

if (chapterImage && chapterImageContainer) {
    const fetchPageData = async (chapterId, pageNumber, direction = null) => {
        const query = direction ? `?direction=${direction}` : '';
        const response = await fetch(`/api/chapter/${chapterId}/page/${pageNumber}${query}`);
        if (!response.ok) throw new Error();
        return response.json();
    };

    chapterImageContainer.addEventListener('click', async (e) => {
        //if(chapterImage.contains(e.target)) return;
        const containerRect = chapterImageContainer.getBoundingClientRect();
        const clickX = e.clientX - containerRect.left;
        const halfWidth = containerRect.width / 2;

        let direction = null;
        if (clickX < halfWidth) {
            direction = 'prev';
        } else {
            direction = 'next';
        }

        const chapterId = chapterImage.dataset.chapterId;

        try {
            const currentPage = Number(chapterImage.dataset.page);
            const data = await fetchPageData(chapterId, currentPage, direction);
            chapterImage.src = data.image_path;
            chapterImage.dataset.page = String(data.page_number);
            history.pushState({}, '', `/title/${data.title_slug}/chapter/${chapterId}?page=${data.page_number}`);
        chapterImageContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
        } catch {
            showToast('Достигнут край главы');
        }

    });

}

const filtersToggle = document.getElementById('filters-toggle');
const filtersPanel = document.getElementById('filters-panel');
const chevron = document.getElementById('filters-chevron');

if (filtersToggle && filtersPanel) {
    filtersToggle.addEventListener('click', () => {
        const isOpen = filtersPanel.classList.contains('max-h-[50vh]');

        if (isOpen) {

            filtersPanel.classList.remove('max-h-[50vh]');
            filtersPanel.classList.add('max-h-0');
            if (chevron) chevron.classList.remove('rotate-180');
        } else {

            filtersPanel.classList.remove('max-h-0');
            filtersPanel.classList.add('max-h-[50vh]');
            if (chevron) chevron.classList.add('rotate-180');
        }
    });
}

const reportModal = document.getElementById('report-modal');
const reportForm = document.getElementById('report-form');
const reportText = document.getElementById('report-text');
const reportTextError = document.getElementById('report-text-error');

const openReportModal = () => {
    if (!reportModal) return;
    reportModal.classList.remove('hidden');
    reportModal.classList.add('flex');
    reportText?.focus();
};

const closeReportModal = () => {
    if (!reportModal) return;
    reportModal.classList.add('hidden');
    reportModal.classList.remove('flex');
    reportForm?.reset();
    reportTextError?.classList.add('hidden');
    reportTextError.textContent = '';
};

const showReportFieldError = (message) => {
    if (!reportTextError) return;
    reportTextError.textContent = message;
    reportTextError.classList.remove('hidden');
};

const validateReportForm = () => {
    const text = reportText?.value.trim() ?? '';

    if (!text) {
        showReportFieldError('Укажите текст жалобы.');
        return false;
    }
    if (text.length < 10) {
        showReportFieldError('Текст жалобы должен содержать не менее 10 символов.');
        return false;
    }
    if (text.length > 2000) {
        showReportFieldError('Текст жалобы не должен превышать 2000 символов.');
        return false;
    }

    reportTextError?.classList.add('hidden');
    return true;
};

document.getElementById('report-open-btn')?.addEventListener('click', openReportModal);
document.getElementById('report-close-btn')?.addEventListener('click', closeReportModal);
document.getElementById('report-cancel-btn')?.addEventListener('click', closeReportModal);

reportModal?.addEventListener('click', (e) => {
    if (e.target === reportModal) closeReportModal();
});

reportForm?.addEventListener('submit', async (e) => {
    e.preventDefault();
    if (!validateReportForm()) return;

    const reportTextValue = reportText?.value.trim() ?? '';

    try {
        const response = await fetch('/reports', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                Accept: 'application/json',
            },
            body: JSON.stringify({ reportText: reportTextValue }),
        });

        const data = await response.json().catch(() => ({}));

        if (!response.ok) {
            if (response.status === 422 && data.errors?.reportText?.[0]) {
                showReportFieldError(data.errors.reportText[0]);
            } else {
                showToast(data.message ?? 'Не удалось отправить жалобу');
            }
            return;
        }

        closeReportModal();
        showToast(data.message ?? 'Жалоба успешно отправлена.', 'success');
    } catch {
        showToast('Не удалось отправить жалобу');
    }
});
