let CmsApp = {
    init: function () {
        this.findActiveMenu();
    },

    findActiveMenu: function () {
        if (typeof window.resourceUrl == "undefined") {
            return false;
        }

        $(document).find('.menu-link.menu-toggle').each(function () {
            if ($(this).attr('href') === window.resourceUrl) {
                CmsApp.markActiveMenu($(this).parent());
            }
        });
    },

    markActiveMenu: function (element) {
        $(element).addClass('menu-item-active');

        $(element).parents('li.menu-item').first().addClass('menu-item-open');
        $(element).parents('li.menu-item').first().addClass('menu-item-here');
    },

    initSelect2: function (callback) {
        $('.input-select2').select2();

        if (typeof(callback) !== "undefined") {
            callback();
        }
    }
};

$(document).ready(function () {
    CmsApp.init();
});
