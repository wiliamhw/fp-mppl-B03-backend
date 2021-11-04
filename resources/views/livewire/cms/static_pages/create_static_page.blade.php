@section('additional_scripts')
<script type="text/javascript">
    window.resourceUrl = '{{ route('cms.static_pages.index') }}';
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
                    <h3 class="card-title">Create New Static Page</h3>
                </div>
                <div class="card-body">
                    @include('cms::_partials.alert')

                    <form class="form" wire:submit.prevent="save">
                        {{ CmsForm::setErrorBag($errors) }}

                        {!! CmsForm::text('staticPage.name') !!}
                        <x-input.tinymce labelName="Content" wire:model="staticPage.content" />
                        {!! CmsForm::text('staticPage.youtube_video') !!}
                        {!! CmsForm::select('staticPage.layout', $layoutOptions) !!}
                        {!! CmsForm::select('staticPage.published', $publishedOptions) !!}

                        @include($seoMetaBlade, ['component' => $this])

                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary">Save Static Page</button>
                            <button wire:click="backToIndex()" type="button" class="btn btn-light-primary ml-2">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
