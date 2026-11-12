<?php
declare(strict_types=1);

namespace PustakaKu\Exceptions;

/** Kesalahan bisnis pada proses penyewaan (saldo kurang, stok habis, dll). */
final class RentalException extends \RuntimeException
{
}
