@section('additional_scripts')
<script type="text/javascript">
    window.resourceUrl = '{{ route('cms.user_webinars.index') }}';
</script>
@endsection

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container">
            <livewire:cms.nav.breadcrumb :items="$this->breadcrumbItems" />

            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header">
                    <h3 class="card-title">User Webinar Detail #{{ $userWebinar->getKey() }}</h3>
                </div>
                <div class="card-body">
                    @include('cms::_partials.alert')

                    <form class="form">
                        {{ CmsForm::setErrorBag($errors) }}

                        {!! CmsForm::number('userWebinar.user_id', ['disabled' => 'disabled']) !!}
                        {!! CmsForm::number('userWebinar.webinar_id', ['disabled' => 'disabled']) !!}
                        {!! CmsForm::select('userWebinar.payment_status', $paymentStatusOptions, ['disabled' => 'disabled']) !!}
                        {!! CmsForm::text('userWebinar.payment_method', ['disabled' => 'disabled']) !!}
                        {!! CmsForm::text('userWebinar.payment_token', ['disabled' => 'disabled']) !!}
                        {!! CmsForm::textarea('userWebinar.feedback', ['disabled' => 'disabled']) !!}

                        <div class="form-group text-center">
                            @if($this->currentAdmin->can('cms.user_webinar.update'))
                                <button wire:click="edit()" type="button" class="btn btn-warning mr-2">
                                    Edit User Webinar
                                </button>
                            @endif

                            <button wire:click="backToIndex()" type="button" class="btn btn-light-primary">Back</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
