<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
      data-sidebar-image="none">
<head>
    <title>Sign In | WPC</title>
    @php($login=true)
    @include('../includes.styles')
</head>
<body>
<div class="auth-page-wrapper pt-5">
    <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
        <div class="bg-overlay"></div>
        <div class="shape">
            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                 viewBox="0 0 1440 120">
                <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
            </svg>
        </div>
    </div>
    <div class="auth-page-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center mt-sm-5 mb-4 text-white-50">
                        <div>
                            <a class="d-inline-block auth-logo">
                                <img src="{{ asset('assets/images/logo-light.png') }}" alt="" height="20">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card mt-4">
                        <div class="card-body p-4">
                            <div class="text-center mt-2">
                                <h5 class="text-primary">Welcome Back !</h5>
                                <p class="text-muted">Sign in to continue</p>
                            </div>
                            <div class="p-2 mt-4">
                                <form id="pageForm">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="username" name="username"
                                               placeholder="Enter username" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="password">Password</label>
                                        <div class="position-relative auth-pass-inputgroup mb-3">
                                            <input type="password" class="form-control pe-5"
                                                   placeholder="Enter password" id="password" name="password" required>
                                            <button
                                                class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted shadow-none"
                                                type="button" id="password-addon"><i
                                                    class="ri-eye-fill align-middle"></i></button>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <button id="submitBtn" class="btn btn-success w-100">Sign In</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('../includes.footer')
</div>

@include('../includes.script')
<script>
    $('#pageForm').parsley().on('form:submit', function () {
        ajaxCall('{{ route('postLogin') }}', 'Content', $('#pageForm').serialize(), $('#submitBtn'), function (data) {
            if (!data.error) {
                setTimeout(function () {
                    location.href = '{{ route('getDashboard') }}';
                }, 1500);
            }
        });
        return false;
    });
    $("#password-addon").on('click', function () {
        let ele = $(this).prev('input');
        if (ele.attr('type') === 'text') {
            ele.attr('type', 'password');
        } else {
            ele.attr('type', 'text');
        }
    });
</script>
</body>


</html>
