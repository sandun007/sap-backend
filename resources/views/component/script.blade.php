<script src="{{asset('assets/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/hotkeys-js@3.8.7/dist/hotkeys.min.js"></script>
<script src="{{asset('assets/shortcut.js')}}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    // Sales Chart
    const ctx1 = document.getElementById('salesChart');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul'],
            datasets: [{
                label: 'Sales',
                data: [1200,1900,3000,2500,2800,3500,4200],
                borderColor: '#0070f3',
                fill: false,
                tension: 0.3
            }]
        }
    });

    // Stock Chart
    const ctx2 = document.getElementById('stockChart');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Available','Reserved','Sold'],
            datasets: [{
                data: [65,20,15],
                backgroundColor: ['#28a745','#ffc107','#dc3545']
            }]
        }
    });
</script>
<script>
    document.querySelectorAll('.dropdown-submenu .dropdown-toggle').forEach(function (element) {
        element.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            let nextMenu = this.nextElementSibling;
            nextMenu.classList.toggle('show');
        });
    });

</script>
<script>
    $(document).ready(function () {
        $('#brandTable').DataTable({
            "pageLength": 5,   // show 5 rows per page
            "lengthMenu": [5, 10, 25, 50],
            "ordering": true,  // enable sorting
            "searching": true, // enable search box
        });
    });
</script>
@include('settings.shortcut')
@stack('script')
