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
                    <h3 class="card-title">Edit User Webinar #{{ $userWebinar->getKey() }}</h3>
                </div>
                <div class="card-body">
                    @include('cms::_partials.alert')

                    <form class="form" wire:submit.prevent="save">
                        {{ CmsForm::setErrorBag($errors) }}

                        {!! CmsForm::number('userWebinar.user_id', ['disabled' => 'disabled']) !!}
                        {!! CmsForm::number('userWebinar.webinar_id', ['disabled' => 'disabled']) !!}
                        {!! CmsForm::select('userWebinar.payment_status', $paymentStatusOptions) !!}
                        {!! CmsForm::text('userWebinar.payment_method') !!}
                        {!! CmsForm::text('userWebinar.payment_token') !!}
                        {!! CmsForm::textarea('userWebinar.feedback', ['disabled' => 'disabled']) !!}

                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary">Update User Webinar</button>
                            <button wire:click="backToIndex()" type="button" class="btn btn-light-primary ml-2">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
