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
                    <h3 class="card-title">Static Page Detail #{{ $staticPage->getKey() }}</h3>
                </div>
                <div class="card-body">
                    @include('cms::_partials.alert')

                    <form class="form">
                        {{ CmsForm::setErrorBag($errors) }}

                        {!! CmsForm::text('staticPage.name', ['disabled' => 'disabled']) !!}
                        {!! CmsForm::text('staticPage.slug', ['disabled' => 'disabled', 'value' => $staticPage->slug]) !!}
                        {!! CmsForm::textarea('staticPage.content', ['disabled' => 'disabled']) !!}
                        {!! CmsForm::text('staticPage.youtube_video', ['disabled' => 'disabled']) !!}
                        {!! CmsForm::text('staticPage.layout', ['disabled' => 'disabled']) !!}
                        {!! CmsForm::text('staticPage.published', ['disabled' => 'disabled']) !!}

                        @include($seoMetaBlade, ['component' => $this])

                        <div class="form-group text-center">
                            @if($this->currentAdmin->can('cms.static_pages.update'))
                                <button wire:click="edit()" type="button" class="btn btn-warning mr-2">
                                    Edit Static Page
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
