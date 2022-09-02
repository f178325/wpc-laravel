@extends('includes.layout')
@section('title','My Servers')
@section('body')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">WPC</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item active">Servers</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Add Websites (CPanels) & Dedicated Servers (WHM
                                Panels)</h4>
                            <p class="mb-0">Note: not all CPanel accounts are the same quality. CPanels hosted with us
                                are very
                                powerful. The SI Tool works best with CPanels hosted on our server, but you can test any
                                CPanel here.</p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <p><a href="#" class="me-2">Click here to buy a CPanel account (hosted with
                                            us).</a>--
                                        Choose the
                                        options you wish, then scroll to the bottom to purchase.</p>
                                    <p><a href="#" class="me-2">Click here to buy your own Dedicated Server.</a>--
                                        Scroll to the bottom of the page and click 'Dedicated' to see the options.
                                    </p>
                                </div>
                            </div>
                            <div id="listing" class="row">
                                <div class="col-md-12 text-end mb-3">
                                    <button id="addBtn" class="btn btn-primary"><i
                                            class="mdi mdi-plus me-2"></i>Add New Host
                                    </button>
                                </div>
                                <div class="col-md-12">
                                    <table id="example"
                                           class="table table-bordered dt-responsive nowrap table-striped align-middle">
                                        <thead>
                                        <tr>
                                            <th scope="col" style="width: 10px;">
                                                <div class="form-check">
                                                    <input class="form-check-input fs-15"
                                                           type="checkbox" id="checkAll">
                                                </div>
                                            </th>
                                            <th>#</th>
                                            <th>Address</th>
                                            <th>Username</th>
                                            <th>Type</th>
                                            <th>Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div id="addForm" class="row" style="display: none">
                                <div class="col-md-12">
                                    <form id="pageForm" method="POST">
                                        @csrf
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="type" class="form-label">Server Type</label>
                                                <select class="form-select" id="type" name="type" required>
                                                    <option value="" selected disabled>-- Select server type --
                                                    </option>
                                                    <option value="Cpanel">Cpanel Account</option>
                                                    <option value="WHM">WHM Server</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="domain" class="form-label">Host Address</label>
                                                <input type="text" class="form-control" id="domain" name="domain"
                                                       placeholder="Enter host (i.e. domain.com)" required>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="username" class="form-label">Username</label>
                                                <input type="text" name="username" class="form-control"
                                                       id="username" placeholder="Enter username" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="password" class="form-label">Password</label>
                                                <input type="password" class="form-control" id="password"
                                                       placeholder="Enter password" name="password" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button class="btn btn-primary submitBtn">Submit</button>
                                                <button type="button" onclick="show_hide('#listing','#addForm')"
                                                        class="btn btn-dark">
                                                    Cancel
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom')
    <script>
        $("#addBtn").on('click', function () {
            show_hide('#addForm', '#listing');
        });
        $('#pageForm').parsley().on('form:submit', function () {
            let formData = $("#pageForm").serialize();
            ajaxCall('{{ route('postHosts') }}', 'Content', formData, $('#pageForm .submitBtn'), function (data) {
                if (!data.error) {

                }
            });
            return false;
        });
    </script>
@endsection
