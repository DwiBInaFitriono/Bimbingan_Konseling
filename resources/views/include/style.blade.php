<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>Sistem Manajemen BK</title>
<link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
<link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&family=Open+Sans:wght@300;400;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<!-- Vendor CSS Files -->
<link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
<link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
<link href="{{ asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
<link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
<link href="{{ asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">

<!-- Template Main CSS File -->
<link href="{{ asset('assets/css/style.css') }}?v={{ time() }}" rel="stylesheet">

<!-- Choices.js CSS for beautiful dropdowns -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<style>
    /* Custom Styling for Choices.js to match Bootstrap 5 & theme */
    .choices__inner {
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 8px;
        padding: 5px 14px;
        min-height: 38px;
        font-size: 0.9rem;
        color: #495057;
        box-shadow: none;
    }
    .choices.is-focused .choices__inner {
        border-color: #4154f1;
        box-shadow: 0 0 0 0.25rem rgba(65, 84, 241, 0.15);
    }
    .choices__list--dropdown {
        border-radius: 8px;
        border: 1px solid #ced4da;
        box-shadow: 0 8px 24px rgba(33, 51, 99, 0.15);
        z-index: 9999; /* Higher than modal */
    }
    .choices[data-type*="select-one"]::after {
        right: 14px;
    }
    .choices__list--dropdown .choices__item--selectable.is-highlighted {
        background-color: #f0f4ff;
        color: #4154f1;
    }
    .choices__list--dropdown .choices__item--selectable {
        padding-right: 14px;
    }
    /* Fix for modal z-index issues */
    .choices[data-type*="select-one"].is-open::after {
        border-color: transparent transparent #495057 transparent;
        margin-top: -7.5px;
    }
</style>

