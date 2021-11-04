@extends('cms::_layouts.auth')

@section('additional_heads')
    <style>
        /* already defined in bootstrap4 */
        .text-xs-center {
            text-align: center;
        }

        .g-recaptcha {
            display: inline-block;
        }

        .login-form {
            max-width: 420px !important;
        }
    </style>
@endsection

@section('additional_scripts')
    {!! app('captcha')->renderJs() !!}
@endsection

@section('content')
    <!--begin::Main-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Login-->
        <div class="login login-1 login-signin-on d-flex flex-column flex-lg-row flex-column-fluid bg-white" id="kt_login">
            <!--begin::Aside-->
            <div class="login-aside d-flex flex-column flex-row-auto" style="background-color: #F2C98A;">
                <!--begin::Aside Top-->
                <div class="d-flex flex-column-auto flex-column pt-lg-40 pt-15">
                    <!--begin::Aside header-->
                    <a href="#" class="text-center mb-10">
{{--                        <img src="{{ asset('cms-assets/media/logos/logo-letter-1.png') }}" class="max-h-70px" alt="" />--}}
                        <h1 style="color: #986923; font-weight: bold;">{{ config('cms.name') }}</h1>
                    </a>
                    <!--end::Aside header-->
                    <!--begin::Aside title-->
                    <h3 class="font-weight-bolder text-center font-size-h4 font-size-h1-lg" style="color: #986923;">{!! config('cms.tagline') !!}</h3>
                    <!--end::Aside title-->
                </div>
                <!--end::Aside Top-->
                <!--begin::Aside Bottom-->
                <div class="aside-img d-flex flex-row-fluid bgi-no-repeat bgi-position-y-bottom bgi-position-x-center" style="background-image: url({{ asset('cms-assets/media/svg/illustrations/login-visual-1.svg') }})"></div>
                <!--end::Aside Bottom-->
            </div>
            <!--begin::Aside-->
            <!--begin::Content-->
            <div class="login-content flex-row-fluid d-flex flex-column justify-content-center position-relative overflow-hidden p-7 mx-auto">
                <!--begin::Content body-->
                <div class="d-flex flex-column-fluid flex-center">
                    <!--begin::ForgotPassword-->
                    <div class="login-form login-signin">
                    <!--begin::Form-->
                    {!! Form::open(['route' => 'cms.auth.password.email', 'class' => 'form']) !!}
                        <!--begin::Title-->
                        <div class="pb-13 pt-lg-0 pt-5">
                            <h3 class="font-weight-bolder text-dark font-size-h4 font-size-h1-lg">Forgotten Password ?</h3>
                            <p class="text-muted font-weight-bold font-size-h4">Enter your email to reset your password</p>
                        </div>
                        <!--end::Title-->

                        @if($errors->count() > 0)
                            <div class="alert alert-custom alert-danger fade show" role="alert">
                                <div class="alert-icon"><i class="fa fa-exclamation-triangle"></i></div>
                                <div class="alert-text">
                                    {!! implode('<br />', $errors->all()) !!}
                                </div>
                                <div class="alert-close">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true"><i class="ki ki-close"></i></span>
                                    </button>
                                </div>
                            </div>
                        @endif

                        @if (session('status'))
                            <div class="alert alert-custom alert-primary fade show" role="alert">
                                <div class="alert-icon"><i class="fa fa-info-circle"></i></div>
                                <div class="alert-text">
                                    {!! session('status') !!}
                                </div>
                                <div class="alert-close">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true"><i class="ki ki-close"></i></span>
                                    </button>
                                </div>
                            </div>
                        @endif

                        <!--begin::Form group-->
                        <div class="form-group">
                            <input class="form-control form-control-solid h-auto py-6 px-6 rounded-lg font-size-h6" type="email" placeholder="Email" name="email" autocomplete="off" />
                        </div>
                        <!--end::Form group-->
                        <!--begin::Form group-->
                        <div class="form-group text-xs-center">
                            {!! app('captcha')->display(['style' => '']) !!}
                        </div>
                        <!--end::Form group-->
                        <!--begin::Form group-->
                        <div class="form-group d-flex flex-wrap pb-lg-0 justify-content-between mt-n5">
                            <input type="submit" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-4" value="Submit">

                            @if (Route::has('cms.auth.login'))
                                <a href="{{ route('cms.auth.login') }}" class="btn btn-light-primary font-weight-bolder font-size-h6 px-8 py-4 my-3">Sign In</a>
                            @endif
                        </div>
                        <!--end::Form group-->
                    {!! Form::close() !!}
                    <!--end::Form-->
                    </div>
                    <!--end::ForgotPassword-->
                </div>
                <!--end::Content body-->

            </div>
            <!--end::Content-->
        </div>
        <!--end::Login-->
    </div>
    <!--end::Main-->
@endsection
