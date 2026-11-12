<?php
declare(strict_types=1);

namespace PustakaKu\Models;

/** E-book reguler: harga proporsional terhadap lama sewa. */
class EBook extends Media
{
    public function tipe(): string { return 'ebook'; }

    public function hitungHarga(int $hari): int
    {
        return self::bulatkan($this->getHargaPer7Hari() * $hari / 7);
    }
}
