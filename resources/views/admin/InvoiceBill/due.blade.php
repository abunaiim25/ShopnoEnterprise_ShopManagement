@extends('layouts.admin_layout')

@section('title')
    Admin - Invoice
@endsection

{{-- page nav --}}
@section('page_nav')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Invoice Bill</li>
        </ol>
        <h6 class="font-weight-bolder mb-0">Invoice Bill</h6>
    </nav>
@endsection

{{-- search nav --}}
@section('search_nav')
    <div class="ms-md-auto pe-md-3 d-flex align-items-center">
        <form action="{{ url('invoice_search_payment_due') }}" method="GET" class="search">
            {{ csrf_field() }}
            <div class="input-group">
                <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                <input name="search" id="invoice_search" type="text" class="form-control"
                    placeholder="Bill Payment Search">
            </div>
        </form>
    </div>
@endsection


@section('admin_content')
    <div class="row">

        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div style="display: flex; justify-content: space-between;" class="mb-2">
                        <h6>Payment Status</h6>
                    </div>


                </div>

                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="admin_payment_status">Bill Due List</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_payment_status_paid">Bill Paid List</a>
                    </li>
                </ul>



                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">

                        @if ($invoice_bill->count() > 0)
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Sl
                                        </th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Date (mm/dd/yyyy)</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Invoice No.</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Customer Name</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Number</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Selling Amount</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Payment Type</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Payment</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Add Payment</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Action</th>

                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($invoice_bill as $item)
                                        <tr>
                                            <td class="align-middle ">
                                                <span
                                                    class="text-secondary text-xs font-weight-bold mx-3">{{ $loop->iteration }}</span>
                                            </td>

                                            <td class="align-middle ">
                                                <span
                                                    class="text-secondary text-xs font-weight-bold">{{ \Carbon\Carbon::parse($item->date)->format('m/d/Y') }}</span>
                                            </td>

                                            <td class="align-middle ">
                                                <span
                                                    class="text-secondary text-xs font-weight-bold">{{ $item->invoice_no ?? 0 }}</span>
                                            </td>

                                            <td class="align-middle ">
                                                <span
                                                    class="text-secondary text-xs font-weight-bold">{{ $item->name }}</span>
                                            </td>

                                            <td class="align-middle ">
                                                <span
                                                    class="text-secondary text-xs font-weight-bold">{{ $item->phone }}</span>
                                            </td>

                                            <td class="align-middle">
                                                <span
                                                    class="text-secondary text-xs font-weight-bold">{{ $item->subtotal ?? 0 }}TK</span>
                                            </td>

                                            <td class="align-middle ">
                                                <span
                                                    class="text-secondary text-xs font-weight-bold">{{ $item->payment_type }}</span>
                                            </td>

                                            @if ($item->payment_status == 'Due')
                                                <td class="align-middle  text-sm">
                                                    <span class="badge badge-sm bg-gradient-danger">Due</span>
                                                </td>
                                            @else
                                                <td class="align-middle  text-sm">
                                                    <span class="badge badge-sm bg-gradient-success">Paid</span>
                                                </td>
                                            @endif

                                            <td class="align-middle ">
                                                <button type="button" class="btn btn-success addPaymentBtn"
                                                    value=" {{ $item->id }}">+Add Due</button>
                                            </td>

                                            <td class="align-middle">
                                                <a href="{{ url('admin_seen_invoicebill/' . $item->id) }}"
                                                    class="btn btn-warning">
                                                    <i class="fas fa-eye"></i> </a>
                                                <!-- <a href="{{ url('admin_place_order_invoice_edit/' . $item->id) }}"
                                                                class="btn btn-sm btn-info">
                                                                <i class="fa fa-pencil"></i> </a>
                                                            -->
                                                <a href="{{ url('place_order_invoice_delete/' . $item->id) }}"
                                                    class="btn  btn-danger"
                                                    onclick="return confirm('Are You Sure To Delete?')"><i
                                                        class="fa fa-trash"></i> </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <h2 class="text-center p-5">Invoice Bill Not Available</h2>
                        @endif
                    </div>
                </div>



                <div class="row mt-4 mx-2 mb-2">
                    <div class="col-lg-12 mb-lg-0 mb-4">
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="d-flex flex-column h-100">
                                            <p class="mb-1 pt-2 text-bold">Total Due List Amount</p>
                                            <h2 class="font-weight-bolder">{{ $subtotal }} TK</h5>
                                                <p class="mb-5">
                                                <h6 class="text-muted font-weight-normal">By {{ $bill_count }} Invoice
                                                    Bill.</h6>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 ms-auto text-center mt-5 mt-lg-0">
                                        <div class="bg-gradient-primary border-radius-lg h-100">
                                            <img src="{{ asset('admin') }}/assets/img/card.jpg"
                                                class="position-absolute h-100 w-50 top-0 d-lg-block d-none" alt="waves">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div>
        {{ $invoice_bill->links() }}
    </div>


    <!--Add Quantity product Modal Modal-->
    <form action="{{ url('due_payment_update') }}" method="POST">
        @csrf
        @method('POST')

        <div class="modal fade" id="addPaymentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog " role="document">
                <div class="modal-content  bg-white">
                    <div class="text-center my-4">
                        <h4 class="modal-title w-100 font-weight-bold text-dark">Add Due Payment
                        </h4>
                    </div>

                    <div class="modal-body mx-3">
                        <div class="row">

                            <input type="hidden" id="id" name="id">
                            <input type="hidden" id="net_oustanding" name="net_oustanding">
                            <input type="hidden" id="collecton_previous" name="collecton_previous" >

                            <div class="col-lg-6 col-md-6 col-6">
                                <div class="form-group">
                                    <label class="form-control-label">Payment Amunt: </label>
                                    <input class="form-control" id="" type="number" name="collecton" placeholder="500" required> 
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-6">
                                <div class="form-group">
                                    <label>Payment Status:</label>
                                    <select class="form-select" name="payment_status"aria-label="Default select example" required>                                       
                                        <option></option>
                                        <option value="Paid">Fully Paid Payment</option>
                                        <option value="Due">Yet Due Payment</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="d-flex justify-content-center mb-4">
                        <button class="btn btn-success">Add</button>
                    </div>

                </div>
            </div>
        </div>
    </form>
    <!--End Add Quantity product Modal Modal-->
@endsection


<!--Add Quantity product Modal-->
<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
    crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        $(document).on('click', '.addPaymentBtn', function() {
            var id = $(this).val();
            //alert(response);
            $('#addPaymentModal').modal('show');

            $.ajax({
                type: "GET",
                url: "/add_due_payment/" + id,
                success: function(response) {
                    $('#id').val(response.bill.id);
                    $('#net_oustanding').val(response.bill.net_oustanding);
                    $('#collecton_previous').val(response.bill.collecton);
                }

            });
        });
    });
</script>


{{-- page sidebar_for_active --}}
@section('sidebar_for_active')
    <ul class="navbar-nav">

        <li class="nav-item {{ Request::is('/home') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/home') }}">
                <div
                    class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                    <svg width="12px" height="12px" viewBox="0 0 45 40" version="1.1"
                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <title>shop </title>
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g transform="translate(-1716.000000, -439.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                <g transform="translate(1716.000000, 291.000000)">
                                    <g transform="translate(0.000000, 148.000000)">
                                        <path class="color-background opacity-6"
                                            d="M46.7199583,10.7414583 L40.8449583,0.949791667 C40.4909749,0.360605034 39.8540131,0 39.1666667,0 L7.83333333,0 C7.1459869,0 6.50902508,0.360605034 6.15504167,0.949791667 L0.280041667,10.7414583 C0.0969176761,11.0460037 -1.23209662e-05,11.3946378 -1.23209662e-05,11.75 C-0.00758042603,16.0663731 3.48367543,19.5725301 7.80004167,19.5833333 L7.81570833,19.5833333 C9.75003686,19.5882688 11.6168794,18.8726691 13.0522917,17.5760417 C16.0171492,20.2556967 20.5292675,20.2556967 23.494125,17.5760417 C26.4604562,20.2616016 30.9794188,20.2616016 33.94575,17.5760417 C36.2421905,19.6477597 39.5441143,20.1708521 42.3684437,18.9103691 C45.1927731,17.649886 47.0084685,14.8428276 47.0000295,11.75 C47.0000295,11.3946378 46.9030823,11.0460037 46.7199583,10.7414583 Z">
                                        </path>
                                        <path class="color-background"
                                            d="M39.198,22.4912623 C37.3776246,22.4928106 35.5817531,22.0149171 33.951625,21.0951667 L33.92225,21.1107282 C31.1430221,22.6838032 27.9255001,22.9318916 24.9844167,21.7998837 C24.4750389,21.605469 23.9777983,21.3722567 23.4960833,21.1018359 L23.4745417,21.1129513 C20.6961809,22.6871153 17.4786145,22.9344611 14.5386667,21.7998837 C14.029926,21.6054643 13.533337,21.3722507 13.0522917,21.1018359 C11.4250962,22.0190609 9.63246555,22.4947009 7.81570833,22.4912623 C7.16510551,22.4842162 6.51607673,22.4173045 5.875,22.2911849 L5.875,44.7220845 C5.875,45.9498589 6.7517757,46.9451667 7.83333333,46.9451667 L19.5833333,46.9451667 L19.5833333,33.6066734 L27.4166667,33.6066734 L27.4166667,46.9451667 L39.1666667,46.9451667 C40.2482243,46.9451667 41.125,45.9498589 41.125,44.7220845 L41.125,22.2822926 C40.4887822,22.4116582 39.8442868,22.4815492 39.198,22.4912623 Z">
                                        </path>
                                    </g>
                                </g>
                            </g>
                        </g>
                    </svg>
                </div>
                <span class="nav-link-text ms-1">Dashboard</span>
            </a>
        </li>

        <li class="nav-item {{ Request::is('/') ? 'active' : '' }}">
            <a class="nav-link " href="{{ url('/') }}" target="_blank">
                <div
                    class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                    <svg width="12px" height="12px" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                        preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5s5 2.24 5 5s-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3s3-1.34 3-3s-1.34-3-3-3z" />
                    </svg>
                </div>
                <span class="nav-link-text ms-1">Visit Site</span>
            </a>
        </li>


        <li class="nav-item {{ Request::is('admin_invoice_bill') ? 'active' : '' }}">
            <a class="nav-link active" href="{{ url('admin_invoice_bill') }}">
                <div
                    class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                    <svg width="12px" height="12px" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                        preserveAspectRatio="xMidYMid meet" viewBox="0 0 32 32">
                        <path fill="currentColor"
                            d="M22.854 6.008c-2.69.074-4.775.804-6.805 1.51c-2.171.754-4.22 1.465-7.059 1.494c-1.897.03-3.797-.298-5.664-.944L2 7.61v17.082l.666.237c1.739.615 3.517.97 5.287 1.054c.281.013.556.02.826.02c3.013 0 5.32-.8 7.557-1.572c2.358-.816 4.582-1.587 7.617-1.444a16.48 16.48 0 0 1 4.713.944l1.334.472V7.314l-.658-.24a17.78 17.78 0 0 0-5.297-1.056a16.56 16.56 0 0 0-1.191-.01zm.054 1.986c.34-.01.688-.01 1.049.004c.374.016.748.05 1.121.094A2.495 2.495 0 0 0 28 9.949v9.102a2.495 2.495 0 0 0-2.957 2.025a17.643 17.643 0 0 0-.996-.074c-3.415-.15-5.933.709-8.367 1.553c-2.361.818-4.598 1.591-7.631 1.447a15.746 15.746 0 0 1-1.13-.1A2.495 2.495 0 0 0 4 22.051v-9.102a2.493 2.493 0 0 0 2.959-2.05c.685.071 1.37.112 2.053.101c3.165-.032 5.466-.833 7.693-1.607c1.961-.683 3.83-1.325 6.203-1.399zM16 11c-2.206 0-4 2.243-4 5s1.794 5 4 5s4-2.243 4-5s-1.794-5-4-5zm0 2c1.084 0 2 1.374 2 3s-.916 3-2 3s-2-1.374-2-3s.916-3 2-3zm7.5 0a1.5 1.5 0 0 0 0 3a1.5 1.5 0 0 0 0-3zm-15 3a1.5 1.5 0 0 0 0 3a1.5 1.5 0 0 0 0-3z" />
                    </svg>
                </div>
                <span class="nav-link-text ms-1">Invoice/Bill</span>
            </a>
        </li>


        <li class="nav-item {{ Request::is('admin_combined_ledger') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('admin_combined_ledger') }}">
                <div
                    class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                        preserveAspectRatio="xMidYMid meet" viewBox="0 0 256 256">
                        <path fill="currentColor"
                            d="M128 20a108 108 0 1 0 108 108A108.1 108.1 0 0 0 128 20Zm0 192a84 84 0 1 1 84-84a84.1 84.1 0 0 1-84 84Zm44-64a32.1 32.1 0 0 1-32 32v4a12 12 0 0 1-24 0v-4h-12a12 12 0 0 1 0-24h36a8 8 0 0 0 0-16h-24a32 32 0 0 1 0-64v-4a12 12 0 0 1 24 0v4h12a12 12 0 0 1 0 24h-36a8 8 0 0 0 0 16h24a32.1 32.1 0 0 1 32 32Z" />
                    </svg>
                </div>
                <span class="nav-link-text ms-1">Transaction</span>
            </a>
        </li>

        <li class="nav-item {{ Request::is('purchase_return') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('purchase_return') }}">
                <div
                    class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                        preserveAspectRatio="xMidYMid meet" viewBox="0 0 256 256">
                        <path fill="currentColor"
                            d="M216 40H40a16 16 0 0 0-16 16v144a16 16 0 0 0 16 16h176a16 16 0 0 0 16-16V56a16 16 0 0 0-16-16Zm-32 96a8 8 0 0 1-8 8H99.3l10.4 10.3a8.1 8.1 0 0 1 0 11.4a8.2 8.2 0 0 1-11.4 0l-24-24a8.1 8.1 0 0 1 0-11.4l24-24a8.1 8.1 0 0 1 11.4 11.4L99.3 128H168v-24a8 8 0 0 1 16 0Z" />
                    </svg>
                </div>
                <span class="nav-link-text ms-1">Purchase Return</span>
            </a>
        </li>



        <li class="nav-item {{ Request::is('admin_category') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('admin_category') }}">
                <div
                    class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                        preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="m12 2l-5.5 9h11L12 2zm0 3.84L13.93 9h-3.87L12 5.84zM17.5 13c-2.49 0-4.5 2.01-4.5 4.5s2.01 4.5 4.5 4.5s4.5-2.01 4.5-4.5s-2.01-4.5-4.5-4.5zm0 7a2.5 2.5 0 0 1 0-5a2.5 2.5 0 0 1 0 5zM3 21.5h8v-8H3v8zm2-6h4v4H5v-4z" />
                    </svg>
                </div>
                <span class="nav-link-text ms-1">Product Category</span>
            </a>
        </li>

        <li class="nav-item {{ Request::is('admin_shop_stock') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('admin_shop_stock') }}">
                <div
                    class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                        preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M15.55 13c.75 0 1.41-.41 1.75-1.03l3.58-6.49A.996.996 0 0 0 20.01 4H5.21l-.94-2H1v2h2l3.6 7.59l-1.35 2.44C4.52 15.37 5.48 17 7 17h12v-2H7l1.1-2h7.45zM6.16 6h12.15l-2.76 5H8.53L6.16 6zM7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2s-.9-2-2-2zm10 0c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2s2-.9 2-2s-.9-2-2-2z" />
                    </svg>
                </div>
                <span class="nav-link-text ms-1">Shop Stock</span>
            </a>
        </li>

        <li class="nav-item {{ Request::is('admin_godown_stock') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('admin_godown_stock') }}">
                <div
                    class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                        preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="m18.36 9l.6 3H5.04l.6-3h12.72M20 4H4v2h16V4m0 3H4l-1 5v2h1v6h10v-6h4v6h2v-6h1v-2l-1-5M6 18v-4h6v4H6Z" />
                    </svg>
                </div>
                <span class="nav-link-text ms-1">Godown Stock</span>
            </a>
        </li>

        <li class="nav-item {{ Request::is('admin_contact') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('admin_contact') }}">
                <div
                    class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                        preserveAspectRatio="xMidYMid meet" viewBox="0 0 256 256">
                        <path fill="currentColor"
                            d="M128 24a104 104 0 0 0-91.2 154l-8.5 30A15.9 15.9 0 0 0 48 227.7l30-8.5A104 104 0 1 0 128 24Zm0 192a88.4 88.4 0 0 1-44.9-12.3a8.7 8.7 0 0 0-4.1-1.1a8.3 8.3 0 0 0-2.2.3l-33.2 9.5l9.5-33.2a8.2 8.2 0 0 0-.8-6.3A88 88 0 1 1 128 216Zm53.7-109.7a8.1 8.1 0 0 1 0 11.4l-32 32a8.2 8.2 0 0 1-11.4 0L112 123.3l-26.3 26.4a8.1 8.1 0 0 1-11.4-11.4l32-32a8.1 8.1 0 0 1 11.4 0l26.3 26.4l26.3-26.4a8.1 8.1 0 0 1 11.4 0Z" />
                    </svg>
                </div>
                <span class="nav-link-text ms-1">Contact</span>
            </a>
        </li>


        <li class="nav-item {{ Request::is('admin_front_control') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('admin_front_control') }}">
                <div
                    class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                        preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M6.94 14.036c-.233.624-.43 1.2-.606 1.783c.96-.697 2.101-1.139 3.418-1.304c2.513-.314 4.746-1.973 5.876-4.058l-1.456-1.455l1.413-1.415l1-1.001c.43-.43.915-1.224 1.428-2.368c-5.593.867-9.018 4.292-11.074 9.818zM17 9.001L18 10c-1 3-4 6-8 6.5c-2.669.334-4.336 2.167-5.002 5.5H3C4 16 6 2 21 2c-1 2.997-1.998 4.996-2.997 5.997L17 9.001z" />
                    </svg>
                </div>
                <span class="nav-link-text ms-1">Design</span>
            </a>
        </li>

        <li class="nav-item {{ Request::is('users') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('users') }}">
                <div
                    class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                        preserveAspectRatio="xMidYMid meet" viewBox="0 0 256 256">
                        <path fill="currentColor"
                            d="M129.2 156.9a64 64 0 1 0-82.4 0a100.1 100.1 0 0 0-40.6 33.6a12 12 0 0 0 2.9 16.7a11.8 11.8 0 0 0 6.9 2.2a11.9 11.9 0 0 0 9.8-5.1a76 76 0 0 1 124.4 0a12 12 0 1 0 19.6-13.8a100.1 100.1 0 0 0-40.6-33.6ZM48 108a40 40 0 1 1 40 40a40 40 0 0 1-40-40Zm200.4 99.2a11.8 11.8 0 0 1-6.9 2.2a12.1 12.1 0 0 1-9.8-5.1a76.2 76.2 0 0 0-62.2-32.3a12 12 0 0 1 0-24a40 40 0 0 0 0-80a39.1 39.1 0 0 0-10.8 1.5a12 12 0 1 1-6.5-23.1a62.8 62.8 0 0 1 17.3-2.4a63.9 63.9 0 0 1 41.2 112.9a100.1 100.1 0 0 1 40.6 33.6a11.9 11.9 0 0 1-2.9 16.7Z" />
                    </svg>
                </div>
                <span class="nav-link-text ms-1">Users</span>
            </a>
        </li>

        <li class="nav-item {{ Request::is('/user/profile') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/user/profile') }}">
                <div
                    class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                        preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="1.5">
                            <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10s10-4.477 10-10S17.523 2 12 2Z" />
                            <path
                                d="M4.271 18.346S6.5 15.5 12 15.5s7.73 2.846 7.73 2.846M12 12a3 3 0 1 0 0-6a3 3 0 0 0 0 6Z" />
                        </g>
                    </svg>
                </div>
                <span class="nav-link-text ms-1">Profile</span>
            </a>
        </li>

    </ul>
@endsection
