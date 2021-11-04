<?php

namespace Cms\Blade;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use RichanFongdasen\I18n\I18nService;

class MultilingualForm implements Htmlable
{
    /**
     * The name of currently multilingual form.
     *
     * @var string
     */
    protected $name;

    /**
     * MultilingualForm's HTML template.
     *
     * @var array
     */
    protected static $template = [
        'tabEndingElements'  => '</div><?php $_first = false; CmsForm::closeMultilingualGroup(); ?><?php endforeach; ?></div></div>',
        'tabNav'             => '<div class="example-preview mb-7"><ul class="nav nav-tabs" role="tablist">%s</ul><div class="tab-content pt-5">',
        'tabNavItem'         => '<li class="nav-item <?php echo ($_first) ? \'active\' : \'\'; ?>">%s</li>',
        'tabNavItemContent'  => '<a class="nav-link <?php echo ($_first) ? \'active\' : \'\'; ?>" data-toggle="tab" href="#%s-<?php echo $_locale->language; ?>"><?php echo $_locale->name; ?></a>',
        'tabNavLoop'         => '<?php $_first = true; foreach (\\I18n::getLocale() as $_locale) : ?>%s<?php $_first = false; endforeach; ?>',
        'tabOpeningElements' => '%s<?php $_first = true; ?><?php foreach (\\I18n::getLocale() as $_locale) : ?><?php CmsForm::createMultilingualGroup($_locale); ?><div class="tab-pane fade p-2 <?php echo ($_first) ? \'active show\' : \'\'; ?>" id="%s-<?php echo $_locale->language; ?>" role="tabpanel">',
    ];

    /**
     * MultilingualForm constructor.
     *
     * @param string|null $name
     */
    public function __construct(string $name = null)
    {
        $this->name = ($name !== null) ? Str::slug($name) : Str::slug(Str::random());
    }

    /**
     * Generate the content of MultilingualForm's tab navigation item.
     *
     * @return string
     */
    protected function generateNavigationItemContent(): string
    {
        return sprintf(self::$template['tabNavItemContent'], $this->getName());
    }

    /**
     * Generate the content of MultilingualForm's tab navigation loop.
     *
     * @return string
     */
    protected function generateNavigationLoop(): string
    {
        $navItem = sprintf(self::$template['tabNavItem'], $this->generateNavigationItemContent());

        return sprintf(self::$template['tabNavLoop'], $navItem);
    }

    /**
     * Generate the content of MultilingualForm's tab navigation.
     *
     * @return string
     */
    protected function generateTabNavigation(): string
    {
        return sprintf(self::$template['tabNav'], $this->generateNavigationLoop());
    }

    /**
     * Get MultilingualForm's ending html elements.
     *
     * @return string
     */
    public function getEndingElements(): string
    {
        return ($this->getLocaleCount() > 1) ? self::$template['tabEndingElements'] : '<?php CmsForm::closeMultilingualGroup(); ?>';
    }

    /**
     * Get locale count.
     *
     * @return int
     */
    protected function getLocaleCount(): int
    {
        $collection = app(I18nService::class)->getLocale();

        if ($collection instanceof Collection) {
            return (int) $collection->count();
        }

        return 0;
    }

    /**
     * Get multilingual form name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get MultilingualForm's opening html elements.
     *
     * @return string
     */
    public function getOpeningElements(): string
    {
        if ($this->getLocaleCount() === 1) {
            return '<?php $_locale = \I18n::getLocale()->first(); CmsForm::createMultilingualGroup($_locale); ?>';
        }

        return sprintf(self::$template['tabOpeningElements'], $this->generateTabNavigation(), $this->getName());
    }

    /**
     * Get content as a string of HTML.
     *
     * @return string
     */
    public function toHtml()
    {
        return $this->getOpeningElements();
    }

    /**
     * Get the HTML string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->toHtml();
    }
}
