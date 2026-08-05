/* Extracted from Blade files */

// Force browser to always load page at the top on refresh (prevent scroll restoration jump)
if ('scrollRestoration' in history) {
    history.scrollRestoration = 'manual';
}
window.scrollTo(0, 0);

// 1. Toast Notification Helper (Global Scope)
window.showToast = function (message, type = 'success') {
    let container = document.getElementById('liveToastContainer');
    if (!container) return;

    let toastId = 'toast-' + Date.now();
    let bgClass = type === 'success' ? 'bg-success text-white'
                : type === 'error'   ? 'bg-danger text-white'
                :                      'bg-warning text-dark';
    let icon    = type === 'success' ? 'bi-check-circle-fill'
                : type === 'error'   ? 'bi-exclamation-triangle-fill'
                :                      'bi-exclamation-circle-fill';

    let toastHtml = `
        <div id="${toastId}" class="toast align-items-center ${bgClass} border-0 shadow-lg rounded-3" role="alert" aria-live="assertive" aria-atomic="true" style="min-width:280px;max-width:360px;">
            <div class="d-flex">
                <div class="toast-body fs-6 d-flex align-items-center gap-2 py-3 px-3">
                    <i class="bi ${icon} fs-4 flex-shrink-0"></i>
                    <span>${message}</span>
                </div>
                <button type="button" class="btn-close ${type === 'warning' ? '' : 'btn-close-white'} me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', toastHtml);

    let toastEl = document.getElementById(toastId);
    let bsToast = new bootstrap.Toast(toastEl, { delay: 3000 });
    bsToast.show();

    toastEl.addEventListener('hidden.bs.toast', function () {
        toastEl.remove();
    });
};

// 2. Modern Alert & Confirm Modal (Global Scope) - pengganti alert() dan confirm()
(function() {
    // Inject modal HTML sekali saja
    function ensureModalExists() {
        if (document.getElementById('globalAlertModal')) return;
        const html = `
        <div class="swal-overlay" id="globalAlertOverlay" style="display:none;">
            <div class="swal-modal" id="globalAlertModal">
                <div class="swal-icon-wrap" id="swalIconWrap"></div>
                <h3 class="swal-title" id="swalTitle">Informasi</h3>
                <p class="swal-text" id="swalText"></p>
                <div class="swal-actions" id="swalActions"></div>
            </div>
        </div>`;
        document.body.insertAdjacentHTML('beforeend', html);
    }

    function getIconSvg(type) {
        if (type === 'warning' || type === 'confirm') {
            return '<div class="swal-icon swal-icon-warning"><i class="bi bi-exclamation-triangle-fill"></i></div>';
        }
        if (type === 'error') {
            return '<div class="swal-icon swal-icon-error"><i class="bi bi-x-circle-fill"></i></div>';
        }
        if (type === 'success') {
            return '<div class="swal-icon swal-icon-success"><i class="bi bi-check-circle-fill"></i></div>';
        }
        return '<div class="swal-icon swal-icon-info"><i class="bi bi-info-circle-fill"></i></div>';
    }

    function showModal(opts) {
        ensureModalExists();
        const overlay = document.getElementById('globalAlertOverlay');
        const modal = document.getElementById('globalAlertModal');
        const iconWrap = document.getElementById('swalIconWrap');
        const title = document.getElementById('swalTitle');
        const text = document.getElementById('swalText');
        const actions = document.getElementById('swalActions');

        iconWrap.innerHTML = getIconSvg(opts.type || 'info');
        title.textContent = opts.title || 'Informasi';
        text.textContent = opts.message || '';
        actions.innerHTML = '';

        overlay.style.display = 'flex';
        requestAnimationFrame(() => {
            overlay.classList.add('swal-show');
            modal.classList.add('swal-show');
        });

        function close() {
            modal.classList.remove('swal-show');
            overlay.classList.remove('swal-show');
            setTimeout(() => { overlay.style.display = 'none'; }, 250);
        }

        return { close, actions };
    }

    // Global swalAlert - pengganti alert()
    window.swalAlert = function(message, type, title) {
        const t = type || 'info';
        const titleMap = { info: 'Informasi', warning: 'Peringatan', error: 'Gagal', success: 'Berhasil' };
        const { close, actions } = showModal({
            message,
            type: t,
            title: title || titleMap[t] || 'Informasi'
        });

        const btn = document.createElement('button');
        btn.className = 'swal-btn swal-btn-primary';
        btn.textContent = 'OK';
        btn.onclick = close;
        actions.appendChild(btn);
        btn.focus();
    };

    // Global swalConfirm - pengganti confirm()
    window.swalConfirm = function(message, onConfirm, onCancel) {
        const { close, actions } = showModal({
            message,
            type: 'confirm',
            title: 'Konfirmasi'
        });

        const btnCancel = document.createElement('button');
        btnCancel.className = 'swal-btn swal-btn-cancel';
        btnCancel.textContent = 'Batal';
        btnCancel.onclick = function() { close(); if (onCancel) onCancel(); };

        const btnOk = document.createElement('button');
        btnOk.className = 'swal-btn swal-btn-danger';
        btnOk.textContent = 'Ya, Lanjutkan';
        btnOk.onclick = function() { close(); if (onConfirm) onConfirm(); };

        actions.appendChild(btnCancel);
        actions.appendChild(btnOk);
        btnOk.focus();
    };
})();

// 3. Helper to restore form submit button state
window.restoreSubmitButton = function (form) {
    if (!form) return;
    form.classList.remove('is-submitting');
    let submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
    if (submitBtn) {
        if (submitBtn.tagName === 'BUTTON' && submitBtn.hasAttribute('data-original-text')) {
            submitBtn.innerHTML = submitBtn.getAttribute('data-original-text');
        }
        submitBtn.classList.remove('disabled');
        submitBtn.style.pointerEvents = 'auto';
    }
};

document.addEventListener("DOMContentLoaded", () => {
    try {

        // 3. Mencegah Double Submit pada semua Form
        document.querySelectorAll('form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                // Jangan jalankan spinner jika event sudah di-prevent (misal oleh inline onsubmit)
                if (e.defaultPrevented) {
                    return;
                }
                
                // Jangan jalankan spinner jika validasi HTML5 bawaan gagal
                if (typeof form.checkValidity === 'function' && !form.checkValidity()) {
                    return;
                }
                
                if (form.classList.contains('is-submitting')) {
                    e.preventDefault();
                    return;
                }

                // Tunda pembaruan visual tombol ke tick berikutnya agar validasi lain
                // yang dipasang via addEventListener dapat berjalan dan memicu e.preventDefault() terlebih dahulu.
                setTimeout(function() {
                    if (e.defaultPrevented) {
                        return;
                    }
                    
                    form.classList.add('is-submitting');
                    
                    let submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
                    if (submitBtn) {
                        if (submitBtn.tagName === 'BUTTON') {
                            let originalText = submitBtn.innerHTML;
                            submitBtn.setAttribute('data-original-text', originalText);
                            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Proses...';
                        }
                        submitBtn.classList.add('disabled');
                        submitBtn.style.pointerEvents = 'none';
                    }
                }, 0);
            });
        });
    } catch(e) { console.warn('Extracted script warning:', e); }
});
