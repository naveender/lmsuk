<!-- BEGIN: Vendor JS-->
<script src="{{ asset('theme/app-assets/vendors/js/vendors.min.js') }}"></script>
<script src="{{ asset('theme/app-assets/vendors/js/extensions/dropzone.min.js') }}"></script>
<script src="{{ asset('theme/app-assets/vendors/js/tables/datatable/datatables.min.js') }}"></script>
<script src="{{ asset('theme/app-assets/vendors/js/tables/datatable/datatables.buttons.min.js') }}"></script>
<script src="{{ asset('theme/app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('theme/app-assets/vendors/js/tables/datatable/buttons.bootstrap.min.js') }}"></script>
<script src="{{ asset('theme/app-assets/vendors/js/tables/datatable/dataTables.select.min.js') }}"></script>
<script src="{{ asset('theme/app-assets/vendors/js/tables/datatable/datatables.checkboxes.min.js') }}"></script>
<script src="{{ asset('theme/app-assets/vendors/js/charts/apexcharts.min.js') }}"></script>

<!-- BEGIN Vendor JS-->

<!-- BEGIN: Page Vendor JS-->
<script src="{{ asset('theme/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
<!-- END: Page Vendor JS-->

<!-- BEGIN: Theme JS-->
<script src="{{ asset('theme/app-assets/js/core/app-menu.js') }}"></script>
<script src="{{ asset('theme/app-assets/js/core/app.js') }}"></script>
<script src="{{ asset('theme/app-assets/js/scripts/components.js') }}"></script>



<script src="{{ asset('theme/app-assets/js/scripts/ui/data-list-view.js') }}"></script>
<script src="{{ asset('theme/app-assets/js/scripts/pickers/dateTime/pick-a-datetime.js') }}"></script>
<script src="{{ asset('theme/app-assets/js/scripts/pages/dashboard-ecommerce.js') }}"></script>

<!-- END: Theme JS-->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });

    window.toggleFilters = function(bodyId = 'filterBody', iconId = 'filterToggleIcon') {
        const body = document.getElementById(bodyId);
        const icon = document.getElementById(iconId);
        if (!body) return;
        const isHidden = window.getComputedStyle(body).display === 'none' || body.style.display === 'none';
        if (isHidden) {
            body.style.display = 'block';
            if (icon) icon.style.transform = 'rotate(0deg)';
        } else {
            body.style.display = 'none';
            if (icon) icon.style.transform = 'rotate(180deg)';
        }
    };
</script>

<script src="{{ asset('theme/app-assets/js/scripts/theme-switcher.js') }}"></script>
<script src="{{ asset('theme/app-assets/js/script.js') }}"></script>