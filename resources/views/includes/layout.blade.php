<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
      data-sidebar-image="none">
<head>
    <title>@yield('title')</title>
    @include('includes.styles')
</head>
<body>
<div id="layout-wrapper">
    @include('includes.header')
    @include('includes.nav')
    <div class="main-content">
        @yield('body')
        @include('includes.footer')
    </div>
</div>
<button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
    <i class="ri-arrow-up-line"></i>
</button>
<div id="deleteModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
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
                <form id="deleteForm" method="POST">
                    @csrf
                    <input type="hidden" id="deleteId" name="deleteId">
                    <input type="hidden" id="table" name="tableName">
                    <div class="mt-4">
                        <h4 class="mb-3">Delete confirmation</h4>
                        <p class="text-muted mb-4">Are you sure? You cannot revert it.</p>
                        <div class="hstack gap-2 justify-content-center">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-danger submitBtn">Delete</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="warningModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-5">
                <lord-icon src="https://cdn.lordicon.com/tdrtiskw.json" trigger="loop"
                           colors="primary:#f7b84b,secondary:#405189" style="width:130px;height:130px"></lord-icon>
                <form id="confirmForm" method="POST">
                    @csrf
                    <input type="hidden" id="code" name="code">
                    <div class="mt-4">
                        <h4 class="mb-3">Confirmation</h4>
                        <p class="text-muted mb-4">Are you sure? You cannot revert it.</p>
                        <div class="hstack gap-2 justify-content-center">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-primary confirmBtn">Confirm</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@include('includes.script')
<script>
    $(document).ready(function () {
        $("tr .form-check-input").prop('checked', false);
        $("#deleteBtn").click(function () {
            if ($(".checkBox:checked").length === 0) {
                alertify.logPosition('top right'), alertify.error('No row selected');
                return false;
            }
            let deleteArr = $("table input[name='id']:checked")
                .map(function () {
                    return $(this).val();
                }).get();
            $("#deleteModal #table").val($(this).data('table'));
            $("#deleteModal #deleteId").val(deleteArr);
            $("#deleteModal").modal('show');
        });
        $("#deleteForm").submit(function (e) {
            e.preventDefault();
            ajaxCall('{{ route('postDelete') }}', 'Content', $("#deleteForm").serialize(), $('#deleteForm .submitBtn'), function (data) {
                $("#deleteModal").modal('hide');
                if (!data.error) {
                    setTimeout(function () {
                        location.reload();
                    }, 1500);
                }
            });
        });
        $("#checkAll").click(function () {
            let check = $("#checkAll").is(":checked");
            if (check) {
                $(".checkBox").prop('checked', true);
            } else {
                $(".checkBox").prop('checked', false);
            }
        })
    });

    function warningModal(code) {
        $("#warningModal #code").val(code);
        $("#warningModal").modal('show');
    }
</script>
@yield('custom')
</body>
</html>
