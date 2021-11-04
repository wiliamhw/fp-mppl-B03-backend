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
                    <h3 class="card-title">Seo Meta Detail #{{ $seoMeta->getKey() }}</h3>
                </div>
                <div class="card-body">
                    @include('cms::_partials.alert')

                    <form class="form">
                        {{ CmsForm::setErrorBag($errors) }}

                        {!! CmsForm::text('seoMeta.seo_url', ['disabled' => 'disabled'])->setTitle('URL') !!}
                        {!! CmsForm::text('seoMeta.seo_title', ['disabled' => 'disabled'])->setTitle('Title') !!}
                        {!! CmsForm::textarea('seoMeta.seo_description', ['disabled' => 'disabled'])->setTitle('Description') !!}
                        {!! CmsForm::text('seoMeta.open_graph_type', ['disabled' => 'disabled']) !!}

                        @if ($seoMeta->getFirstMediaUrl('seo_image', 'seo_image_small'))
                            <div class="form-group">
                                <label for="seoImage" style="display: block;">SEO Image</label>
                                <img src="{{ asset($seoMeta->getFirstMediaUrl('seo_image', 'seo_image_small')) }}" alt="{{ $seoMeta->title }}" style="border: 1px solid #333;" />
                            </div>
                        @endif

                        <div class="form-group text-center">
                            @if($this->currentAdmin->can('cms.seo_metas.update'))
                                <button wire:click="edit()" type="button" class="btn btn-warning mr-2">
                                    Edit Seo Meta
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
