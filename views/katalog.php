<main class="wrap">
    <span class="tag">Katalog Media</span>
    <div class="head">
        <h1>Pinjam ilmu pemrograman web, tanpa antre.</h1>
        <form class="search" method="get" action="index.php" role="search">
            <input type="hidden" name="page" value="katalog">
            <?php if ($kategori !== ''): ?><input type="hidden" name="kategori" value="<?= e($kategori) ?>"><?php endif; ?>
            <label class="sr" for="q">Cari judul atau penulis</label>
            <input id="q" type="search" name="q" value="<?= e($q) ?>" placeholder="Cari judul atau penulis">
            <button class="btn" type="submit">Cari</button>
        </form>
    </div>
    <nav class="chips" aria-label="Filter kategori">
        <?php foreach (['' => 'Semua'] + array_combine($kategoriList, $kategoriList) as $k => $label): ?>
            <a class="chip<?= (string) $k === $kategori ? ' on' : '' ?>" href="index.php?page=katalog&amp;kategori=<?= urlencode((string) $k) ?>&amp;q=<?= urlencode($q) ?>"><?= e($label) ?></a>
        <?php endforeach; ?>
    </nav>

    <section class="grid" id="grid" aria-label="Daftar e-book">
        <?php foreach ($items as $b): $ada = $b->tersedia(); ?>
        <article class="card bk book" data-cari="<?= e(strtolower($b->getJudul() . ' ' . $b->getPenulis())) ?>">
            <div class="cover" style="background:<?= e($b->getWarna()) ?>;color:<?= e($b->getTeks()) ?>"><h3><?= e($b->getJudul()) ?></h3></div>
            <div class="body">
                <span class="<?= $ada ? 'ok' : 'no' ?>"><?= $ada ? 'Tersedia' : 'Disewa' ?></span>
                <p class="jd"><?= e($b->getJudul()) ?></p>
                <p class="mut"><?= e($b->getPenulis()) ?> · <?= e($b->getKategori()) ?><?= $b->tipe() === 'premium' ? ' · Premium' : '' ?></p>
                <div class="buy">
                    <strong><?= e(rupiah($b->hitungHarga(7))) ?><span class="mut"> / 7 hari</span></strong>
                    <button type="button" class="btn sewa" <?= $ada ? '' : 'disabled' ?>
                        data-id="<?= e($b->getId()) ?>" data-judul="<?= e($b->getJudul()) ?>" data-penulis="<?= e($b->getPenulis()) ?>"
                        data-p3="<?= $b->hitungHarga(3) ?>" data-p7="<?= $b->hitungHarga(7) ?>" data-p14="<?= $b->hitungHarga(14) ?>"><?= $ada ? 'Sewa' : 'Disewa' ?></button>
                </div>
            </div>
        </article>
        <?php endforeach; ?>
    </section>
    <p id="kosong" class="mut empty" <?= $items ? 'hidden' : '' ?>>Tidak ada e-book yang cocok dengan pencarianmu.</p>
</main>

<dialog id="modal-sewa" data-saldo="<?= $member->getSaldo() ?>" aria-labelledby="m-judul">
    <form method="post" action="index.php?page=sewa" class="modal">
        <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
        <input type="hidden" name="book_id" id="m-id">
        <div class="mh"><span class="tag">Konfirmasi Sewa</span><button type="button" class="btn o sq" data-close aria-label="Tutup">✕</button></div>
        <h2 id="m-judul"></h2>
        <p class="mut" id="m-penulis"></p>
        <fieldset>
            <legend>Durasi sewa</legend>
            <label class="chip"><input type="radio" name="hari" value="3"> 3 hari</label>
            <label class="chip"><input type="radio" name="hari" value="7" checked> 7 hari</label>
            <label class="chip"><input type="radio" name="hari" value="14"> 14 hari</label>
        </fieldset>
        <dl class="sum">
            <div><dt>Harga sewa</dt><dd id="m-harga"></dd></div>
            <div><dt>Jatuh tempo</dt><dd id="m-tempo"></dd></div>
            <div class="tot"><dt>Total</dt><dd id="m-total"></dd></div>
            <p class="mut" id="m-saldo"></p>
        </dl>
        <div class="acts end"><button type="button" class="btn o" data-close>Batal</button><button type="submit" class="btn" id="m-ok">Konfirmasi &amp; Sewa</button></div>
    </form>
</dialog>
