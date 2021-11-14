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
                    <h3 class="card-title">Webinar Detail #{{ $webinar->getKey() }}</h3>
                </div>
                <div class="card-body">
                    @include('cms::_partials.alert')

                    <form class="form">
                        {{ CmsForm::setErrorBag($errors) }}

                        {!! CmsForm::number('webinar.category_id', ['disabled' => 'disabled']) !!}
                        {!! CmsForm::text('webinar.title', ['disabled' => 'disabled']) !!}
                        {!! CmsForm::text('webinar.description', ['disabled' => 'disabled']) !!}
                        {!! CmsForm::datetime('webinar.start_at', ['disabled' => 'disabled']) !!}
                        {!! CmsForm::datetime('webinar.end_at', ['disabled' => 'disabled']) !!}
                        {!! CmsForm::number('webinar.price', ['disabled' => 'disabled']) !!}
                        {!! CmsForm::text('webinar.type', ['disabled' => 'disabled']) !!}
                        {!! CmsForm::text('webinar.zoom_id', ['disabled' => 'disabled']) !!}
                        {!! CmsForm::number('webinar.max_participants', ['disabled' => 'disabled']) !!}
                        {!! CmsForm::datetime('webinar.published_at', ['disabled' => 'disabled']) !!}

                        <div class="form-group text-center">
                            @if($this->currentAdmin->can('cms.webinars.update'))
                                <button wire:click="edit()" type="button" class="btn btn-warning mr-2">
                                    Edit Webinar
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
