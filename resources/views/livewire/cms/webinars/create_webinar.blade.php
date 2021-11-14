@section('additional_scripts')
<script type="text/javascript">
    window.resourceUrl = '{{ route('cms.webinars.index') }}';
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
                    <h3 class="card-title">Create New Webinar</h3>
                </div>
                <div class="card-body">
                    @include('cms::_partials.alert')

                    <form class="form" wire:submit.prevent="save">
                        {{ CmsForm::setErrorBag($errors) }}

                        {!! CmsForm::number('webinar.category_id') !!}
                        {!! CmsForm::text('webinar.title') !!}
                        {!! CmsForm::text('webinar.description') !!}
                        {!! CmsForm::datetime('webinar.start_at') !!}
                        {!! CmsForm::datetime('webinar.end_at') !!}
                        {!! CmsForm::number('webinar.price') !!}
                        {!! CmsForm::text('webinar.type') !!}
                        {!! CmsForm::text('webinar.zoom_id') !!}
                        {!! CmsForm::number('webinar.max_participants') !!}
                        {!! CmsForm::datetime('webinar.published_at') !!}

                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary">Save Webinar</button>
                            <button wire:click="backToIndex()" type="button" class="btn btn-light-primary ml-2">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
