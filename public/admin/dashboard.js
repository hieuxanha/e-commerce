(function () {
  const el = document.getElementById('dashboard-data');
  if (!el) { console.error('#dashboard-data not found'); return; }

  const raw = el.textContent || '{}';
  console.log('RAW JSON:', raw);               // <- phải thấy chuỗi JSON
  let data = {};
  try { data = JSON.parse(raw); } catch(e) { console.error('Bad JSON', e); return; }
  console.log('PARSED:', data);                // <- phải thấy object có 4 mảng

  const { catLabels = [], catCounts = [], roleLabels = [], roleCounts = [] } = data;
  console.log({ catLabels, catCounts, roleLabels, roleCounts });

  const catEl = document.getElementById('chartByCategory');
  if (catEl && catLabels.length) {
    new Chart(catEl, {
      type: 'bar',
      data: { labels: catLabels, datasets: [{ label:'Số sản phẩm', data: catCounts,
        backgroundColor:'rgba(75,192,192,0.6)', borderColor:'rgba(75,192,192,1)', borderWidth:1 }] },
      options: { responsive:true, plugins:{ legend:{ display:false } }, scales:{ y:{ beginAtZero:true } } }
    });
  } else {
    console.warn('Bar chart skipped: no element or empty data');
  }

  const roleEl = document.getElementById('chartRoles');
  if (roleEl && roleLabels.length) {
    new Chart(roleEl, {
      type: 'doughnut',
      data: { labels: roleLabels, datasets: [{ data: roleCounts,
        backgroundColor:['rgba(255,99,132,0.6)','rgba(54,162,235,0.6)','rgba(255,206,86,0.6)'],
        borderColor:['rgba(255,99,132,1)','rgba(54,162,235,1)','rgba(255,206,86,1)'], borderWidth:1 }] },
      options: { responsive:true, plugins:{ legend:{ position:'bottom' } } }
    });
  } else {
    console.warn('Doughnut chart skipped: no element or empty data');
  }
})();
