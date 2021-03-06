<?php

use App\Models\Admin;

/**
 * @SuppressWarnings(PHPMD.MissingImport)
 */
if (!function_exists('cms_admin')) {
    /**
     * Get the current authenticated cms admin.
     *
     * @throws \ErrorException
     *
     * @return Admin
     */
    function cms_admin(): Admin
    {
        $admin = auth()->guard(config('cms.guard'))->user();

        if (!($admin instanceof Admin)) {
            throw new \ErrorException('The logged in user is not an instance of CMS Admin model.');
        }

        return $admin;
    }
}

if (!function_exists('formatRupiah')) {
    /**
     * Convert int to rupiah string
     *
     * @param int $value
     * @return string
     */
    function formatRupiah(int $value): string
    {
        return 'Rp ' . number_format($value , 0, ',', '.');
    }
}
