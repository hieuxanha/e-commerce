/* public/admin/thongke.js */
(function () {
  'use strict';

  // Đọc JSON thuần
  const el = document.getElementById('tk-data');
  if (!el) { console.error('[thongke.js] #tk-data not found'); return; }

  let payload = {};
  try { payload = JSON.parse((el.textContent || '{}').trim()); }
  catch (e) { console.error('[thongke.js] Bad JSON:', el.textContent); return; }

  const toNum = (v) => Number(v ?? 0);

  const lineLabels = Array.isArray(payload.lineLabels) ? payload.lineLabels : [];
  const lineData   = (payload.lineData  || []).map(toNum);
  const barLabels  = Array.isArray(payload.barLabels) ? payload.barLabels : [];
  const barData    = (payload.barData   || []).map(toNum);
  const pieLabels  = Array.isArray(payload.pieLabels) ? payload.pieLabels : [];
  const pieData    = (payload.pieData   || []).map(toNum);

  // Plugin "Không có dữ liệu"
  const noDataPlugin = {
    id: 'noData',
    afterDraw(chart, _, opts) {
      const ds = chart.data?.datasets?.[0]?.data || [];
      const hasData = ds.some(v => Number(v) !== 0);
      if (hasData) return;
      const { ctx, chartArea } = chart;
      if (!chartArea) return;
      ctx.save();
      ctx.font = '14px system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial';
      ctx.fillStyle = '#6c757d';
      ctx.textAlign = 'center';
      ctx.textBaseline = 'middle';
      ctx.fillText(opts?.text || 'Không có dữ liệu',
        (chartArea.left + chartArea.right) / 2,
        (chartArea.top + chartArea.bottom) / 2
      );
      ctx.restore();
    }
  };

  // === Palette KHÔNG có đỏ ===
  // Dùng các tông xanh dương/xanh lá/tím/vàng nhạt…
  function genPieColors(n) {
    const palette = [
      'hsl(205 70% 55% / .85)', // blue
      'hsl(160 70% 45% / .85)', // green
      'hsl(190 70% 55% / .85)', // teal
      'hsl(45 80% 60% / .85)',  // amber
      'hsl(280 60% 60% / .85)', // purple
      'hsl(100 65% 45% / .85)', // lime
      'hsl(210 50% 60% / .85)', // steel blue
      'hsl(230 55% 60% / .85)', // indigo
      'hsl(90  55% 50% / .85)', // greenish
      'hsl(260 55% 62% / .85)', // violet
    ];
    return Array.from({ length: n }, (_, i) => palette[i % palette.length]);
  }

  // Vẽ biểu đồ
  let lineChart, barChart, pieChart;

  function drawLine() {
    const c = document.getElementById('lineChart');
    if (!c) return;
    lineChart = new Chart(c, {
      type: 'line',
      data: {
        labels: lineLabels,
        datasets: [{
          label: 'Doanh thu (đ)',
          data: lineData,
          tension: 0.3,
          fill: false
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { display: true }, noData: { text: 'Không có dữ liệu' } },
        scales: {
          y: {
            beginAtZero: true,
            ticks: { callback: v => new Intl.NumberFormat('vi-VN').format(v) }
          }
        }
      },
      plugins: [noDataPlugin]
    });
  }

  function drawBar() {
    const c = document.getElementById('barChart');
    if (!c) return;
    barChart = new Chart(c, {
      type: 'bar',
      data: {
        labels: barLabels,
        datasets: [{ label: 'Doanh thu (đ)', data: barData }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: true },
          tooltip: { callbacks: { label: (ctx) => ` ${new Intl.NumberFormat('vi-VN').format(toNum(ctx.raw))} đ` } },
          noData: { text: 'Không có dữ liệu' }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: { callback: v => new Intl.NumberFormat('vi-VN').format(v) }
          }
        }
      },
      plugins: [noDataPlugin]
    });
  }

  function drawPie() {
    const c = document.getElementById('pieChart');
    if (!c) return;
    pieChart = new Chart(c, {
      type: 'pie',
      data: {
        labels: pieLabels,
        datasets: [{
          data: pieData,
          backgroundColor: genPieColors(pieLabels.length), // palette đã bỏ đỏ
          borderColor: '#fff',
          borderWidth: 2
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { position: 'bottom' },
          tooltip: {
            callbacks: {
              label: (ctx) => {
                const arr = (ctx.dataset.data || []).map(toNum);
                const total = arr.reduce((a, b) => a + b, 0) || 1;
                const val = toNum(ctx.raw);
                const pct = Math.round(val * 100 / total);
                return ` ${ctx.label}: ${new Intl.NumberFormat('vi-VN').format(val)} đ (${pct}%)`;
              }
            }
          },
          noData: { text: 'Không có dữ liệu' }
        }
      },
      plugins: [noDataPlugin]
    });
  }

  function init() {
    drawLine();
    drawBar();
    drawPie();

    // Công tắc hiển thị/ẩn từng biểu đồ
    const byId = (id) => document.getElementById(id);
    const bindToggle = (chkId, wrapId) => {
      const chk = byId(chkId), wrap = byId(wrapId);
      if (chk && wrap) chk.addEventListener('change', () => (wrap.hidden = !chk.checked));
    };
    bindToggle('tg-line', 'wrap-line');
    bindToggle('tg-bar',  'wrap-bar');
    bindToggle('tg-pie',  'wrap-pie');
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
