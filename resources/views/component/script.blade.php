<script src="{{asset('assets/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

@stack('script')
