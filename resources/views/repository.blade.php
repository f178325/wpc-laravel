@extends('includes.layout')
@section('title', 'File Repository')
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
                                <li class="breadcrumb-item active">File Repository</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div id="formCard" class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Manage Repository</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="rootDir">Root Directory</label>
                                    <input type="text" class="form-control" id="rootDir"
                                           value="{{ auth()->user()->username }}" disabled>
                                </div>
                                <div class='col-md-6 mb-3 input-icons'>
                                    <label for="currentDir" class="form-label">Current Directory
                                        <span>
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#dirModal"
                                                   class="ms-2">
                                                    <i data-bs-toggle="tooltip" title="Add New Directory"
                                                       data-feather="plus-square"></i></a>
                                                <a data-bs-toggle="modal" data-bs-target="#removeDirModal" href="#">
                                                    <i data-bs-toggle="tooltip" title="Remove Current Directory"
                                                       data-feather="minus-square"></i></a>
                                            </span>

                                    </label>
                                    <input type="text" class="form-control" id="currentDir"
                                           value="{{ auth()->user()->username }}" autocomplete="off" disabled>
                                </div>
                                <div class="col-md-6 mb-3 input-icons">
                                    <form id="uploadForm">
                                        @csrf
                                        <label for="file">Upload Files
                                            <span><button class="bg-transparent border-0 text-primary"><i
                                                        data-feather="file-plus"></i></button></span>
                                        </label>
                                        <input type="file" class="form-control" id="file" name="file" required>
                                    </form>
                                </div>
                                <div class="col-md-6 mb-3 input-icons">
                                    <label for="dirList">Directory List
                                        <span>
                                                <a href="#" onclick="backDir(); return false;"><i
                                                        data-feather="arrow-up-circle"></i></a>
                                            </span>
                                    </label>
                                    <select id="dirList" class="form-select">
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 my-3">
                                    <h3>Files List</h3>
                                </div>
                                <div class="col-md-12">
                                    <table id="example"
                                           class="table table-bordered dt-responsive nowrap table-striped align-middle w-100">
                                        <thead>
                                        <tr>
                                            <th>File Name</th>
                                            <th>File Path</th>
                                            <th>Actions</th>
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
        </div>
    </div>
@endsection

@section('page-modals')
    <div id="dirModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myExtraLargeModalLabel">Add New Directory</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="dirForm">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="dirName">Directory Name</label>
                                <input type="text" class="form-control" id="dirName" name="dirName"
                                       placeholder="Enter directory name" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary submitBtn">Create directory</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="removeDirModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
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
                        <p class="text-muted mb-4">Are you sure? All the files in the directory will be deleted</p>
                        <div class="hstack gap-2 justify-content-center">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button onclick="deleteDirectory(this)" class="btn btn-danger">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('custom')
    <script>
        $(document).ready(function () {
            getRepositoryList();
            $("#dirList").on('change', function () {
                let currentDir = $("#currentDir");
                currentDir.val(currentDir.val() + '/' + $(this).val());
                getRepositoryList();
            });
        });
        $("#removeBtn").on('click', function () {
            if ($("#rootDir").val() === $("#currentDir").val()) {
                alertify.logPosition('top right'), alertify.error('Cannot delete root directory');
            }
        });
        $('#dirForm').parsley().on('form:submit', function () {
            ajaxCall('{{ route('createDirectory') }}', 'Content', {
                '_token': '{{ csrf_token() }}',
                'dir': $("#currentDir").val(),
                'dirName': $("#dirName").val()
            }, $("#dirForm .submitBtn"), function () {
                $("#dirModal").modal('hide');
                $("#dirForm").trigger('reset');
                getRepositoryList();
            });
            return false;
        });
        $('#uploadForm').parsley().on('form:submit', function () {
            let formData = new FormData($("#uploadForm")[0]);
            formData.append('dir', $("#currentDir").val());
            ajaxCall('{{ route('uploadFile') }}', 'File', formData, $("#dirForm .submitBtn"), function () {
                $("#uploadForm").trigger('reset');
                getRepositoryList();
            });
            return false;
        });

        function copyPath(row) {
            let path = $("#example tbody tr#row-" + row + " .path").html();
            navigator.clipboard.writeText(path);
            alertify.logPosition("top right"), alertify.success("Path copied");
        }

        function deleteFile(fileName) {
            ajaxCall('{{ route('postDeleteFile') }}', 'Content', {
                '_token': '{{ csrf_token() }}',
                'dir': $("#currentDir").val(),
                'fileName': fileName
            }, null, function (data) {
                refreshTable(data);
            });
        }

        function refreshTable(data) {
            let tableHtml = '';
            for (let i = 0; i < data.files.length; i++) {
                tableHtml += '<tr id="row-' + i + '">';
                tableHtml += '<td>' + data.files[i] + '</td>';
                tableHtml += '<td class="path">' + $("#currentDir").val() + '/' + data.files[i] + '</td>';
                tableHtml += '<td><button class="btn btn-primary btn-sm me-1" onclick="copyPath(' + i + ')" title="Copy Path"><i class="mdi mdi-content-copy"></i></button>' +
                    '<button class="btn btn-danger btn-sm" onclick="deleteFile(\'' + data.files[i] + '\')" title="Delete File"><i class="mdi mdi-trash-can"></i></button></td>';
                tableHtml += '</tr>';
            }
            $("#example tbody").html(tableHtml);
            $("#example").DataTable();
        }

        function getRepositoryList() {
            $("#dirList").empty();
            $("#example tbody").html('');
            $("#example").dataTable().fnDestroy();
            ajaxCall('{{ route('getRepoList') }}', 'Content', {
                '_token': '{{ csrf_token() }}',
                'dir': $("#currentDir").val()
            }, null, function (data) {
                if (data.dir === '') {
                    $("#dirList").html('<option value="" disabled>No directory exist</option>');
                } else {
                    $("#dirList").html(data.dir);
                }
                $('#dirList').get(0).selectedIndex = -1;
                let tableHtml = '';
                for (let i = 0; i < data.files.length; i++) {
                    tableHtml += '<tr id="row-' + i + '">';
                    tableHtml += '<td>' + data.files[i] + '</td>';
                    tableHtml += '<td class="path">' + $("#currentDir").val() + '/' + data.files[i] + '</td>';
                    tableHtml += '<td><button class="btn btn-primary btn-sm me-1" onclick="copyPath(' + i + ')" title="Copy Path"><i class="mdi mdi-content-copy"></i></button>' +
                        '<button class="btn btn-danger btn-sm" onclick="deleteFile(\'' + data.files[i] + '\')" title="Delete File"><i class="mdi mdi-trash-can"></i></button></td>';
                    tableHtml += '</tr>';
                }
                $("#example tbody").html(tableHtml);
                $("#example").DataTable();
            });
        }

        function deleteDirectory(e) {
            if ($("#rootDir").val() === $("#currentDir").val()) {
                alertify.logPosition("top right"), alertify.error("Cannot remove root directory");
                return false;
            }
            ajaxCall('{{ route('removeDirectory') }}', 'Content', {
                '_token': '{{ csrf_token() }}',
                'dir': $("#currentDir").val()
            }, $(e), function () {
                $("#removeDirModal").modal('hide');
                backDir();
                // getRepositoryList();
            });
        }

        function backDir() {
            let currentDir = $("#currentDir");
            if ($("#rootDir").val() === currentDir.val()) {
                return false;
            }
            let dir = currentDir.val().split('/');
            let newDir = '';
            for (let i = 0; i < dir.length - 1; i++) {
                newDir += '/' + dir[i];
            }
            currentDir.val(newDir.substring(1));
            getRepositoryList();
        }
    </script>
@endsection
