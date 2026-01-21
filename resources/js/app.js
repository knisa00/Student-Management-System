document.addEventListener('DOMContentLoaded', function () {
    console.log('Student Management System loaded');

    // ===== 1. FORM VALIDATION =====
    initFormValidation();

    // ===== 2. REAL-TIME SEARCH =====
    initRealtimeSearch();

    // ===== 3. LOADING SPINNERS =====
    initLoadingSpinners();

    // ===== 4. TOAST NOTIFICATIONS =====
    window.showToast = showToast;

    // ===== 5. MODAL CONFIRMATIONS =====
    initModalConfirmations();

    // ===== 6. PAGINATION =====
    initPagination();

    // Auto-focus first input on login/register forms
    const firstInput = document.querySelector('input:not([type="hidden"]):not([type="submit"])');
    if (firstInput) {
        firstInput.focus();
    }
});

// ===== 1. FORM VALIDATION =====
function initFormValidation() {
    // Email validation
    document.querySelectorAll('input[type="email"]').forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value && !isValidEmail(this.value)) {
                this.classList.add('is-invalid');
                showToast('Invalid email format', 'danger');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    });

    // Password validation (min 8 chars)
    document.querySelectorAll('input[type="password"][name="password"]').forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value && this.value.length < 8) {
                this.classList.add('is-invalid');
                showToast('Password must be at least 8 characters', 'danger');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    });

    // Password confirmation
    document.querySelectorAll('input[name="password_confirmation"]').forEach(input => {
        input.addEventListener('blur', function() {
            const passwordField = document.querySelector('input[name="password"]');
            if (this.value && passwordField.value !== this.value) {
                this.classList.add('is-invalid');
                showToast('Passwords do not match', 'danger');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    });

    // Required fields
    document.querySelectorAll('input[required], select[required], textarea[required]').forEach(field => {
        field.addEventListener('blur', function() {
            if (!this.value.trim()) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    });
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// ===== 2. REAL-TIME SEARCH =====
function initRealtimeSearch() {
    const searchInputs = document.querySelectorAll('input[name="search"], input[placeholder*="Search"]');
    
    searchInputs.forEach(input => {
        input.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const container = this.closest('form') ? this.closest('form').parentElement : this.parentElement;
            
            // Search in course cards
            const cards = container.querySelectorAll('.course-card, [data-searchable]');
            let visibleCount = 0;
            
            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                const isMatch = text.includes(searchTerm);
                
                card.style.display = isMatch ? 'block' : 'none';
                if (isMatch) visibleCount++;
            });

            // Show "no results" message
            let noResultsMsg = container.querySelector('.no-results-message');
            if (visibleCount === 0 && searchTerm) {
                if (!noResultsMsg) {
                    noResultsMsg = document.createElement('div');
                    noResultsMsg.className = 'alert alert-info no-results-message mt-3';
                    noResultsMsg.textContent = 'No results found for: ' + searchTerm;
                    container.appendChild(noResultsMsg);
                }
            } else if (noResultsMsg) {
                noResultsMsg.remove();
            }
        });
    });
}

// ===== 3. LOADING SPINNERS =====
function initLoadingSpinners() {
    // Add spinner to all form submissions
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
                
                // Re-enable after 5 seconds (in case of error)
                setTimeout(() => {
                    if (submitBtn.disabled) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                }, 5000);
            }
        });
    });

    // Add spinner to links that should show loading
    document.querySelectorAll('a[data-loading="true"]').forEach(link => {
        link.addEventListener('click', function(e) {
            if (!this.href.includes('javascript:')) {
                const spinner = document.createElement('span');
                spinner.className = 'spinner-border spinner-border-sm me-2';
                this.innerHTML = spinner.outerHTML + 'Loading...';
                this.style.pointerEvents = 'none';
            }
        });
    });
}

// ===== 4. TOAST NOTIFICATIONS =====
function showToast(message, type = 'success', duration = 3000) {
    const toastContainer = getToastContainer();
    
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} position-fixed`;
    toast.style.cssText = `
        bottom: 20px;
        right: 20px;
        min-width: 300px;
        z-index: 9999;
        animation: slideIn 0.3s ease-in-out;
    `;
    
    const iconMap = {
        'success': '✅',
        'danger': '❌',
        'warning': '⚠️',
        'info': 'ℹ️'
    };
    
    toast.innerHTML = `
        <div class="d-flex align-items-center">
            <span style="font-size: 1.2em; margin-right: 10px;">${iconMap[type] || '•'}</span>
            <div>${message}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    
    // Auto-remove after duration
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease-in-out';
        setTimeout(() => toast.remove(), 300);
    }, duration);
}

function getToastContainer() {
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        document.body.appendChild(container);
    }
    return container;
}

// ===== 5. MODAL CONFIRMATIONS =====
function initModalConfirmations() {
    // Replace all onclick="return confirm(...)" with modal confirmations
    document.querySelectorAll('[onclick*="confirm"]').forEach(element => {
        const onclickAttr = element.getAttribute('onclick');
        const message = onclickAttr.match(/confirm\(['"](.+?)['"]\)/)?.[1] || 'Are you sure?';
        
        element.removeAttribute('onclick');
        element.addEventListener('click', function(e) {
            if (!this.dataset.isForm) {
                e.preventDefault();
                showConfirmModal(message, () => {
                    // Find the parent form and submit it
                    const form = this.closest('form');
                    if (form) {
                        form.submit();
                    } else if (this.tagName === 'FORM') {
                        this.submit();
                    }
                });
            }
        });
    });
}

function showConfirmModal(message, onConfirm, onCancel = null) {
    const modalId = 'confirmModal_' + Date.now();
    
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.id = modalId;
    modal.innerHTML = `
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    ${message}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmBtn">Confirm</button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    const bsModal = new bootstrap.Modal(modal);
    
    document.getElementById('confirmBtn').addEventListener('click', () => {
        bsModal.hide();
        onConfirm();
    });
    
    modal.addEventListener('hidden.bs.modal', () => {
        modal.remove();
        if (onCancel) onCancel();
    });
    
    bsModal.show();
}

// ===== 6. PAGINATION =====
function initPagination() {
    const itemsPerPage = 10;
    
    document.querySelectorAll('[data-paginate="true"]').forEach(table => {
        const rows = Array.from(table.querySelectorAll('tbody tr'));
        if (rows.length <= itemsPerPage) return;
        
        const totalPages = Math.ceil(rows.length / itemsPerPage);
        let currentPage = 1;
        
        const paginationContainer = document.createElement('nav');
        paginationContainer.className = 'mt-4';
        paginationContainer.innerHTML = `
            <ul class="pagination justify-content-center">
                <li class="page-item" id="prevBtn">
                    <a class="page-link" href="#" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li class="page-item active"><span class="page-link" id="pageInfo">Page 1 of ${totalPages}</span></li>
                <li class="page-item" id="nextBtn">
                    <a class="page-link" href="#" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>`;
        
        table.parentElement.insertAdjacentElement('afterend', paginationContainer);
        
        function showPage(page) {
            currentPage = Math.max(1, Math.min(page, totalPages));
            const start = (currentPage - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            
            rows.forEach((row, index) => {
                row.style.display = index >= start && index < end ? '' : 'none';
            });
            
            document.getElementById('pageInfo').textContent = `Page ${currentPage} of ${totalPages}`;
            document.getElementById('prevBtn').classList.toggle('disabled', currentPage === 1);
            document.getElementById('nextBtn').classList.toggle('disabled', currentPage === totalPages);
        }
        
        document.getElementById('prevBtn').addEventListener('click', (e) => {
            e.preventDefault();
            showPage(currentPage - 1);
        });
        
        document.getElementById('nextBtn').addEventListener('click', (e) => {
            e.preventDefault();
            showPage(currentPage + 1);
        });
        
        showPage(1);
    });
}

// ===== ADD CUSTOM CSS =====
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
    
    .is-invalid {
        border-color: #dc3545 !important;
    }
    
    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
    }
    
    .page-link {
        cursor: pointer;
    }
    
    .page-item.disabled .page-link {
        cursor: not-allowed;
        opacity: 0.5;
    }
`;
document.head.appendChild(style);