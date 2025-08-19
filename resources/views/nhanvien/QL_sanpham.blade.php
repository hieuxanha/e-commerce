{{-- resources/views/admin/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Chiến dịch tuyển dụng</title>
    <link rel="stylesheet" href="">
    <link rel="stylesheet" href="{{ asset('css/nhanvien/QL_sanpham.css') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- <link rel="stylesheet" href="{{ asset('css/login.css') }}" /> -->







</head>

<body>
    <div class="app">
        <!-- Sidebar -->
        <aside class="side">
            <div class="brand">
                <img src="https://i.pravatar.cc/56?img=12" alt="">
                <div>
                    <div>Nhân viên</div>
                    <small style="color:var(--muted)">Silver</small>
                </div>x
            </div>

            <nav class="menu">
                <a class="mi {{ request()->routeIs('nhanvien.dashboard') ? 'active' : '' }}"
                    href="{{ route('nhanvien.dashboard') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M3 12l2-2 4 4 8-8 4 4" stroke-width="2" />
                    </svg> Trang chủ</a>

                <a class="mi {{ request()->routeIs('nhanvien.danhsachsanpham') ? 'active' : '' }}"
                    href="{{ route('nhanvien.danhsachsanpham') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <circle cx="12" cy="12" r="9" stroke-width="2" />
                    </svg> Quản lý sản phẩm</a>1


                <a class="mi " href="#"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <rect x="3" y="4" width="18" height="16" rx="3" stroke-width="2" />
                        <path d="M7 8h10" stroke-width="2" />
                    </svg> Quản lý dơn hàng</a>
                <a class="mi" href="#"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M20 7l-8 10-5-5" stroke-width="2" />
                    </svg> Quản lý khách hàng</a>
                <a class="mi" href="#"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M3 3h18v14H3z" stroke-width="2" />
                        <path d="M3 9h18" stroke-width="2" />
                    </svg> Quản lý khuyến mãi và mã giảm giá</a>

                <a class="mi" href="#"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M12 1v22M1 12h22" stroke-width="2" />
                    </svg> Quản lý thanh toán</a>
                <a class="mi" href="#"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M12 17l-5 3 1-6-4-4 6-1 2-5 2 5 6 1-4 4 1 6z" stroke-width="2" />
                    </svg> Quản lý nội dung website</a>

                <a class="mi" href="#"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" stroke-width="2" />
                    </svg> Báo cáo và hệ thống nâng cao</a>

                <a class="mi" href="#"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" stroke-width="2" />
                    </svg>Quản lý phân quyền</a>
            </nav>
        </aside>

        <!-- Main -->
        <main>
            <!-- Topbar -->
            <div class="top">
                <form class="top-search" action="#" method="GET" role="search">
                    <input
                        type="text"
                        name="q"
                        class="top-search-input"
                        placeholder="Tìm sản phẩm, danh mục, thương hiệu..."
                        autocomplete="off" />
                    <button class="top-search-btn" aria-label="Tìm kiếm">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7"></circle>
                            <line x1="16.65" y1="16.65" x2="21" y2="21"></line>
                        </svg>
                    </button>
                </form>

                <div class="content">
                    dd
                </div>




        </main>
    </div>
    {{-- Tabs JS (đơn giản) --}}
    <script>
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                const target = btn.dataset.tab;
                document.querySelectorAll('.tab-panel').forEach(p => {
                    p.classList.toggle('active', p.id === 'tab-' + target);
                });
            });
        });
    </script>
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