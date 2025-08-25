{{-- resources/views/admin/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Chiến dịch tuyển dụng</title>
    <link rel="stylesheet" href="">
    <link rel="stylesheet" href="{{ asset('css/nhanvien/nhanvien.css') }}" />
    <!-- co css tim kiem trong này  -->
    <link rel="stylesheet" href="{{ asset('css/nhanvien/timkiem.css') }}" />



    <!-- <link rel="stylesheet" href="{{ asset('css/login.css') }}" /> -->


</head>

<body>
    <div class="app">
        <!-- Sidebar -->

        @include('nhanvien.sidebar-nhanvien')

        <!-- Main -->
        <main>
            <div class="top">
                <form class="top-search" action="#" method="GET" role="search">
                    <input type="text" name="q" class="top-search-input" placeholder="Tìm sản phẩm, danh mục, thương hiệu..." autocomplete="off" />
                    <button class="top-search-btn" aria-label="Tìm kiếm">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7"></circle>
                            <line x1="16.65" y1="16.65" x2="21" y2="21"></line>
                        </svg>
                    </button>
                </form>
            </div>

            acbbbb

        </main>
    </div>

    <!-- Chart.js CDN (chỉ để vẽ demo UI) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('chart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['27/10', '28/10', '29/10', '30/10', '31/10', '01/11', '02/11'],
                datasets: [{
                        label: 'Lượt hiển thị',
                        data: [30, 55, 20, 90, 40, 160, 120],
                        borderWidth: 2,
                        tension: .35
                    },
                    {
                        label: 'Lượt xem',
                        data: [5, 10, 7, 22, 12, 28, 18],
                        borderWidth: 2,
                        tension: .35
                    },
                    {
                        label: 'Ứng tuyển',
                        data: [1, 2, 1, 4, 2, 6, 3],
                        borderWidth: 2,
                        tension: .35
                    },
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        grid: {
                            color: '#f1f5f9'
                        },
                        ticks: {
                            color: '#64748b'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#64748b'
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>