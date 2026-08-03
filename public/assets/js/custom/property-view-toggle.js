(function () {
    "use strict";

    var STORAGE_KEY = 'propertyListView';

    function setView(view) {
        var grid = document.getElementById('propertyGridView');
        var list = document.getElementById('propertyListView');
        if (!grid || !list) return;

        grid.classList.toggle('d-none', view !== 'grid');
        list.classList.toggle('d-none', view !== 'list');

        document.querySelectorAll('.view-toggle-btn').forEach(function (btn) {
            btn.classList.toggle('active', btn.dataset.view === view);
        });

        if (view === 'list' && window.__propertyDataTable) {
            window.__propertyDataTable.columns.adjust();
        }

        localStorage.setItem(STORAGE_KEY, view);
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.view-toggle-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                setView(this.dataset.view);
            });
        });

        var saved = localStorage.getItem(STORAGE_KEY);
        if (saved === 'grid' || saved === 'list') {
            setView(saved);
        }
    });
})();
