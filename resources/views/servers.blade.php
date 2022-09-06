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
                    <div id="formCard" class="card">
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
                                    <button data-bs-toggle="modal" data-bs-target="#batchModal"
                                            class="btn btn-secondary"><i class="mdi mdi-plus-box me-2"></i>Batch Upload
                                    </button>
                                    <button id="deleteBtn" data-table="hosts" class="btn btn-danger"><i
                                            class="mdi mdi-trash-can me-2"></i>Delete
                                    </button>
                                </div>
                                <div class="col-md-12">
                                    <table id="example"
                                           class="table table-bordered dt-responsive nowrap table-striped align-middle">
                                        <thead>
                                        <tr>
                                            <th scope="col">
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
                                        @foreach($hosts as $k => $v)
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input fs-15 checkBox" type="checkbox" value="{{ $v['id'] }}" name="id">
                                                    </div>
                                                </td>
                                                <td>{{ $k+1 }}</td>
                                                <td>{{ $v['name'] }}</td>
                                                <td>{{ $v['username'] }}</td>
                                                <td>{{ $v['type'] }}</td>
                                                <td>
                                                    <a href="{{ 'pages/list_subdomains?id=' . $v->id }}"><i
                                                            class="icon-md me-2" data-feather="list"
                                                            data-bs-toggle="tooltip"
                                                            title="All subdomains"></i>
                                                    </a>
                                                    <a href="{{ 'pages/create_subdomains?id=' . $v->id }}"><i
                                                            class="icon-md" data-feather="server"
                                                            data-bs-toggle="tooltip"
                                                            title="Process installation"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
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
                    <div id="tableCard" class="card" style="display: none">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Domains</h4>
                            <div class="flex-shrink-0">
                                <a onclick="location.reload()" class="btn btn-dark btn-sm"><i
                                        class="me-1 mdi mdi-arrow-left"></i>Go Back
                                </a>
                                <button id="processBtn" class="btn btn-primary btn-sm"><i
                                        class="me-1 mdi mdi-arrow-right"></i>Process
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="example1"
                                   class="table table-bordered dt-responsive nowrap table-striped align-middle w-100">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Domain</th>
                                    <th>Username</th>
                                    <th>Password</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page-modals')
    <div id="batchModal" class="modal fade" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Multiple Hosts</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="batchForm" autocomplete="off">
                    @csrf
                    <input type="hidden" name="format" value="serversTable">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="file" class="form-label">File</label>
                                    <input type="file" class="form-control" id="file" name="file" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal"> Close</button>
                        <a href="{{ asset('sample/sample_server.xlsx') }}" download="" class="btn btn-secondary">
                            Download Sample</a>
                        <button class="btn btn-primary submitBtn">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('custom')
    <script>
        $("input[type='checkbox']").prop('checked', false);
        $("#addBtn").on('click', function () {
            show_hide('#addForm', '#listing');
        });
        $('#pageForm').parsley().on('form:submit', function () {
            let formData = $("#pageForm").serialize();
            ajaxCall('{{ route('postHosts') }}', 'Content', formData, $('#pageForm .submitBtn'), function (data) {
                if (!data.error) {
                    setTimeout(function () {
                        location.reload();
                    }, 1500)
                }
            });
            return false;
        });
        $('#batchForm').parsley().on('form:submit', function () {
            let formData = new FormData($("#batchForm")[0]);
            ajaxCall('{{ route('getTable') }}', 'File', formData, $('#batchForm .submitBtn'), function (data) {
                if (!data.error) {
                    $("#batchForm").trigger('reset');
                    $("#example1").dataTable().fnDestroy();
                    $("table#example1 tbody").html(data.html);
                    $("#example1").DataTable();
                    $("#processBtn").prop('disabled', false);
                    $("#batchModal").modal('hide');
                    $("#formCard").hide();
                    $("#tableCard").show();
                }
            });
            return false;
        });
        $("#processBtn").on('click', function () {
            $(this).prop('disabled', true);
            $("#example1 tbody tr").each(function () {
                let domain, username, password, type;
                let rowStatus = $(this).find('.status');
                rowStatus.html('<span>Processing... <span class="spinner-border spinner-border-sm"></span></span>');
                domain = $(this).find('.domain').html();
                username = $(this).find('.username').html();
                password = $(this).find('.password').html();
                type = $(this).find('.type').html();
                ajaxCall('{{ route('postHosts') }}', 'Content', {
                    _token: '{{ csrf_token() }}',
                    domain: domain,
                    username: username,
                    password: password,
                    type: type
                }, null, function (data) {
                    if (data.error) {
                        rowStatus.html('<span class="badge bg-danger">Error</span>');
                    } else {
                        rowStatus.html('<span class="badge bg-success">Success</span>');
                    }
                });
            });
        });
    </script>
@endsection
