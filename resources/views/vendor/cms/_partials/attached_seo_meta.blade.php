
<div class="mt-16 mb-16">
    <div class="mb-8">
        <h3>Attached SEO Meta Data</h3>
    </div>

    @multilingual
        {!! CmsForm::text('seoMeta.seo_title', $component->getSeoFormAttribute())->setTitle('Title') !!}
        {!! CmsForm::textarea('seoMeta.seo_description', $component->getSeoFormAttribute())->setTitle('Description') !!}
        {!! CmsForm::select('seoMeta.open_graph_type', $openGraphTypes, $component->getSeoFormAttribute()) !!}

        @php
            $localeKey = config('i18n.language_key', 'language');
        @endphp
        <div class="form-group mt-12">
            @if($component->getSeoFormOperation() === 'view')
                @if ($component->getAttachedModel()->getSeoMetaAttribute($_locale->{$localeKey})->exists && $component->getAttachedModel()->getSeoMetaAttribute($_locale->{$localeKey})->getFirstMediaUrl('seo_image', 'seo_image_small'))
                    <label for="seoImage" style="display: block;">SEO Image</label>
                    <img src="{{ asset($component->getAttachedModel()->getSeoMetaAttribute($_locale->{$localeKey})->getFirstMediaUrl('seo_image', 'seo_image_small')) }}" style="border: 1px solid #333;" />
                @endif
            @else
                <label for="seoMedia">SEO Image</label>
                <x-media-library-attachment name="seoMedia{{ ucfirst($_locale->{$localeKey}) }}" rules="mimes:jpeg,png" />
                <div class="font-size-sm mt-2 text-info">It is recommended to upload an image with 1600x800 resolution.</div>

                @if ($component->getAttachedModel()->getSeoMetaAttribute($_locale->{$localeKey})->exists && $component->getAttachedModel()->getSeoMetaAttribute($_locale->{$localeKey})->getFirstMediaUrl('seo_image', 'seo_image_small'))
                    <div class="mt-6">
                        <img src="{{ asset($component->getAttachedModel()->getSeoMetaAttribute($_locale->{$localeKey})->getFirstMediaUrl('seo_image', 'seo_image_small')) }}" style="border: 1px solid #333;" />
                    </div>
                @endif
            @endif
        </div>
    @endmultilingual

</div>
