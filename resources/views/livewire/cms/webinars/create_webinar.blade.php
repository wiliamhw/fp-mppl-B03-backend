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

                        <x-input.image variable="webinarThumbnail" title="Webinar Thumnail" />
                        {!! CmsForm::select('webinar.category_id', $categoryOptions)->setTitle('Category') !!}
                        {!! CmsForm::text('webinar.title') !!}
                        <div class="form-group">
                            <x-input.tinymce labelName="Description" wire:model.defer="webinar.description" />
                        </div>
                        <div class="form-group">
                            <label for="webinar.start_at">Start At</label>
                            <input class="form-control" required="" wire:model.defer="webinar.start_at"
                                   name="webinar.start_at" type="datetime-local" id="webinar.start_at">
                            @error('webinar.start_at')
                            <p class="text-danger"> {{ $message }} </p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="webinar.end_at">End At</label>
                            <input class="form-control" required="" wire:model.defer="webinar.end_at"
                                   name="webinar.end_at" type="datetime-local" id="webinar.end_at">
                            @error('webinar.end_at')
                            <p class="text-danger"> {{ $message }} </p>
                            @enderror
                        </div>
                        {!! CmsForm::number('webinar.price', ['required' => false])->setTitle('Price (optional)')!!}
                        {!! CmsForm::text('webinar.zoom_id', ['required' => false])->setTitle('Zoom Id (optional)') !!}
                        {!! CmsForm::number('webinar.max_participants') !!}
                        {!! CmsForm::select('isPublished', $isPublishedOptions)->setTitle('Is Published') !!}

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
