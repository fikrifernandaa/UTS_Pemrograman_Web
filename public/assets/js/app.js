'use strict';
document.addEventListener('DOMContentLoaded', () => {
  const rp = (n) => 'Rp ' + n.toLocaleString('id-ID');

  // 1. Tutup pesan flash
  document.querySelectorAll('.flash .x').forEach((b) => b.addEventListener('click', () => b.parentElement.remove()));

  // 2. Validasi form (client-side; server tetap memvalidasi ulang)
  document.querySelectorAll('form[data-validate]').forEach((form) => {
    form.addEventListener('submit', (ev) => {
      let valid = true;
      form.querySelectorAll('input:not([type=hidden])').forEach((input) => {
        let msg = '';
        if (input.validity.valueMissing) msg = 'Wajib diisi.';
        else if (input.validity.typeMismatch) msg = 'Format email tidak valid.';
        else if (input.minLength > 0 && input.value.length < input.minLength) msg = 'Minimal ' + input.minLength + ' karakter.';
        const out = input.parentElement.querySelector('.err');
        if (out) out.textContent = msg;
        input.setAttribute('aria-invalid', msg ? 'true' : 'false');
        if (msg) valid = false;
      });
      if (!valid) ev.preventDefault();
    });
  });

  // 3. Pencarian instan pada kartu katalog
  const q = document.getElementById('q');
  const cards = document.querySelectorAll('.book');
  const kosong = document.getElementById('kosong');
  if (q && cards.length) {
    q.addEventListener('input', () => {
      const kata = q.value.trim().toLowerCase();
      let tampil = 0;
      cards.forEach((c) => {
        const cocok = c.dataset.cari.includes(kata);
        c.hidden = !cocok;
        if (cocok) tampil++;
      });
      kosong.hidden = tampil > 0;
    });
  }

  // 4. Modal simulasi penyewaan
  const dlg = document.getElementById('modal-sewa');
  if (!dlg) return;
  const saldo = Number(dlg.dataset.saldo);
  let harga = {};
  const $ = (id) => document.getElementById(id);

  const update = () => {
    const hari = Number(dlg.querySelector('input[name=hari]:checked').value);
    const total = harga[hari];
    const tempo = new Date();
    tempo.setDate(tempo.getDate() + hari);
    $('m-harga').textContent = rp(total);
    $('m-total').textContent = rp(total);
    $('m-tempo').textContent = tempo.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
    const cukup = saldo >= total;
    $('m-saldo').textContent = cukup
      ? 'Saldo dompet: ' + rp(saldo) + ' → ' + rp(saldo - total)
      : 'Saldo ' + rp(saldo) + ' tidak cukup untuk sewa ini.';
    $('m-ok').disabled = !cukup;
  };

  document.querySelectorAll('.sewa').forEach((btn) => btn.addEventListener('click', () => {
    harga = { 3: +btn.dataset.p3, 7: +btn.dataset.p7, 14: +btn.dataset.p14 };
    $('m-id').value = btn.dataset.id;
    $('m-judul').textContent = btn.dataset.judul;
    $('m-penulis').textContent = btn.dataset.penulis;
    dlg.querySelector('input[value="7"]').checked = true;
    update();
    dlg.showModal();
  }));
  dlg.querySelectorAll('input[name=hari]').forEach((r) => r.addEventListener('change', update));
  dlg.querySelectorAll('[data-close]').forEach((b) => b.addEventListener('click', () => dlg.close()));
  dlg.addEventListener('click', (ev) => { if (ev.target === dlg) dlg.close(); });
});
