@extends('includes.layout')
@section('title','Bulk Create Emails')
@section('body')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">WPC</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item active">Create Emails</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div id="formCard" class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Bulk Email Creation</h4>
                            <div class="flex-shrink-0">
                                <a href="{{ asset('sample/sample_bulk_email_creator.xlsx') }}" download=""
                                   class="btn-sm btn btn-secondary"><span
                                        class="me-1 mdi mdi-download"></span>Download Sample
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="live-preview">
                                <form id="pageForm" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="file" class="form-label">File</label>
                                            <input type="file" class="form-control" id="file"
                                                   name="file" required>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary submitBtn">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div id="tableCard" class="card" style="display: none">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Domains</h4>
                            <div class="flex-shrink-0">
                                <button id="backBtn" class="btn btn-dark btn-sm"><i
                                        class="me-1 mdi mdi-arrow-left"></i>Go Back
                                </button>
                                <button id="processBtn" class="btn btn-primary btn-sm"><i
                                        class="me-1 mdi mdi-arrow-right"></i>Process
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="example"
                                   class="table table-bordered dt-responsive nowrap table-striped align-middle w-100">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Domain</th>
                                    <th>Subdomain</th>
                                    <th>Username</th>
                                    <th>Password</th>
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
@section('custom')
    <script>
        $('#pageForm').parsley().on('form:submit', function () {
            let formData = new FormData($("#pageForm")[0]);
            ajaxCall('{{ route('getEmailTable') }}', 'File', formData, $('#pageForm .submitBtn'), function (data) {
                if (!data.error) {
                    $("#pageForm").trigger('reset');
                    $("#example").dataTable().fnDestroy();
                    $("table#example tbody").html(data.html);
                    $("#example").DataTable();
                    $("#processBtn").prop('disabled', false);
                    $("#formCard").hide();
                    $("#tableCard").show();
                }
            });
            return false;
        });
        $("#backBtn").on('click', function () {
            $("#tableCard").hide();
            $("#formCard").show();
        });
        $("#processBtn").on('click', function () {
            $(this).prop('disabled', true);
            $("#example tbody tr").each(function () {
                let domain, subdomain, username, password;
                let rowStatus = $(this).find('.status');
                rowStatus.html('<span>Processing... <span class="spinner-border spinner-border-sm"></span></span>');
                domain = $(this).find('.domain').html();
                subdomain = $(this).find('.subdomain').html();
                username = $(this).find('.username').html();
                password = $(this).find('.password').html();
                ajaxCall('{{ route('postEmails') }}', 'Content', {
                    _token: '{{ csrf_token() }}',
                    domain: domain,
                    subdomain: subdomain,
                    username: username,
                    password: password
                }, null, function (data) {
                    if (data.error) {
                        rowStatus.html('<span class="badge bg-danger">' + data.res + '</span>');
                    } else {
                        rowStatus.html('<span class="badge bg-success">Success</span>');
                    }
                });
            });
        });
    </script>
@endsection
