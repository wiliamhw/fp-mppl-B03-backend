<?php

namespace Tests;

use App\Models\SeoMeta;
use Cms\Models\Concerns\HasSeoMeta;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Str;
use RichanFongdasen\I18n\I18nService;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Fake any media file using the given filename and
     * based on the given dummy source path.
     *
     * @param string $filename
     * @param string $dummySource
     *
     * @return File
     */
    protected function fakeMedia(string $filename, string $dummySource): File
    {
        $tmpFile = tap(tmpfile(), static function ($temp) use ($dummySource) {
            fwrite($temp, file_get_contents(public_path($dummySource)));
        });

        return new File($filename, $tmpFile);
    }

    /**
     * Fake the model's raw data and remove any translation attributes.
     *
     * @param string $modelName
     * @param array  $attributes
     *
     * @return array
     */
    protected function fakeRawData(string $modelName, array $attributes = []): array
    {
        $model = app($modelName);
        if (!($model instanceof Model)) {
            return [];
        }

        $raw = Factory::factoryForModel($modelName)->raw($attributes);

        if (method_exists($model, 'getTranslatableAttributes')) {
            foreach ($model->getTranslatableAttributes() as $attribute) {
                if (isset($raw[$attribute])) {
                    unset($raw[$attribute]);
                }
            }
        }

        return $raw;
    }

    /**
     * Fake the attachable SEO Meta data, and group them for each locale.
     *
     * @param string $attachableType
     * @param int    $attachableId
     *
     * @return array
     */
    protected function fakeAttachedSeoMetaData(string $attachableType, int $attachableId = 1): array
    {
        $model = app($attachableType);
        if (!($model instanceof Model) || !in_array(HasSeoMeta::class, array_values(class_uses($model)), true)) {
            return [];
        }

        $locales = app(I18nService::class)->getLocale()->keys()->all();
        $result = [];

        foreach ($locales as $locale) {
            $baseAttributes = [
                'attachable_type' => $attachableType,
                'attachable_id'   => $attachableId,
                'locale'          => $locale,
                'seo_url'         => null,
            ];

            $result[$locale] = SeoMeta::factory()->raw($baseAttributes);
        }

        return $result;
    }

    /**
     * Fake the model's translation data, and group them for each locale.
     *
     * @param string $modelName
     * @param int    $foreignKeyValue
     *
     * @return array
     */
    protected function fakeTranslationData(string $modelName, int $foreignKeyValue = 1): array
    {
        $model = app($modelName);
        if (!($model instanceof Model) || !method_exists($model, 'getTranslatableAttributes')) {
            return [];
        }

        $raw = Factory::factoryForModel($modelName)->raw();
        $locales = app(I18nService::class)->getLocale()->keys()->all();
        $result = [];

        foreach ($locales as $locale) {
            if (!isset($result[$locale])) {
                $foreignKey = Str::snake(class_basename($model)).'_id';
                $result[$locale] = [
                    $foreignKey => $foreignKeyValue,
                    'locale'    => $locale,
                ];
            }

            foreach ($model->getTranslatableAttributes() as $attribute) {
                $result[$locale][$attribute] = $raw[$attribute][$locale];
            }
        }

        return $result;
    }

    /**
     * Get any protected / private property value.
     *
     * @param mixed  $object
     * @param string $propertyName
     *
     * @throws \ReflectionException If no property exists by that name.
     *
     * @return mixed
     */
    public function getPropertyValue($object, $propertyName)
    {
        $reflection = new \ReflectionClass(get_class($object));
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        return $property->getValue($object);
    }

    /**
     * Invoke protected / private method of the given object.
     *
     * @param object $object
     * @param string $methodName
     * @param array  $parameters
     *
     * @throws \ReflectionException if the method does not exist.
     *
     * @return mixed
     */
    protected function invokeMethod($object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
