{{-- resources/views/admin/coupons/index.blade.php --}}
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Mã giảm giá</title>

    <link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/nhanvien/timkiem.css') }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        .card-surface {
            border: 1px solid #eaeef4;
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(16, 24, 40, .06)
        }

        .form-hint {
            font-size: .85rem;
            color: #6b7280
        }

        .table th {
            white-space: nowrap
        }

        .sticky-actions {
            position: sticky;
            right: 0;
            background: #fff
        }

        .list-filter .input-group-text {
            background: #fff
        }

        .list-filter input {
            font-size: .9rem
        }

        select[multiple] {
            font-size: .95rem
        }

        .level-badge {
            font-size: .75rem
        }
    </style>
</head>

<body>
    <div class="app">
        @include('admin.sidebar-admin')

        <main class="p-3 p-md-4">
            {{-- Alerts --}}
            @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
            @if($errors->any())
            <div class="alert alert-danger">
                <div class="fw-semibold mb-1">Có lỗi xảy ra:</div>
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            {{-- Top search --}}
            <div class="top mb-3">
                <form class="top-search" action="{{ route('admin.coupons.index') }}" method="GET" role="search">
                    <input type="text" name="q" class="top-search-input" placeholder="Tìm theo mã / ghi chú..." value="{{ $q ?? '' }}" autocomplete="off">
                    <button class="top-search-btn ms-2" aria-label="Tìm kiếm">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7"></circle>
                            <line x1="16.65" y1="16.65" x2="21" y2="21"></line>
                        </svg>
                    </button>
                </form>
            </div>

            {{-- Header actions --}}
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h4 class="mb-0">Mã giảm giá</h4>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#couponCreateModal">
                    <i class="bi bi-plus-lg me-1"></i> Thêm mã
                </button>
            </div>

            {{-- List --}}
            <div class="card card-surface">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Mã</th>
                                <th class="text-center">Loại</th>
                                <th class="text-end">Giá trị</th>
                                <th class="text-end">Tối đa</th>
                                <th class="text-end">Tối thiểu ĐH</th>
                                <th>Phạm vi</th>
                                <th>Hạng áp dụng</th>
                                <th class="text-center">Trạng thái</th>
                                <th>Hiệu lực</th>
                                <th class="sticky-actions">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($coupons ?? [] as $c)
                            @php
                            $levelBadges = [];
                            $levels = $c->eligible_levels ?? null; // null = mọi hạng
                            if (is_array($levels) && count($levels)) {
                            $map = [
                            'dong' => ['Đồng','text-bg-secondary'],
                            'bac' => ['Bạc','text-bg-light text-dark border'],
                            'vang' => ['Vàng','text-bg-warning text-dark'],
                            'kim_cuong' => ['Kim cương','text-bg-primary'],
                            ];
                            foreach ($levels as $lv) {
                            $m = $map[$lv] ?? [$lv,'text-bg-secondary'];
                            $levelBadges[] = '<span class="badge level-badge '.$m[1].'">'.$m[0].'</span>';
                            }
                            }
                            @endphp
                            <tr>
                                <td class="fw-semibold">{{ $c->code }}</td>
                                <td class="text-center">
                                    @switch($c->type)
                                    @case('percent') <span class="badge text-bg-primary">%</span> @break
                                    @case('fixed') <span class="badge text-bg-info">₫</span> @break
                                    @case('free_shipping') <span class="badge text-bg-success">Free ship</span> @break
                                    @endswitch
                                </td>
                                <td class="text-end">{{ $c->value ? number_format($c->value) : '—' }}</td>
                                <td class="text-end">{{ $c->max_discount ? number_format($c->max_discount) : '—' }}</td>
                                <td class="text-end">{{ number_format($c->min_subtotal ?? 0) }}</td>
                                <td><span class="badge bg-secondary-subtle text-secondary-emphasis">{{ $c->apply_scope }}</span></td>
                                <td>
                                    @if(is_array($levels) && count($levels))
                                    {!! implode(' ', $levelBadges) !!}
                                    @else
                                    <span class="text-muted small">Mọi hạng</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $c->status==='active'?'text-bg-success':'text-bg-secondary' }}">
                                        {{ $c->status==='active'?'Đang bật':'Tắt' }}
                                    </span>
                                </td>
                                <td class="small text-muted">
                                    @php
                                    $start = $c->starts_at ? \Carbon\Carbon::parse($c->starts_at)->format('d/m/Y H:i') : '—';
                                    $end = $c->ends_at ? \Carbon\Carbon::parse($c->ends_at)->format('d/m/Y H:i') : '—';
                                    @endphp
                                    {{ $start }} &rarr; {{ $end }}
                                </td>
                                <td class="sticky-actions">
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#couponEditModal"
                                            data-id="{{ $c->id }}"
                                            data-code="{{ $c->code }}"
                                            data-type="{{ $c->type }}"
                                            data-value="{{ $c->value }}"
                                            data-max_discount="{{ $c->max_discount }}"
                                            data-min_subtotal="{{ $c->min_subtotal }}"
                                            data-apply_scope="{{ $c->apply_scope }}"
                                            data-status="{{ $c->status }}"
                                            data-starts_at="{{ $c->starts_at }}"
                                            data-ends_at="{{ $c->ends_at }}"
                                            data-note="{{ $c->note }}"
                                            data-product_ids="{{ isset($c->products) ? $c->products->pluck('id')->implode(',') : '' }}"
                                            data-category_ids="{{ isset($c->categories) ? $c->categories->pluck('id')->implode(',') : '' }}"
                                            data-brand_ids="{{ isset($c->brands) ? $c->brands->pluck('id')->implode(',') : '' }}"
                                            data-eligible_levels='@json($c->eligible_levels)'>
                                            <i class="bi bi-pencil-square me-1"></i>Sửa
                                        </button>

                                        <form action="{{ route('admin.coupons.destroy',$c) }}" method="POST"
                                            onsubmit="return confirm('Xoá mã {{ $c->code }}?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash3 me-1"></i>Xoá
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">Chưa có mã nào.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-3">{{ $coupons?->withQueryString()->links() }}</div>
            </div>
        </main>
    </div>

    {{-- ========== Modal: CREATE ========== --}}
    <div class="modal fade" id="couponCreateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <form class="modal-content" method="POST" action="{{ route('admin.coupons.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Thêm mã giảm giá</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Mã <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control" required placeholder="VD: WELCOME50K" value="{{ old('code') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Loại <span class="text-danger">*</span></label>
                            <select name="type" class="form-select" id="createType" required>
                                <option value="percent">Phần trăm (%)</option>
                                <option value="fixed">Số tiền (₫)</option>
                                <option value="free_shipping">Miễn phí vận chuyển</option>
                            </select>
                            <div class="form-hint">Free ship sẽ bỏ qua “Giá trị”.</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Giá trị</label>
                            <input type="number" name="value" class="form-control" placeholder="10 hoặc 50000" value="{{ old('value') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Trần giảm tối đa</label>
                            <input type="number" name="max_discount" class="form-control" placeholder="Chỉ áp dụng khi %" value="{{ old('max_discount') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Giá trị tối thiểu đơn</label>
                            <input type="number" name="min_subtotal" class="form-control" value="{{ old('min_subtotal',0) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Trạng thái</label>
                            <select name="status" class="form-select">
                                <option value="active" selected>Bật</option>
                                <option value="inactive">Tắt</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Phạm vi áp dụng</label>
                            <select name="apply_scope" class="form-select" id="createScope">
                                <option value="all">Toàn shop</option>
                                <option value="cart">Giỏ hàng</option>
                                <option value="product">Theo sản phẩm</option>
                                <option value="category">Theo danh mục</option>
                                <option value="brand">Theo thương hiệu</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Bắt đầu</label>
                            <input type="datetime-local" name="starts_at" class="form-control" value="{{ old('starts_at') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kết thúc</label>
                            <input type="datetime-local" name="ends_at" class="form-control" value="{{ old('ends_at') }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Ghi chú</label>
                            <input type="text" name="note" class="form-control" placeholder="VD: Chào khách mới" value="{{ old('note') }}">
                        </div>

                        {{-- ===== HẠNG THÀNH VIÊN ÁP DỤNG (CREATE) ===== --}}
                        <div class="col-12">
                            <label class="form-label d-flex align-items-center justify-content-between">
                                <span>Hạng thành viên áp dụng</span>
                                <span class="form-hint">Để trống = áp dụng mọi hạng</span>
                            </label>
                            <div class="row g-2">
                                <div class="col-6 col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="lvCreateDong" name="eligible_levels[]" value="dong" @checked(is_array(old('eligible_levels')) && in_array('dong', old('eligible_levels')))>
                                        <label class="form-check-label" for="lvCreateDong">Đồng</label>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="lvCreateBac" name="eligible_levels[]" value="bac" @checked(is_array(old('eligible_levels')) && in_array('bac', old('eligible_levels')))>
                                        <label class="form-check-label" for="lvCreateBac">Bạc</label>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="lvCreateVang" name="eligible_levels[]" value="vang" @checked(is_array(old('eligible_levels')) && in_array('vang', old('eligible_levels')))>
                                        <label class="form-check-label" for="lvCreateVang">Vàng</label>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="lvCreateKC" name="eligible_levels[]" value="kim_cuong" @checked(is_array(old('eligible_levels')) && in_array('kim_cuong', old('eligible_levels')))>
                                        <label class="form-check-label" for="lvCreateKC">Kim cương</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- ===== /HẠNG THÀNH VIÊN ÁP DỤNG (CREATE) ===== --}}

                        {{-- Chi tiết phạm vi --}}
                        @if(!empty($products) || !empty($categories) || !empty($brands))
                        <div class="col-12" id="createScopeDetails" style="display:none">
                            <div class="row g-3">
                                {{-- SẢN PHẨM --}}
                                <div class="col-md-4" id="box-products" style="display:none">
                                    <label class="form-label">Chọn sản phẩm</label>
                                    <div class="list-filter input-group input-group-sm mb-2">
                                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                                        <input id="filterCreateProducts" class="form-control" placeholder="Tìm theo tên sản phẩm...">
                                    </div>
                                    <select id="createProducts" name="product_ids[]" class="form-select" multiple size="10">
                                        @foreach(($products ?? []) as $p)
                                        <option value="{{ $p->id }}">
                                            {{ $p->ten_san_pham ?? $p->name ?? $p->sku ?? ('SP #'.$p->id) }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <div class="form-hint mt-1"><span id="countCreateProducts">Đã chọn: 0</span></div>
                                </div>

                                {{-- DANH MỤC --}}
                                <div class="col-md-4" id="box-categories" style="display:none">
                                    <label class="form-label">Chọn danh mục</label>
                                    <div class="list-filter input-group input-group-sm mb-2">
                                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                                        <input id="filterCreateCategories" class="form-control" placeholder="Tìm theo tên danh mục...">
                                    </div>
                                    <select id="createCategories" name="category_ids[]" class="form-select" multiple size="10">
                                        @foreach(($categories ?? []) as $cat)
                                        <option value="{{ $cat->id }}">
                                            {{ $cat->ten_danh_muc ?? $cat->name ?? ('DM #'.$cat->id) }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <div class="form-hint mt-1"><span id="countCreateCategories">Đã chọn: 0</span></div>
                                </div>

                                {{-- THƯƠNG HIỆU --}}
                                <div class="col-md-4" id="box-brands" style="display:none">
                                    <label class="form-label">Chọn thương hiệu</label>
                                    <div class="list-filter input-group input-group-sm mb-2">
                                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                                        <input id="filterCreateBrands" class="form-control" placeholder="Tìm theo tên thương hiệu...">
                                    </div>
                                    <select id="createBrands" name="brand_ids[]" class="form-select" multiple size="10">
                                        @foreach(($brands ?? []) as $b)
                                        <option value="{{ $b->id }}">
                                            {{ $b->ten_thuong_hieu ?? $b->name ?? ('TH #'.$b->id) }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <div class="form-hint mt-1"><span id="countCreateBrands">Đã chọn: 0</span></div>
                                </div>
                            </div>
                            <div class="form-hint mt-1">Chỉ hiển thị một danh sách tương ứng với “Phạm vi”.</div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Huỷ</button>
                    <button class="btn btn-success" type="submit">Lưu</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ========== Modal: EDIT ========== --}}
    <div class="modal fade" id="couponEditModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <form class="modal-content" method="POST" id="editForm">
                @csrf @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Sửa mã giảm giá</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Mã <span class="text-danger">*</span></label>
                            <input type="text" name="code" id="editCode" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Loại</label>
                            <select name="type" id="editType" class="form-select">
                                <option value="percent">Phần trăm (%)</option>
                                <option value="fixed">Số tiền (₫)</option>
                                <option value="free_shipping">Miễn phí vận chuyển</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Giá trị</label>
                            <input type="number" name="value" id="editValue" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Trần giảm tối đa</label>
                            <input type="number" name="max_discount" id="editMax" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tối thiểu đơn</label>
                            <input type="number" name="min_subtotal" id="editMinSubtotal" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Trạng thái</label>
                            <select name="status" id="editStatus" class="form-select">
                                <option value="active">Bật</option>
                                <option value="inactive">Tắt</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Phạm vi</label>
                            <select name="apply_scope" id="editScope" class="form-select">
                                <option value="all">Toàn shop</option>
                                <option value="cart">Giỏ hàng</option>
                                <option value="product">Theo sản phẩm</option>
                                <option value="category">Theo danh mục</option>
                                <option value="brand">Theo thương hiệu</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Bắt đầu</label>
                            <input type="datetime-local" name="starts_at" id="editStart" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kết thúc</label>
                            <input type="datetime-local" name="ends_at" id="editEnd" class="form-control">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Ghi chú</label>
                            <input type="text" name="note" id="editNote" class="form-control">
                        </div>

                        {{-- ===== HẠNG THÀNH VIÊN ÁP DỤNG (EDIT) ===== --}}
                        <div class="col-12">
                            <label class="form-label d-flex align-items-center justify-content-between">
                                <span>Hạng thành viên áp dụng</span>
                                <span class="form-hint">Để trống = áp dụng mọi hạng</span>
                            </label>
                            <div class="row g-2">
                                <div class="col-6 col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="lvEditDong" name="eligible_levels[]" value="dong">
                                        <label class="form-check-label" for="lvEditDong">Đồng</label>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="lvEditBac" name="eligible_levels[]" value="bac">
                                        <label class="form-check-label" for="lvEditBac">Bạc</label>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="lvEditVang" name="eligible_levels[]" value="vang">
                                        <label class="form-check-label" for="lvEditVang">Vàng</label>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="lvEditKC" name="eligible_levels[]" value="kim_cuong">
                                        <label class="form-check-label" for="lvEditKC">Kim cương</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- ===== /HẠNG THÀNH VIÊN ÁP DỤNG (EDIT) ===== --}}

                        {{-- Chi tiết phạm vi khi EDIT --}}
                        @if(!empty($products) || !empty($categories) || !empty($brands))
                        <div class="col-12" id="editScopeDetails" style="display:none">
                            <div class="row g-3">
                                {{-- SẢN PHẨM --}}
                                <div class="col-md-" id="edit-box-products" style="display:none">
                                    <label class="form-label">Chọn sản phẩm</label>
                                    <div class="list-filter input-group input-group-sm mb-2">
                                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                                        <input id="filterEditProducts" class="form-control" placeholder="Tìm theo tên sản phẩm...">
                                    </div>
                                    <select name="product_ids[]" id="editProducts" class="form-select" multiple size="10">
                                        @foreach(($products ?? []) as $p)
                                        <option value="{{ $p->id }}">
                                            {{ $p->ten_san_pham ?? $p->name ?? $p->sku ?? ('SP #'.$p->id) }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <div class="form-hint mt-1"><span id="countEditProducts">Đã chọn: 0</span></div>
                                </div>

                                {{-- DANH MỤC --}}
                                <div class="col-md-4" id="edit-box-categories" style="display:none">
                                    <label class="form-label">Chọn danh mục</label>
                                    <div class="list-filter input-group input-group-sm mb-2">
                                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                                        <input id="filterEditCategories" class="form-control" placeholder="Tìm theo tên danh mục...">
                                    </div>
                                    <select name="category_ids[]" id="editCategories" class="form-select" multiple size="10">
                                        @foreach(($categories ?? []) as $cat)
                                        <option value="{{ $cat->id }}">
                                            {{ $cat->ten_danh_muc ?? $cat->name ?? ('DM #'.$cat->id) }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <div class="form-hint mt-1"><span id="countEditCategories">Đã chọn: 0</span></div>
                                </div>

                                {{-- THƯƠNG HIỆU --}}
                                <div class="col-md-4" id="edit-box-brands" style="display:none">
                                    <label class="form-label">Chọn thương hiệu</label>
                                    <div class="list-filter input-group input-group-sm mb-2">
                                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                                        <input id="filterEditBrands" class="form-control" placeholder="Tìm theo tên thương hiệu...">
                                    </div>
                                    <select name="brand_ids[]" id="editBrands" class="form-select" multiple size="10">
                                        @foreach(($brands ?? []) as $b)
                                        <option value="{{ $b->id }}">
                                            {{ $b->ten_thuong_hieu ?? $b->name ?? ('TH #'.$b->id) }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <div class="form-hint mt-1"><span id="countEditBrands">Đã chọn: 0</span></div>
                                </div>
                            </div>
                            <div class="form-hint mt-1">Chỉ hiển thị một danh sách tương ứng với “Phạm vi”.</div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Đóng</button>
                    <button class="btn btn-primary" type="submit">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ====== Common helpers ======
        function toggleBlock(show, el) {
            if (el) el.style.display = show ? '' : 'none';
        }

        function countSelected(select) {
            return Array.from(select?.options || []).filter(o => o.selected).length;
        }

        function updateCount(labelEl, select) {
            if (labelEl && select) labelEl.textContent = 'Đã chọn: ' + countSelected(select);
        }

        function installFilter(inputEl, selectEl) {
            if (!inputEl || !selectEl) return;
            inputEl.value = '';
            inputEl.addEventListener('input', () => {
                const q = inputEl.value.trim().toLowerCase();
                Array.from(selectEl.options).forEach(o => {
                    const txt = (o.textContent || '').toLowerCase();
                    o.hidden = (q && !txt.includes(q));
                });
            });
            selectEl.addEventListener('change', () =>
                updateCount(document.getElementById(inputEl.getAttribute('data-count-target')), selectEl));
        }

        // ===== CREATE: Scope details =====
        const scopeSel = document.getElementById('createScope');
        const detailsWrap = document.getElementById('createScopeDetails');
        const boxProducts = document.getElementById('box-products');
        const boxCategories = document.getElementById('box-categories');
        const boxBrands = document.getElementById('box-brands');

        function toggleScopeDetails() {
            if (!scopeSel || !detailsWrap) return;
            const v = scopeSel.value;
            const show = (v === 'product' || v === 'category' || v === 'brand');
            toggleBlock(show, detailsWrap);
            toggleBlock(v === 'product', boxProducts);
            toggleBlock(v === 'category', boxCategories);
            toggleBlock(v === 'brand', boxBrands);
        }
        scopeSel?.addEventListener('change', toggleScopeDetails);
        toggleScopeDetails();

        // FREE SHIPPING => disable value
        const createTypeSel = document.getElementById('createType');
        const createValueInput = document.querySelector('#couponCreateModal input[name="value"]');

        function syncCreateValue() {
            if (!createTypeSel || !createValueInput) return;
            const isFree = createTypeSel.value === 'free_shipping';
            createValueInput.disabled = isFree;
            if (isFree) createValueInput.value = '';
        }
        createTypeSel?.addEventListener('change', syncCreateValue);
        syncCreateValue();

        // Filters + counters (CREATE)
        const createProductsSel = document.getElementById('createProducts');
        const createCategoriesSel = document.getElementById('createCategories');
        const createBrandsSel = document.getElementById('createBrands');
        const filterCreateProducts = document.getElementById('filterCreateProducts');
        const filterCreateCategories = document.getElementById('filterCreateCategories');
        const filterCreateBrands = document.getElementById('filterCreateBrands');
        if (filterCreateProducts) filterCreateProducts.setAttribute('data-count-target', 'countCreateProducts');
        if (filterCreateCategories) filterCreateCategories.setAttribute('data-count-target', 'countCreateCategories');
        if (filterCreateBrands) filterCreateBrands.setAttribute('data-count-target', 'countCreateBrands');
        installFilter(filterCreateProducts, createProductsSel);
        installFilter(filterCreateCategories, createCategoriesSel);
        installFilter(filterCreateBrands, createBrandsSel);
        updateCount(document.getElementById('countCreateProducts'), createProductsSel);
        updateCount(document.getElementById('countCreateCategories'), createCategoriesSel);
        updateCount(document.getElementById('countCreateBrands'), createBrandsSel);

        // ===== EDIT: Prefill, scope details, filters, eligible_levels =====
        const editModal = document.getElementById('couponEditModal');
        editModal?.addEventListener('show.bs.modal', function(event) {
            const btn = event.relatedTarget;
            const id = btn.getAttribute('data-id');

            const setVal = (idSel, val) => {
                const el = document.getElementById(idSel);
                if (el) el.value = (val ?? '');
            };
            setVal('editCode', btn.getAttribute('data-code'));
            setVal('editType', btn.getAttribute('data-type'));
            setVal('editValue', btn.getAttribute('data-value'));
            setVal('editMax', btn.getAttribute('data-max_discount'));
            setVal('editMinSubtotal', btn.getAttribute('data-min_subtotal'));
            setVal('editScope', btn.getAttribute('data-apply_scope'));
            setVal('editStatus', btn.getAttribute('data-status'));

            const toLocal = (dt) => {
                if (!dt || dt === 'null') return '';
                const d = new Date(dt);
                const pad = n => n.toString().padStart(2, '0');
                return `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
            };
            setVal('editStart', toLocal(btn.getAttribute('data-starts_at')));
            setVal('editEnd', toLocal(btn.getAttribute('data-ends_at')));
            setVal('editNote', btn.getAttribute('data-note'));
            document.getElementById('editForm').action = "{{ url('/admin/coupons') }}/" + id;

            // Disable value if free_shipping
            const typeSel = document.getElementById('editType');
            const valInput = document.getElementById('editValue');
            const syncEditValue = () => {
                const off = (typeSel.value === 'free_shipping');
                valInput.disabled = off;
                if (off) valInput.value = '';
            };
            typeSel?.addEventListener('change', syncEditValue);
            syncEditValue();

            // Show proper details by scope
            const editScopeSel = document.getElementById('editScope');
            const editDetails = document.getElementById('editScopeDetails');
            const boxP = document.getElementById('edit-box-products');
            const boxC = document.getElementById('edit-box-categories');
            const boxB = document.getElementById('edit-box-brands');

            function toggleEditDetails() {
                const v = editScopeSel.value;
                const show = (v === 'product' || v === 'category' || v === 'brand');
                toggleBlock(show, editDetails);
                toggleBlock(v === 'product', boxP);
                toggleBlock(v === 'category', boxC);
                toggleBlock(v === 'brand', boxB);
            }
            editScopeSel?.addEventListener('change', toggleEditDetails);
            toggleEditDetails();

            // Pre-select IDs đã lưu
            const prodIds = (btn.getAttribute('data-product_ids') || '').split(',').filter(Boolean);
            const catIds = (btn.getAttribute('data-category_ids') || '').split(',').filter(Boolean);
            const brIds = (btn.getAttribute('data-brand_ids') || '').split(',').filter(Boolean);
            const editProducts = document.getElementById('editProducts');
            const editCategories = document.getElementById('editCategories');
            const editBrands = document.getElementById('editBrands');

            function setMultiSelected(selectEl, ids) {
                if (!selectEl) return;
                Array.from(selectEl.options).forEach(o => {
                    o.selected = ids.includes(String(o.value));
                });
            }
            setMultiSelected(editProducts, prodIds);
            setMultiSelected(editCategories, catIds);
            setMultiSelected(editBrands, brIds);

            // Filters + counters (EDIT)
            const fP = document.getElementById('filterEditProducts');
            const fC = document.getElementById('filterEditCategories');
            const fB = document.getElementById('filterEditBrands');
            if (fP) {
                fP.value = '';
                fP.setAttribute('data-count-target', 'countEditProducts');
            }
            if (fC) {
                fC.value = '';
                fC.setAttribute('data-count-target', 'countEditCategories');
            }
            if (fB) {
                fB.value = '';
                fB.setAttribute('data-count-target', 'countEditBrands');
            }
            installFilter(fP, editProducts);
            installFilter(fC, editCategories);
            installFilter(fB, editBrands);
            updateCount(document.getElementById('countEditProducts'), editProducts);
            updateCount(document.getElementById('countEditCategories'), editCategories);
            updateCount(document.getElementById('countEditBrands'), editBrands);

            // ===== Prefill eligible_levels (EDIT) =====
            function setChecked(id, on) {
                const el = document.getElementById(id);
                if (el) el.checked = !!on;
            }
            // clear all first
            setChecked('lvEditDong', false);
            setChecked('lvEditBac', false);
            setChecked('lvEditVang', false);
            setChecked('lvEditKC', false);
            // parse incoming json
            let levels = [];
            try {
                levels = JSON.parse(btn.getAttribute('data-eligible_levels'));
            } catch (e) {}
            if (Array.isArray(levels)) {
                setChecked('lvEditDong', levels.includes('dong'));
                setChecked('lvEditBac', levels.includes('bac'));
                setChecked('lvEditVang', levels.includes('vang'));
                setChecked('lvEditKC', levels.includes('kim_cuong'));
            }
        });
    </script>
</body>

</html>