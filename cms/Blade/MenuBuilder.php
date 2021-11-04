<?php

namespace Cms\Blade;

use App\Models\Admin;
use ErrorException;
use Illuminate\Support\Str;

class MenuBuilder
{
    /**
     * Html template for main navigation menu.
     *
     * @var array
     */
    protected static $template = [
        'menuIcon'       => '<i class="%s"><span></span></i>',
        'mainMenu'       => '<ul class="menu-nav">%s</ul>',
        'mainMenuItem'   => '<li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover"><a href="%s" class="menu-link menu-toggle"><i class="%s"></i><span class="menu-text">%s</span>%s</a>%s</li>',
        'childMenu'      => '<div class="menu-submenu"><i class="menu-arrow"></i><ul class="menu-subnav">%s</ul></div>',
        'childMenuItem'  => '<li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover"><a class="menu-link menu-toggle" href="%s">%s <span class="menu-text">%s</span>%s</a>%s</li>',
        'parentMenuItem' => '<li class="menu-item menu-item-parent" aria-haspopup="true"><span class="menu-link"><span class="menu-text">%s</span></span></li>',
    ];

    /**
     * Current authenticated admin.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected $admin;

    /**
     * Check if the current admin has access to the specific menu item.
     *
     * @param string $context
     *
     * @throws ErrorException
     *
     * @return bool
     */
    protected function accessPermitted(string $context): bool
    {
        $permissions = explode('|', $context);

        foreach ($permissions as $permission) {
            if ($this->getAdmin()->hasPermissionTo($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Build the child menus.
     *
     * @param array $children
     *
     * @throws ErrorException
     *
     * @return string
     */
    protected function buildChildMenu(array $children): string
    {
        $result = '';

        foreach ($children as $child) {
            $result .= $this->buildChildMenuItem($child);
        }

        if (!empty($result)) {
            $result = sprintf(self::$template['childMenu'], $result);
        }

        return $result;
    }

    /**
     * Build child menu item from the given data.
     *
     * @param array $data
     *
     * @throws ErrorException
     *
     * @return string|null
     */
    protected function buildChildMenuItem(array $data): ?string
    {
        if (!$this->accessPermitted($data['permission'])) {
            return null;
        }

        $children = data_get($data, 'children', []);

        $icon = (isset($data['icon']) && !empty($data['icon'])) ?
            sprintf(self::$template['menuIcon'], htmlspecialchars($data['icon'])) :
            '';

        $expandable = !empty($children) ? '<i class="menu-arrow"></i>' : '';

        return sprintf(
            self::$template['childMenuItem'],
            $this->parseUrl(data_get($data, 'url', '/')),
            $icon,
            htmlspecialchars(data_get($data, 'title', 'Untitled')),
            $expandable,
            $this->buildChildMenu($children)
        );
    }

    /**
     * Build parent menu item and its children.
     *
     * @param array $data
     *
     * @throws ErrorException
     *
     * @return string|null
     */
    protected function buildMenuItem(array $data): ?string
    {
        if (!$this->accessPermitted($data['permission'])) {
            return null;
        }

        $children = data_get($data, 'children', []);

        $expandable = !empty($children) ? '<i class="menu-arrow"></i>' : '';

        return sprintf(
            self::$template['mainMenuItem'],
            $this->parseUrl(data_get($data, 'url', '/')),
            htmlspecialchars(data_get($data, 'icon')),
            htmlspecialchars(data_get($data, 'title', 'Untitled')),
            $expandable,
            $this->buildChildMenu($children)
        );
    }

    /**
     * Get the currently logged in Admin instance.
     *
     * @throws ErrorException
     *
     * @return Admin
     */
    protected function getAdmin(): Admin
    {
        if ($this->admin === null) {
            $this->admin = auth()->guard(config('cms.guard'))->user();
        }

        if (!($this->admin instanceof Admin)) {
            throw new ErrorException('Failed to identify the CMS Admin.');
        }

        return $this->admin;
    }

    /**
     * @param string $url
     *
     * @return string
     */
    protected function parseUrl(string $url): string
    {
        return !Str::startsWith(Str::lower($url), 'javascript') ?
            url($url) :
            $url;
    }

    /**
     * Build the CMS main navigation component.
     *
     * @throws ErrorException
     *
     * @return string
     */
    public function toHtml(): string
    {
        $result = '';
        $items = config('cms_menu.items');

        foreach ($items as $item) {
            $result .= $this->buildMenuItem($item);
        }

        if (!empty($result)) {
            $result = sprintf(self::$template['mainMenu'], $result);
        }

        return $result;
    }
}
