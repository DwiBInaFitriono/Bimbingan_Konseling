/* Extracted from Blade files */

// Force browser to always load page at the top on refresh (prevent scroll restoration jump)
if ('scrollRestoration' in history) {
    history.scrollRestoration = 'manual';
}
window.scrollTo(0, 0);

// 1. Toast Notification Helper (Global Scope)
window.showToast = function (message, type = 'success') {
    let container = document.getElementById('liveToastContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'liveToastContainer';
        container.className = 'toast-container position-fixed top-0 end-0 p-3 mt-5 me-2';
        container.style.zIndex = '11000';
        container.style.pointerEvents = 'none';
        document.body.appendChild(container);
    }

    let toastId = 'toast-' + Date.now();
    let borderStyle = type === 'success' ? 'border-left: 5px solid #10b981;'
                    : type === 'error'   ? 'border-left: 5px solid #ef4444;'
                    : type === 'warning' ? 'border-left: 5px solid #f59e0b;'
                    :                      'border-left: 5px solid #4154f1;';
    let iconColor = type === 'success' ? 'color: #10b981;'
                  : type === 'error'   ? 'color: #ef4444;'
                  : type === 'warning' ? 'color: #f59e0b;'
                  :                      'color: #4154f1;';
    let iconClass = type === 'success' ? 'bi-check-circle-fill'
                  : type === 'error'   ? 'bi-exclamation-octagon-fill'
                  : type === 'warning' ? 'bi-exclamation-triangle-fill'
                  :                      'bi-info-circle-fill';

    let toastHtml = `
        <div id="${toastId}" class="toast align-items-center border-0 shadow-lg rounded-3 mb-2" role="alert" aria-live="assertive" aria-atomic="true" style="background:#ffffff; ${borderStyle} min-width:300px; max-width:400px; pointer-events:auto; box-shadow: 0 10px 30px rgba(1,41,112,0.12) !important;">
            <div class="d-flex align-items-center py-2 px-3">
                <i class="bi ${iconClass} fs-4 me-2 flex-shrink-0" style="${iconColor}"></i>
                <div class="toast-body p-0 flex-grow-1 text-dark" style="font-size: 13.5px; font-weight: 500;">
                    ${message}
                </div>
                <button type="button" class="btn-close ms-2 me-0 flex-shrink-0" data-bs-dismiss="toast" aria-label="Close" style="font-size: 0.75rem;"></button>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', toastHtml);

    let toastEl = document.getElementById(toastId);
    let bsToast = new bootstrap.Toast(toastEl, { delay: 3500 });
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
        overlay.classList.add('swal-show');
        modal.classList.add('swal-show');

        function close() {
            modal.classList.remove('swal-show');
            overlay.classList.remove('swal-show');
            setTimeout(() => { overlay.style.display = 'none'; }, 130);
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
        btn.type = 'button';
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
            title: 'Konfirmasi Tindakan'
        });

        const btnCancel = document.createElement('button');
        btnCancel.className = 'swal-btn swal-btn-cancel';
        btnCancel.type = 'button';
        btnCancel.textContent = 'Tidak';
        btnCancel.onclick = function() { close(); if (onCancel) onCancel(); };

        const btnOk = document.createElement('button');
        btnOk.className = 'swal-btn swal-btn-danger';
        btnOk.type = 'button';
        btnOk.textContent = 'Iya, Lanjutkan';
        btnOk.onclick = function() {
            btnOk.disabled = true;
            btnCancel.style.display = 'none';
            btnOk.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';
            if (typeof onConfirm === 'function') {
                onConfirm();
            }
        };

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
