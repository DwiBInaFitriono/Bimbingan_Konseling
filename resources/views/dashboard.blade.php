<!DOCTYPE html>
<html lang="en">

<head>
    @include('include.style')
</head>

<body>

    <!-- ======= Header ======= -->
    @include('include.header')

    <!-- ======= Sidebar ======= -->
    @include('include.sidebar')

    <main id="main" class="main">
        <div class="col-md-9">
            @yield('kontent')
        </div>
    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    @include('include.footer')

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>
    @include('include.script')


</body>

</html>
