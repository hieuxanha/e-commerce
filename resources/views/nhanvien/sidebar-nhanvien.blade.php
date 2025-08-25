 <!-- Sidebar -->



 <aside class="side">
     <div class="brand">
         <img src="https://i.pravatar.cc/56?img=12" alt="">
         <div>
             <div>Nhân viên</div>
             <small style="color:var(--muted)">Silver</small>
         </div>
     </div>

     <nav class="menu">
         <a class="mi " href="{{ route('nhanvien.dashboard') }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                 <path d="M3 12l2-2 4 4 8-8 4 4" stroke-width="2" />
             </svg> Trang chủ</a>

         <div class="mi has-sub {{ request()->routeIs('nhanvien.danhsachsanpham') ? '' : '' }}">
             <div class="menu-parent">
                 <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                     <circle cx="12" cy="12" r="9" stroke-width="2" />
                 </svg>
                 <span>Quản lý sản phẩm</span>
             </div>
             <div class="submenu">
                 <a href="{{ route('nhanvien.sanpham.them') }}">Thêm sản phẩm</a>

                 <a href="{{ route('nhanvien.danhsachsanpham') }}">Danh sách sản phẩm</a>
                 <a href="{{ route('nhanvien.brands.index') }}">
                     Xem danh sách thương hiệu
                 </a>
                 <a href="{{ route('nhanvien.categories.index') }}">
                     Danh danh mục</a>
             </div>

         </div>


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
                 <path d="M3 3h18v14H3z" stroke-width="2" />
                 <path d="M3 9h18" stroke-width="2" />
             </svg> Quản lý vận chuyển</a>
         <a class="mi" href="#"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                 <path d="M12 1v22M1 12h22" stroke-width="2" />
             </svg> Quản lý thanh toán</a>
         <a class="mi" href="#"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                 <path d="M12 17l-5 3 1-6-4-4 6-1 2-5 2 5 6 1-4 4 1 6z" stroke-width="2" />
             </svg> Quản lý nội dung website</a>

         <a class="mi" href="#"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                 <path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" stroke-width="2" />
             </svg> Báo cáo và hệ thống nâng cao</a>

     </nav>
 </aside>
 <!-- Main -->