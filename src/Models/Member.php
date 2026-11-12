<?php
declare(strict_types=1);

namespace PustakaKu\Models;

use PustakaKu\Exceptions\RentalException;

final class Member extends User
{
    public function __construct(string $id, string $nama, string $email, string $passwordHash, private int $saldo = 0)
    {
        parent::__construct($id, $nama, $email, $passwordHash);
    }

    public function peran(): string { return 'member'; }

    public function getSaldo(): int { return $this->saldo; }

    public function kurangiSaldo(int $jumlah): void
    {
        if ($jumlah > $this->saldo) {
            throw new RentalException('Saldo dompet tidak cukup untuk transaksi ini.');
        }
        $this->saldo -= $jumlah;
    }

    public static function dariArray(array $d): self
    {
        return new self($d['id'], $d['nama'], $d['email'], $d['password_hash'], (int) $d['saldo']);
    }

    public function jsonSerialize(): array
    {
        return parent::jsonSerialize() + ['saldo' => $this->saldo];
    }
}
