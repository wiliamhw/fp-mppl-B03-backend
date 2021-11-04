@section('additional_scripts')
<script type="text/javascript">
    window.resourceUrl = '{{ route('cms.seo_metas.index') }}';
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
                    <h3 class="card-title">Create New Seo Meta</h3>
                </div>
                <div class="card-body">
                    @include('cms::_partials.alert')

                    <form class="form" wire:submit.prevent="save">
                        {{ CmsForm::setErrorBag($errors) }}

                        {!! CmsForm::text('seoMeta.seo_url')->setTitle('URL') !!}
                        {!! CmsForm::text('seoMeta.seo_title')->setTitle('Title') !!}
                        {!! CmsForm::textarea('seoMeta.seo_description')->setTitle('Description') !!}
                        {!! CmsForm::select('seoMeta.open_graph_type', $openGraphTypes) !!}

                        <div class="form-group mb-12">
                            <label for="seoImage">SEO Image</label>
                            <x-media-library-attachment name="seoImage" rules="mimes:jpeg,png" />
                            <div class="font-size-sm mt-2 text-info">It is recommended to upload an image with 1600x800 resolution.</div>
                        </div>

                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary">Save Seo Meta</button>
                            <button wire:click="backToIndex()" type="button" class="btn btn-light-primary ml-2">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
