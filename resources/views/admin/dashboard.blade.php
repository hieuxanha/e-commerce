{{-- resources/views/admin/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Chiến dịch tuyển dụng</title>
    <link rel="stylesheet" href="">
    <link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}" />

    <!-- <link rel="stylesheet" href="{{ asset('css/login.css') }}" /> -->


</head>

<body>
    <div class="app">
        <!-- Sidebar -->
        <aside class="side">
            <div class="brand">
                <img src="https://i.pravatar.cc/56?img=12" alt="">
                <div>
                    <div>ADMIssssN</div>
                    <small style="color:var(--muted)">Silver</small>
                </div>
            </div>

            <nav class="menu">
                <a class="mi active" href="#"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M3 12l2-2 4 4 8-8 4 4" stroke-width="2" />
                    </svg> Trang chủ</a>
                <a class="mi {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}"
                    href="{{ route('admin.reviews.index') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M21 15a4 4 0 0 1-4 4H8l-5 4V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4v8z" stroke-width="2" />
                    </svg>
                    Quản lý đánh giá
                </a>

                <a class="mi" href="#"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <circle cx="12" cy="12" r="9" stroke-width="2" />
                    </svg> Quản lý sản phẩm</a>


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

                <div class="title">Chi tiết chiến dịch:</div>
                <div style="font-weight:700">Telesale Làm Việc Tạ</div>
                <div class="sep"></div>
                <span class="chip">Điểm tối ưu: 78%</span>
            </div>

            <div class="content">
                <!-- KPIs -->
                <div class="grid kpis">
                    <div class="card kpi">
                        <div class="lbl">......</div>
                        <div class="val">85</div>
                    </div>
                    <div class="card kpi green">
                        <div class="lbl">///////////</div>
                        <div class="val">79</div>
                    </div>
                    <div class="card kpi red">
                        <div class="lbl">Ở LIÊN HỆ</div>
                        <div class="val">6</div>
                    </div>
                    <div class="card kpi orange">
                        <div class="lbl">SỐ CREDIT ĐÃ SỬ DỤNG</div>
                        <div class="val">95</div>
                    </div>
                    <div class="card kpi">
                        <div class="lbl">SỐ LƯỢT MỞĐÃ DÙNG</div>
                        <div class="val">0</div>
                    </div>
                </div>

                <!-- Tabs + Chart -->
                <div class="card" style="margin-top:16px;">
                    <div class="tabs">
                        <div class="tab active">Tin tuyển dụng</div>
                        <div class="tab">CV ứng tuyển <span style="background:#fee2e2;color:#b91c1c;margin-left:6px;padding:2px 6px;border-radius:999px">2</span></div>
                        <div class="tab">Ứng viên đã xem tin <span style="background:#e0f2fe;color:#0369a1;margin-left:6px;padding:2px 6px;border-radius:999px">545</span></div>
                        <div class="tab">CV Scout</div>
                        <div class="tab">CV tìm kiếm</div>
                        <div class="tab">CV đang theo dõi</div>
                        <div class="tab">CV được hỗ trợ</div>
                        <div class="tab">Dịch vụ</div>
                    </div>

                    <div class="chart-wrap">
                        <div class="chart-head">
                            <div>7 ngày qua</div>
                            <div style="display:flex;align-items:center;gap:16px;margin-left:14px">
                                <span>Đẩy top tự động gần nhất lúc 18:56 02/11/2021</span>
                            </div>
                            <div class="view-switch">
                                <button class="active">Giờ</button>
                                <button>Ngày</button>
                            </div>
                        </div>
                        <canvas id="chart" height="120"></canvas>

                        <div style="display:flex;gap:18px;margin-top:10px;color:var(--muted);font-size:12px">
                            <span>● Lượt hiển thị</span>
                            <span>● Lượt xem</span>
                            <span>● Lượt ứng tuyển</span>
                        </div>
                    </div>
                </div>

                <!-- Table mini -->
                <div class="card" style="margin-top:16px;">
                    <table class="t">
                        <thead>
                            <tr>
                                <th>Thao tác</th>
                                <th>Tin tuyển dụng</th>
                                <th>Số lần hiển thị</th>
                                <th>Số lượt xem</th>
                                <th>Tỷ lệ xem tin</th>
                                <th>Số lượt ứng tuyển</th>
                                <th>Tỷ lệ ứng tuyển</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><a href="#" style="color:#0ea5e9;text-decoration:none">Chi tiết</a></td>
                                <td>Telesale Làm Việc Tại Văn Phòng Ở Quận Tân…</td>
                                <td>4711</td>
                                <td>492</td>
                                <td>10.44%</td>
                                <td>21</td>
                                <td>4.27%</td>
                            </tr>
                            <tr>
                                <td><a href="#" style="color:#0ea5e9;text-decoration:none">Chi tiết</a></td>
                                <td>Nhân viên CSKH part-time</td>
                                <td>2331</td>
                                <td>210</td>
                                <td>9.01%</td>
                                <td>8</td>
                                <td>3.81%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
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