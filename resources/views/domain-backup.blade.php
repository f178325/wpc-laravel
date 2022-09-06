@extends('includes.layout')
@section('title','Backup Domain')
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
                                <li class="breadcrumb-item active">Backup Domain</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div id="formCard" class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Backup Domain</h4>
                        </div>
                        <div class="card-body">
                            <form id="pageForm" autocomplete="off">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="server_id" class="form-label">Server Host</label>
                                        <select class="form-select" id="server_id" name="server_id" required>
                                            <option value="" selected disabled>-- Select host --</option>
                                            @foreach($hosts as $v)
                                                <option value="{{ $v['id'] }}">{{ $v['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="subdomain" class="form-label">Subdomain<span
                                                class="spinner-border spinner-border-sm d-none subdomain-spinner ms-2"></span></label>
                                        <select class="form-select" id="subdomain" name="subdomain" required></select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="connFile" class="form-label">Subdomain</label>
                                        <input type="text" class="form-control" id="connFile" name="connFile"
                                               value="connection.php" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="rootDir" class="form-label">Root Directory</label>
                                        <input type="text" class="form-control" id="rootDir" name="rootDir"
                                               value="{{ auth()->user()->username }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="destDir" class="form-label">Destination Directory</label>
                                        <input type="text" class="form-control" id="destDir" name="destDir"
                                               value="{{ auth()->user()->username }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="dirList" class="form-label">Directory List<span
                                                class="spinner-border spinner-border-sm d-none dirList-spinner ms-2"></span></label>
                                        <select class="form-select" id="dirList" name="dirList" required>
                                            <option value="" selected disabled>-- Select folder --</option>
                                        </select>
                                    </div>
                                </div>
                                <button class="btn btn-primary submitBtn">Create backup</button>
                            </form>
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
            ajaxCall('{{ route('getTable') }}', 'File', formData, $('#pageForm .submitBtn'), function (data) {
                if (!data.error) {

                }
            });
            return false;
        });
        $("#server_id").on('change', function () {
            $("#subdomain").html('');
            $(".subdomain-spinner").removeClass('d-none');
            ajaxCall('{{ route('getSubdomains') }}', 'Content', {
                '_token': '{{ csrf_token() }}',
                'id': $(this).val()
            }, null, function (data) {
                if (!data.error) {
                    $("#subdomain").html(data.html);
                }
                $(".subdomain-spinner").addClass('d-none');
            });
        });
    </script>
@endsection
