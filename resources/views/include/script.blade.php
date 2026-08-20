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
<div class="toast-container position-fixed top-0 end-0 mt-5 me-3 pt-3 p-3" style="z-index: 11000; pointer-events: none;" id="liveToastContainer"></div>

<script src="{{ asset('assets/js/script.js') }}?v={{ time() }}"></script>

<!-- Choices.js JS for beautiful dropdowns -->
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script>
    function initChoices() {
        const selects = document.querySelectorAll('select.form-select');
        selects.forEach(function(select) {
            // Prevent double initialization
            if (!select.classList.contains('choices-initialized')) {
                select.choicesObj = new Choices(select, {
                    searchEnabled: true,
                    itemSelectText: '',
                    shouldSort: false,
                    placeholder: true,
                });
                select.classList.add('choices-initialized');
            }
        });
    }

    // Initialize on normal DOM load
    document.addEventListener('DOMContentLoaded', initChoices);
    
    // Fallback mutation observer for dynamically added selects (e.g. inside dynamically loaded modals)
    const observer = new MutationObserver((mutations) => {
        let shouldInit = false;
        mutations.forEach((mutation) => {
            if (mutation.addedNodes.length) shouldInit = true;
        });
        if (shouldInit) initChoices();
    });
    observer.observe(document.body, { childList: true, subtree: true });
</script>

<script>
    // Jalankan seketika tanpa menunggu DOMContentLoaded penuh karena posisi script di bawah body
    if (typeof window.showToast === 'function') {
        @if(session('success'))
            window.showToast(@json(session('success')), 'success');
        @endif
        @if(session('password_success'))
            window.showToast(@json(session('password_success')), 'success');
        @endif
        @if(session('error'))
            window.showToast(@json(session('error')), 'error');
        @endif
        @if(session('warning'))
            window.showToast(@json(session('warning')), 'warning');
        @endif
        @if($errors->any())
            window.showToast(@json($errors->first()), 'error');
        @endif
    }
</script>
