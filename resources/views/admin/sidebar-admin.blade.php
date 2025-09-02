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
        <a class="mi " href="#"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
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

        <a class="mi {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"
            href="{{ route('admin.orders.index') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <rect x="3" y="4" width="18" height="16" rx="3" stroke-width="2" />
                <path d="M7 8h10" stroke-width="2" />
            </svg> Quản lý vận chuyển
        </a>

        <!-- 

        <a class="mi" href="#"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M20 7l-8 10-5-5" stroke-width="6" rx="3" stroke-width="2" />
                <path d="M7 8h10" stroke-width="2" />
            </svg> Quản lý vận chuyển
        </a> -->



        <a class="mi" href="#"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M20 7l-8 10-5-5" stroke-width="2" />
            </svg> Quản lý khách hàng</a>

        <a class="mi {{ request()->routeIs('admin.phanquyen.*') ? 'active' : ' '}}"
            href="{{route('admin.phanquyen.index') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" stroke-width="2" />
            </svg>Quản lý phân quyền</a>

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