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
                            <form id="pageForm" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="rootDir">Root Directory</label>
                                        <input type="text" class="form-control" id="rootDir" disabled>
                                    </div>
                                    <div class='col-md-6 mb-3 input-icons'>
                                        <label for="currentDir" class="form-label">Current Directory
                                            <span>
                                                <a href="#" data-bs-toggle="tooltip" title="Add New Directory"
                                                   class="ms-2"><i data-feather="plus-square"></i></a>
                                                <a href="#" data-bs-toggle="tooltip" title="Remove Current Directory"><i
                                                        data-feather="minus-square"></i></a>
                                            </span>

                                        </label>
                                        <input type="text" class="form-control" id="currentDir" value="" disabled>
                                    </div>
                                    <div class="col-md-6 mb-3 input-icons">
                                        <label for="file">Upload Files
                                            <span>
                                                <a href="#"><i data-feather="file-plus"></i></a>
                                            </span>
                                        </label>
                                        <input type="file" class="form-control" id="file" name="file">
                                    </div>
                                    <div class="col-md-6 mb-3 input-icons">
                                        <label for="dirList">Directory List
                                            <span>
                                                <a href="#"><i data-feather="arrow-up-circle"></i></a>
                                            </span>
                                        </label>
                                        <select id="dirList" class="form-select">
                                        </select>
                                    </div>
                                </div>
                            </form>
                            <div class="row">
                                <div class="col-md-12 my-3">
                                    <h3>Files List</h3>
                                </div>
                                <div class="col-md-12">
                                    <table id="example"
                                           class="table table-bordered dt-responsive nowrap table-striped align-middle w-100">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Domain</th>
                                            <th>Subdomain</th>
                                            <th>Email</th>
                                            <th>Forward Email</th>
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
        </div>
    </div>
@endsection

@section('modal')
    <div id="inputModal" class="modal fade" tabindex="-1" aria-labelledby="exampleModalgridLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalgridLabel">Add New Directory</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0);">
                        <div class="row g-3">
                            <div>
                                <label>Directory Name</label>
                                <input type="text" class="form-control" id="" placeholder="">
                            </div>
                            <!--end col-->
                            <div class="col-lg-12">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="submit" class="btn btn-primary">Create Directory</button>
                                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom')
    <script>
        // getRepositoryList();
        $("#modalDirect").on('click', function () {
            $("#inputModal").modal("show");
        });

        function getRepositoryList() {
            ajaxCall('{{ route('getRepoList') }}', 'Content', {
                '_token': '{{ csrf_token() }}',
                'dir': $("#currentDir").val()
            }, null, function (data) {

            });
        }
    </script>
@endsection
