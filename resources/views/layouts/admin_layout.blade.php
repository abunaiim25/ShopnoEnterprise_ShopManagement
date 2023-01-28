@php
$front = App\Models\FrontControl::first();
$categories = App\Models\Category::get();
$product_stock = App\Models\ShopStock::select('product_name')->where('status', '1')->where('product_quantity', '>', 0)->get();
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img_DB/front/logo/' . $front->logo_small) }}">
    <link rel="icon" type="image/png" href="{{ asset('img_DB/front/logo/' . $front->logo_small) }}">
    <title>
        @yield('title')
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="{{ asset('admin') }}/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="{{ asset('admin') }}/assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="{{ asset('admin') }}/assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('admin') }}/assets/css/soft-ui-dashboard.css?v=1.0.7" rel="stylesheet" />

</head>

<body class="g-sidenav-show  bg-gray-100">

    <!-- SideBar -->
    @include('layouts.admin_inc.sidebar')

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">

        <!-- Navbar -->
        @include('layouts.admin_inc.navbar')

        <div class="container-fluid py-4">

            <!--Main Work-->
            @yield('admin_content')

            <!-- Footer -->
            @include('layouts.admin_inc.footer')
        </div>
    </main>

    <!-- Navbar -->
    @include('layouts.admin_inc.right_sidebar')




    <!--https://sweetalert.js.org/guides/-->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    @if (session('status_swal'))
    <script>
        swal("{{ session('status_swal') }}");
    </script>
    @endif
    <!--   Core JS Files   -->
    <script src="{{ asset('admin') }}/assets/js/core/popper.min.js"></script>
    <script src="{{ asset('admin') }}/assets/js/core/bootstrap.min.js"></script>
    <script src="{{ asset('admin') }}/assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="{{ asset('admin') }}/assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="{{ asset('admin') }}/assets/js/plugins/chartjs.min.js"></script>
    <script src="{{ asset('admin') }}/assets/js/last_design.js"></script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('admin') }}/assets/js/soft-ui-dashboard.min.js?v=1.0.7"></script>

    <!-select with search-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script>
    $("#product_desc_select").select2({
        placeholder: "Search Product",
            allowClear: true
    });
   </script>



    @include('layouts.admin_inc.js')

{{-- 
    <!--Product Autocomplite search-->
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT
        2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
        var availableTags = [];
        $.ajax({
            method: "GET",
            url: "/product-list",
            success: function(response) {
                startAutoComplete(response);
            }
        });

        function startAutoComplete(availableTags) {
            $("#search_stock_product_name").autocomplete({
                source: availableTags
            });
        }
    </script>
    <!--End Product Autocomplite search-->
 --}}

    <!--invoice Autocomplite search-->
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT
        2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
        var availableTags = [];
        $.ajax({
            method: "GET",
            url: "/invoice_autocomplete_search",
            success: function(response) {
                startAutoComplete(response);
            }
        });

        function startAutoComplete(availableTags) {
            $("#invoice_search").autocomplete({
                source: availableTags
            });
        }
    </script>
    <!--End invoice Autocomplite search-->


    <!--User Autocomplite search-->
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT
        2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
        var availableTags = [];
        $.ajax({
            method: "GET",
            url: "/user_autocomplete_search",
            success: function(response) {
                startAutoComplete(response);
            }
        });

        function startAutoComplete(availableTags) {
            $("#users_search").autocomplete({
                source: availableTags
            });
        }
    </script>
    <!--End User Autocomplite search-->

    <!--Admin Autocomplite search-->
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT
        2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"> </script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
        var availableTags = [];
        $.ajax({
            method: "GET",
            url: "/admin_autocomplete_search",
            success: function(response) {
                startAutoComplete(response);
            }
        });

        function startAutoComplete(availableTags) {
            $("#search_admin").autocomplete({
                source: availableTags
            });
        }
    </script>
    <!--End Admin Autocomplite search-->

 
    <!--Return Purchase Autocomplite search-->
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT
        2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
        var availableTags = [];
        $.ajax({
            method: "GET",
            url: "/purchase_return_autocomplete_search",
            success: function(response) {
                startAutoComplete(response);
            }
        });

        function startAutoComplete(availableTags) {
            $("#purchase_return_search").autocomplete({
                source: availableTags
            });
        }
    </script>
    <!--End Return Purchase Autocomplite search-->


<!--form multiple-->

<script>
    $(document).ready(function() {

        //remove form
        $(document).on('click', '.remove-btn', function() {
            $(this).closest('.main-form').remove();
        });

        //add form
        $(document).on('click', '.add-more-form', function() {
            //alert('hello')
            $('.paste-new-forms2').append(`
        
            <div class="main-form card-body px-0 pt-0">
                        <div class="table-responsive px-4">
                            <div class="row ">
                                <div class="col-lg-3 col-md-3 col-4">
                                    <div class="form-group">
                                        <select id="product_desc_select" type="text" name="product_desc"
                                            class="form-select form-select-lg" aria-label="Default select example">
                                            <option></option>
                                            @foreach ($product_stock as $row)
                                                <option>{{ $row->product_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-2 col-md-2 col-2">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="warranty[]" placeholder="2"
                                            required>
                                    </div>
                                </div>

                                <div class="col-lg-2 col-md-2 col-2">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="price[]" placeholder="1000"
                                            required>
                                    </div>
                                </div>

                                <div class="col-lg-2 col-md-2 col-2">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="qty[]" placeholder="2"
                                            required>
                                    </div>
                                </div>

                                <div class="col-lg-2 col-md-2 col-2">
                                    <div>
                                      <button type="button" class="remove-btn btn btn-danger btn-sm ">-</button>
                                   </div>
                                </div>
                            </div>
                        </div>
                    </div>
         `);
        });
    })
</script>

</body>

</html>