/* Extracted from Blade files */

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

document.addEventListener("DOMContentLoaded", () => {
    try {
        // 2. Preservasi Posisi Scroll Otomatis (Anti-Reset scroll saat reload/submit form)
        // Simpan posisi scroll sebelum halaman di-reload/unload
        window.addEventListener("beforeunload", function () {
            sessionStorage.setItem("scrollPosition_" + window.location.pathname, window.scrollY);
        });

        // Kembalikan posisi scroll setelah halaman selesai dimuat
        const savedScrollPosition = sessionStorage.getItem("scrollPosition_" + window.location.pathname);
        if (savedScrollPosition !== null) {
            setTimeout(function () {
                window.scrollTo({
                    top: parseFloat(savedScrollPosition),
                    behavior: "instant"
                });
                sessionStorage.removeItem("scrollPosition_" + window.location.pathname);
            }, 50);
        }
        // 3. Mencegah Double Submit pada semua Form
        document.querySelectorAll('form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                if (form.classList.contains('is-submitting')) {
                    e.preventDefault();
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
            });
        });
    } catch(e) { console.warn('Extracted script warning:', e); }
});
