<?php
declare(strict_types=1);

namespace PustakaKu\Models;

/** E-book premium (edisi lengkap): ada biaya layanan 20%. */
final class EBookPremium extends EBook
{
    private const BIAYA_LAYANAN = 1.2;

    public function tipe(): string { return 'premium'; }

    public function hitungHarga(int $hari): int
    {
        return self::bulatkan($this->getHargaPer7Hari() * $hari / 7 * self::BIAYA_LAYANAN);
    }
}
