<!-- Sidebar -->
<aside class="side">
    <div class="brand">
        <img src="https://i.pravatar.cc/56?img=12" alt="">
        <div>
            <div>ADMIN</div>
            <small style="color:var(--muted)">Silver</small>
        </div>
    </div>

    <nav class="menu">
        <a class="mi " href="{{ route('admin.dashboard') }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M3 12l2-2 4 4 8-8 4 4" stroke-width="2" />
            </svg> Trang chủ</a>

        <!-- mươn tạm bên nhan vien -->
        <div class="mi has-sub {{ request()->routeIs('admin.danhsachsanpham') ? '' : '' }}">
            <div class="menu-parent">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <circle cx="12" cy="12" r="9" stroke-width="2" />
                </svg>
                <span>Quản lý sản phẩm</span>
            </div>
            <div class="submenu">
                <a href="{{ route('admin.sanpham.them') }}">Thêm sản phẩm</a>

                <a href="{{ route('admin.danhsachsanpham') }}">Danh sách sản phẩm</a>
                <a href="{{ route('admin.brands.index') }}">
                    Xem danh sách thương hiệu
                </a>
                <a href="{{ route('admin.categories.index') }}">
                    Danh danh mục</a>
            </div>

        </div>
        <!-- 
        <a class="mi" href="#"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <circle cx="12" cy="12" r="9" stroke-width="2" />
            </svg> Quản lý sản phẩm</a> -->

        <a class="mi {{ request()->routeIs('admin.vanchuyen.*') ? 'active' : '' }}"
            href="{{ route('admin.vanchuyen.index') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <rect x="3" y="4" width="18" height="16" rx="3" stroke-width="2" />
                <path d="M7 8h10" stroke-width="2" />
            </svg> Quản lý vận chuyển
        </a>
        <a class="mi {{ request()-> routeIs('admin.orders.*') ? 'active' : '' }}"
            href="{{ route('admin.orders.index') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <rect x="3" y="4" width="18" height="16" rx="3" stroke-width="2" />
                <path d="M7 8h10" stroke-width="2" />
            </svg> Quản lý dơn hàng</a>

        <a class="mi {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}"
            href="{{ route('admin.reviews.index') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M21 15a4 4 0 0 1-4 4H8l-5 4V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4v8z" stroke-width="2" />
            </svg>
            Quản lý đánh giá
        </a>



        <a class="mi {{ request()->routeIs('admin.QL_khachhang.*') ? 'active' : ''}}"
            href="{{ route('admin.QL_khachhang.index') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M20 7l-8 10-5-5" stroke-width="2" />
            </svg> Quản lý khách hàng</a>

        <a class="mi {{ request()->routeIs('admin.phanquyen.*') ? 'active' : ' '}}"
            href="{{route('admin.phanquyen.index') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" stroke-width="2" />
            </svg>Quản lý phân quyền</a>


        <a class="mi {{ request()->routeIs('admin.tonkho') ? 'active' : ''}} 
        " href="{{route('admin.tonkho.index') }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M3 3h18v14H3z" stroke-width="2" />
                <path d="M3 9h18" stroke-width="2" />
            </svg>Quản lý tồn kho</a>


        <a class="mi {{request()->routeIs('admin.thongke') ? 'active' : ' '}}
        " href="{{route('admin.thongke.index')}}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M12 17l-5 3 1-6-4-4 6-1 2-5 2 5 6 1-4 4 1 6z" stroke-width="2" />
            </svg> Thống kê</a>



        <!-- đang pjaats triển  -->
        <a href="{{ route('admin.coupons.index') }}"
            class="mi d-inline-flex align-items-center gap-2 {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}"
            aria-label="Quản lý mã giảm giá" title="Quản lý mã giảm giá">
            <svg viewBox="0 0 24 24" class="mi-icon" aria-hidden="true">
                <path d="M3 3h18v14H3z" />
                <path d="M3 9h18" />
            </svg>
            <span>Quản lý mã giảm giá</span>
        </a>


        <a class="mi" href="#"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M12 17l-5 3 1-6-4-4 6-1 2-5 2 5 6 1-4 4 1 6z" stroke-width="2" />
            </svg> Quản lý nội dung website</a>

        <a class="mi" href="#"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" stroke-width="2" />
            </svg> Báo cáo và hệ thống nâng cao</a>


</aside>


<script>
    document.addEventListener('click', e => {
        const item = e.target.closest('.mi.has-sub .menu-parent');
        if (item) {
            e.preventDefault();
            item.parentElement.classList.toggle('open');
        }
    });
</script>