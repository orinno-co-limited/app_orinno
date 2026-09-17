(function ($) {
    "use strict";

    var FILTER_COLUMN_INDEX = { category: 3, status: 4, district: 5 };

    function getActiveDataTable() {
        return window.__propertyDataTable || null;
    }

    function currentFilters() {
        return {
            search: ($('.property-search').val() || '').toLowerCase().trim(),
            category: $('.property-filter-category').val() || '',
            status: $('.property-filter-status').val() || '',
            district: $('.property-filter-district').val() || ''
        };
    }

    function applyGridFilter(filters) {
        var visibleCount = 0;
        $('.property-grid-item').each(function () {
            var $item = $(this);
            var matches =
                (!filters.category || $item.data('category') == filters.category) &&
                (!filters.status || $item.data('status') == filters.status) &&
                (!filters.district || $item.data('district') == filters.district) &&
                (!filters.search || String($item.data('search') || '').indexOf(filters.search) !== -1);

            $item.toggle(matches);
            if (matches) visibleCount++;
        });
        return visibleCount;
    }

    function applyTableFilter(filters) {
        var table = getActiveDataTable();
        if (!table) return null;

        table.search(filters.search);
        table.column(FILTER_COLUMN_INDEX.category).search(filters.category ? '^' + filters.category + '$' : '', true, false);
        table.column(FILTER_COLUMN_INDEX.status).search(filters.status ? '^' + filters.status + '$' : '', true, false);
        table.column(FILTER_COLUMN_INDEX.district).search(filters.district ? '^' + filters.district + '$' : '', true, false);
        table.draw();

        return table.rows({ search: 'applied' }).count();
    }

    function updateResultCount(count) {
        var $el = $('#propertyResultCount');
        if (!$el.length || count === null) return;
        $el.text(count + ' ' + (count === 1 ? 'result' : 'results'));
    }

    function runFilters() {
        var filters = currentFilters();
        var gridVisible = $('#propertyGridView').hasClass('d-none') ? null : applyGridFilter(filters);
        var tableVisible = $('#propertyListView').hasClass('d-none') ? null : applyTableFilter(filters);
        updateResultCount(gridVisible !== null ? gridVisible : tableVisible);
    }

    $(document).on('input', '.property-search', function () {
        clearTimeout(window.__propertyFilterDebounce);
        window.__propertyFilterDebounce = setTimeout(runFilters, 200);
    });

    $(document).on('change', '.property-filter-category, .property-filter-status, .property-filter-district', runFilters);

    $(document).on('click', '.view-toggle-btn', function () {
        setTimeout(runFilters, 50);
    });

    $(function () {
        setTimeout(runFilters, 300);
    });

})(jQuery);
