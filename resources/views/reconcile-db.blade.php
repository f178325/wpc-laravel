@extends('includes.layout')
@section('title','Reconcile Database')
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
                                <li class="breadcrumb-item active">Reconcile Database</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div id="formCard" class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Reconcile Database</h4>
                        </div>
                        <div class="card-body">
                            <div class="row mb-5">
                                <label for="hosts" class="form-label col-md-9 text-end mt-2">Select Host</label>
                                <div class="col-md-3">
                                    <select id="hosts" class="form-select" autocomplete="off">
                                        <option value="" disabled selected>-- Select Host --</option>
                                        @foreach($hosts as $v)
                                            <option value="{{ $v['id'] }}">{{ $v['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12 text-end mt-3">
                                    <button id="delBtn" class="btn btn-danger"><i class="mdi mdi-trash-can me-2"></i>
                                        Delete selected
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <table id="example"
                                           class="table table-bordered dt-responsive nowrap table-striped align-middle w-100">
                                        <thead>
                                        <tr>
                                            <th scope="col">
                                                <div class="form-check">
                                                    <input class="form-check-input fs-15"
                                                           type="checkbox" id="checkAll" autocomplete="off">
                                                </div>
                                            </th>
                                            <th>Database Name</th>
                                            <th>Subdomain</th>
                                            <th>Result</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                    <div class="processing text-center d-none">
                                        <span class="fs-5 me-2">Processing...</span>
                                        <span class="spinner-border spinner-border-sm text-dark"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page-modals')
    <div id="delModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center p-5">
                    <lord-icon
                        src="https://cdn.lordicon.com/gsqxdxog.json"
                        trigger="hover"
                        colors="primary:#121331,secondary:#f06548"
                        state="hover-empty"
                        style="width:120px;height:120px">
                    </lord-icon>
                    <div class="mt-4">
                        <h4 class="mb-3">Delete confirmation</h4>
                        <p class="text-muted mb-4">Are you sure? You cannot revert it.</p>
                        <div class="hstack gap-2 justify-content-center">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button id="delConfirm" class="btn btn-danger">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom')
    <script>
        $("#hosts").on('change', function () {
            getDatabase();
        });
        $("#delBtn").on('click', function () {
            if ($(".checkBox:checked").length === 0) {
                alertify.logPosition('top right'), alertify.error('No row selected');
                return false;
            }
            $("#delModal").modal('show');
        });
        $("#delConfirm").on('click', function () {
            $("#delModal").modal('hide');
            let row, rowStatus;
            let count = $(".checkBox:checked").length;
            let ajaxCount = 0;
            $(".checkBox:checked").each(function () {
                row = $("tr#" + $(this).val());
                rowStatus = row.find('.status');
                rowStatus.html('<span>Processing... <span class="spinner-border spinner-border-sm"></span></span>');
                ajaxCall('{{ route('postReconcile') }}', 'Content', {
                    '_token': '{{ csrf_token() }}',
                    'id': $("#hosts").val(),
                    'db': $(this).val()
                }, null, function (data) {
                    if (data.error) {
                        rowStatus.html('<span class="badge bg-danger">' + data.res + '</span>');
                    } else {
                        rowStatus.html('<span class="badge bg-success">Success</span>');
                    }
                    ajaxCount++;
                    if (ajaxCount === count) {
                        setTimeout(function () {
                            getDatabase();
                        }, 1000)
                    }
                });
            });
        });

        function getDatabase() {
            let select = $("#hosts");
            let table = $("#example").hide();
            let tableBody = table.find('tbody');
            table.dataTable().fnDestroy();
            select.prop('disabled', true);
            $(".processing").removeClass('d-none');
            $("#delBtn").prop('disabled', true);
            ajaxCall('{{ route('getDatabase') }}', 'Content', {
                '_token': '{{ csrf_token() }}',
                'id': select.val()
            }, null, function (data) {
                $(".processing").addClass('d-none');
                if (!data.error) {
                    tableBody.html(data.html);
                } else {
                    tableBody.html('<tr><td colspan="4">No data available</td></tr>');
                }
                select.prop('disabled', false);
                $("#delBtn").prop('disabled', false);
                table.DataTable();
                table.show();
            });
        }
    </script>
@endsection
