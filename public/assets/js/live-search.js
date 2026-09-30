(function() {
    'use strict';

    function initLiveSearch() {
        const searchContainers = document.querySelectorAll('.live-search-container');

        searchContainers.forEach(container => {
            const input = container.querySelector('.js-live-search-input');
            const dropdown = container.querySelector('.js-live-search-dropdown');

            if (!input || !dropdown) return;

            let debounceTimer = null;
            let currentController = null;
            let activeIndex = -1;

            function escapeHtml(str) {
                if (!str) return '';
                return str
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function highlightMatch(text, query) {
                if (!query) return escapeHtml(text);
                const escapedQuery = query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
                const regex = new RegExp(`(${escapedQuery})`, 'gi');
                const safeText = escapeHtml(text);
                return safeText.replace(regex, '<span class="theme-color fw-bold">$1</span>');
            }

            function closeDropdown() {
                dropdown.classList.add('d-none');
                dropdown.innerHTML = '';
                activeIndex = -1;
            }

            function showLoading(query) {
                dropdown.classList.remove('d-none');
                dropdown.innerHTML = `
                    <div class="live-search-state">
                        <div class="spinner-border spinner-border-sm theme-color mb-2" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mb-0 text-content">Searching products for "<strong>${escapeHtml(query)}</strong>"...</p>
                    </div>
                `;
            }

            function performSearch(query) {
                query = query.trim();
                if (query.length === 0) {
                    closeDropdown();
                    return;
                }

                showLoading(query);

                if (currentController) {
                    currentController.abort();
                }
                currentController = new AbortController();

                fetch(`/search/live?q=${encodeURIComponent(query)}`, {
                    signal: currentController.signal,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (!data || data.total === 0 || !data.products || data.products.length === 0) {
                        dropdown.innerHTML = `
                            <div class="live-search-state">
                                <i class="iconly-Search icli mb-2" style="font-size: 26px; color: #94a3b8;"></i>
                                <p class="mb-1 fw-bold text-dark">No products found</p>
                                <p class="mb-0 text-muted small">We couldn't find any match for "<strong>${escapeHtml(query)}</strong>"</p>
                            </div>
                        `;
                        return;
                    }

                    let itemsHtml = '<div class="live-search-list">';
                    data.products.forEach((prod, index) => {
                        itemsHtml += `
                            <a href="${prod.url}" class="live-search-item" data-index="${index}">
                                <img src="${prod.image}" class="live-search-thumb" alt="${escapeHtml(prod.name)}">
                                <div class="live-search-info">
                                    <h6 class="live-search-name">${highlightMatch(prod.name, query)}</h6>
                                    <div class="live-search-meta">
                                        ${prod.category_name ? `<span class="live-search-cat">${escapeHtml(prod.category_name)}</span>` : ''}
                                        <span class="live-search-stock ${prod.is_in_stock ? '' : 'out-of-stock'}">${prod.is_in_stock ? 'In Stock' : 'Out of Stock'}</span>
                                    </div>
                                </div>
                                <div class="live-search-price-wrap">
                                    <span class="live-search-price">$${prod.price}</span>
                                </div>
                            </a>
                        `;
                    });
                    itemsHtml += '</div>';

                    if (data.total > 0) {
                        itemsHtml += `
                            <div class="live-search-footer">
                                <span class="text-muted">Found <strong>${data.total}</strong> products</span>
                                <a href="${data.viewAllUrl}">View All Results <i class="fa-solid fa-arrow-right ms-1"></i></a>
                            </div>
                        `;
                    }

                    dropdown.innerHTML = itemsHtml;
                    activeIndex = -1;
                })
                .catch(err => {
                    if (err.name === 'AbortError') return;
                    closeDropdown();
                });
            }

            input.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                const query = this.value;
                debounceTimer = setTimeout(() => {
                    performSearch(query);
                }, 200);
            });

            input.addEventListener('focus', function() {
                if (this.value.trim().length > 0) {
                    performSearch(this.value);
                }
            });

            input.addEventListener('keydown', function(e) {
                const items = dropdown.querySelectorAll('.live-search-item');
                if (dropdown.classList.contains('d-none') || items.length === 0) {
                    return;
                }

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    activeIndex = (activeIndex + 1) % items.length;
                    updateActiveItem(items);
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    activeIndex = (activeIndex - 1 + items.length) % items.length;
                    updateActiveItem(items);
                } else if (e.key === 'Enter') {
                    if (activeIndex >= 0 && items[activeIndex]) {
                        e.preventDefault();
                        window.location.href = items[activeIndex].href;
                    }
                } else if (e.key === 'Escape') {
                    closeDropdown();
                }
            });

            function updateActiveItem(items) {
                items.forEach((item, idx) => {
                    if (idx === activeIndex) {
                        item.classList.add('active');
                        item.scrollIntoView({ block: 'nearest' });
                    } else {
                        item.classList.remove('active');
                    }
                });
            }
        });

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.live-search-container')) {
                document.querySelectorAll('.js-live-search-dropdown').forEach(dropdown => {
                    dropdown.classList.add('d-none');
                    dropdown.innerHTML = '';
                });
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initLiveSearch);
    } else {
        initLiveSearch();
    }
})();
