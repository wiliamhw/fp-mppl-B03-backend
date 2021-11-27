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
                    <h3 class="card-title">Edit Webinar #{{ $webinar->getKey() }}</h3>
                </div>
                <div class="card-body">
                    @include('cms::_partials.alert')

                    <form class="form" wire:submit.prevent="save">
                        {{ CmsForm::setErrorBag($errors) }}

                        <x-image_preview title="Current Webinar Thumbnail" :imageUrl="$webinarThumbnailUrl"/>
                        <x-input.image variable="webinarThumbnail" title="Webinar Thumnail" />

                        {!! CmsForm::select('webinar.category_id', $categoryOptions)->setTitle('Category') !!}
                        {!! CmsForm::text('webinar.title') !!}
                        <div class="form-group">
                            <x-input.tinymce labelName="Description" wire:model.defer="webinar.description" />
                        </div>
                        <div class="form-group">
                            <label for="startAt">Start At</label>
                            <input class="form-control" required wire:model.defer="startAt"
                                   name="startAt" type="datetime-local" id="startAt">
                            @error('startAt')
                            <p class="text-danger"> {{ $message }} </p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="endAt">End At</label>
                            <input class="form-control" required wire:model.defer="endAt"
                                   name="endAt" type="datetime-local" id="endAt">
                            @error('endAt')
                            <p class="text-danger"> {{ $message }} </p>
                            @enderror
                        </div>
                        {!! CmsForm::number('webinar.price', ['required' => false])->setTitle('Price (optional)')!!}
                        {!! CmsForm::text('webinar.zoom_id', ['required' => false])->setTitle('Zoom Id (optional)') !!}
                        {!! CmsForm::number('webinar.max_participants') !!}
                        {!! CmsForm::select('isPublished', $isPublishedOptions)->setTitle('Is Published') !!}

                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary">Update Webinar</button>
                            <button wire:click="backToIndex()" type="button" class="btn btn-light-primary ml-2">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
