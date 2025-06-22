<!DOCTYPE html>
    <html lang="pt-br">
    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>{{ $title ?? 'APP' }}</title>
        <!-- Custom fonts for this template-->
        {{-- @push('styles') --}}
            <link href="{{ asset('template/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
            
            <!-- Custom styles for this template-->
            <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
        {{-- @endpush --}}
        @stack('styles')

        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"rel="stylesheet">
    </head>

    <body id="page-top">

        <!-- Page Wrapper -->
        <div id="wrapper">

            <!-- Sidebar -->
            <x-layout.navbar />
            <!-- End of Sidebar -->

            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">

                <!-- Main Content -->
                <div id="content">

                    <!-- Topbar -->
                    <x-layout.topbar />
                    <!-- End of Topbar -->

                    <!-- Begin Page Content -->
                    <div class="container-fluid">

                        <!-- Page Heading -->
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h1 class="h3 mb-0 text-gray-800">{{ $title ?? 'Dashboard' }}</h1>
                            {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                    class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> --}}
                        </div>

                        {{ $slot }}
                        <!-- Content Row -->
                        {{-- <x-layout.content /> --}}

                    </div>
                    <!-- /.container-fluid -->

                </div>
                <!-- End of Main Content -->

                <!-- Footer -->
                <x-layout.footer />
                <!-- End of Footer -->

            </div>
            <!-- End of Content Wrapper -->

        </div>   
        <!-- End of Page Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <!-- Logout Modal-->
        <x-layout.modal.logout />

        {{-- @push('scripts') --}}
            <!-- Bootstrap core JavaScript -->
            <script src="{{ asset('template/jquery/jquery.min.js') }}"></script>
            <script src="{{ asset('template/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

            <!-- Core plugin JavaScript -->
            <script src="{{ asset('template/jquery-easing/jquery.easing.min.js') }}"></script>

            <!-- Custom scripts for all pages -->
            <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

            <!-- Page level plugins -->
            <script src="{{ asset('template/chart.js/Chart.min.js') }}"></script>

            <!-- Page level custom scripts -->
            {{-- <script src="{{ asset('js/demo/chart-area-demo.js') }}"></script>
            <script src="{{ asset('js/demo/chart-pie-demo.js') }}"></script>
            <script src="{{ asset('js/demo/chart-bar-demo.js') }}"></script> --}}
        {{-- @endpush --}}
        @stack('scripts')
    </body>

</html>