<main class="wrap">
    <span class="tag">Dashboard Member</span>
    <h1>Halo, <?= e(explode(' ', $member->getNama())[0]) ?>. <?= count($aktif) ?> e-book sedang kamu sewa.</h1>
    <p class="mut lead"><?= e($member->getEmail()) ?></p>

    <section class="stats" aria-label="Ringkasan akun">
        <div class="card pad hl"><p>Sedang disewa</p><strong><?= count($aktif) ?> buku</strong></div>
        <div class="card pad"><p>Total transaksi</p><strong><?= count($riwayat) ?></strong></div>
        <div class="card pad"><p>Saldo dompet</p><strong><?= e(rupiah($member->getSaldo())) ?></strong></div>
    </section>

    <h2>Sedang disewa</h2>
    <section class="rows">
        <?php if (!$aktif): ?><p class="mut">Belum ada e-book yang disewa. <a class="link" href="index.php?page=katalog">Jelajahi katalog</a></p><?php endif; ?>
        <?php foreach ($aktif as $r): $b = $peta[$r->getBookId()] ?? null; $sisa = $r->sisaHari($sekarang); ?>
        <article class="card pad row bk">
            <div>
                <span class="<?= $sisa > 1 ? 'ok' : 'no' ?>"><?= $sisa >= 0 ? "Sisa $sisa hari" : 'Terlambat ' . abs($sisa) . ' hari' ?></span>
                <h3><?= e($b?->getJudul() ?? 'E-book dihapus') ?></h3>
                <p class="mut"><?= e($b?->getPenulis() ?? '-') ?> · Jatuh tempo <?= e(tanggal($r->jatuhTempo())) ?></p>
            </div>
            <div class="acts">
                <form method="post" action="index.php?page=perpanjang"><input type="hidden" name="csrf" value="<?= e($csrf) ?>"><input type="hidden" name="rental_id" value="<?= e($r->getId()) ?>"><button class="btn">Perpanjang 7 hari</button></form>
                <form method="post" action="index.php?page=kembali"><input type="hidden" name="csrf" value="<?= e($csrf) ?>"><input type="hidden" name="rental_id" value="<?= e($r->getId()) ?>"><button class="btn o">Kembalikan</button></form>
            </div>
        </article>
        <?php endforeach; ?>
    </section>

    <h2>Riwayat transaksi</h2>
    <div class="card scroll">
        <table>
            <thead><tr><th>Tanggal</th><th>E-book</th><th>Durasi</th><th>Total</th><th>Status</th></tr></thead>
            <tbody>
            <?php foreach ($riwayat as $r): $b = $peta[$r->getBookId()] ?? null; ?>
                <tr><td><?= e(tanggal($r->getMulai())) ?></td><td><strong><?= e($b?->getJudul() ?? '-') ?></strong></td><td><?= $r->getHari() ?> hari</td><td><?= e(rupiah($r->getTotal())) ?></td><td><?= $r->aktif() ? 'Aktif' : 'Selesai' ?></td></tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>
