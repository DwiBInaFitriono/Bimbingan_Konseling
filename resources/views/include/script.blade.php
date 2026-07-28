<!-- Vendor JS Files -->
<script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
<script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
<script src="{{ asset('assets/vendor/quill/quill.js') }}"></script>
<script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
<script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>

<!-- Template Main JS File -->
<script src="{{ asset('assets/js/main.js') }}"></script>

{{-- Container Toast Notification Real-Time --}}
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 11000;" id="liveToastContainer"></div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // 1. Toast Notification Helper
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
            let bsToast = new bootstrap.Toast(toastEl, { delay: 4000 });
            bsToast.show();

            toastEl.addEventListener('hidden.bs.toast', function () {
                toastEl.remove();
            });
        };

        // Show Laravel Session Flash Messages as Toast (bottom-right)
        @if(session('success'))
            showToast(@json(session('success')), 'success');
        @endif
        @if(session('password_success'))
            showToast(@json(session('password_success')), 'success');
        @endif
        @if(session('error'))
            showToast(@json(session('error')), 'error');
        @endif
        @if(session('warning'))
            showToast(@json(session('warning')), 'warning');
        @endif

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
    });
</script>
