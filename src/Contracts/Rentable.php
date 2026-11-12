<?php
declare(strict_types=1);

namespace PustakaKu\Contracts;

/** Kontrak untuk setiap media yang dapat disewa. */
interface Rentable
{
    public function hitungHarga(int $hari): int;

    public function tersedia(): bool;
}
