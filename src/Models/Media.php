<?php
declare(strict_types=1);

namespace PustakaKu\Models;

use PustakaKu\Contracts\Rentable;

/** Kelas abstrak media: properti private (encapsulation), harga dihitung subclass (polymorphism). */
abstract class Media implements Rentable, \JsonSerializable
{
    public function __construct(
        private string $id,
        private string $judul,
        private string $penulis,
        private string $kategori,
        private int $hargaPer7Hari,
        private bool $tersedia = true,
        private string $warna = '#E9DFC8',
        private string $teks = '#17171A',
    ) {
    }

    public function getId(): string { return $this->id; }
    public function getJudul(): string { return $this->judul; }
    public function getPenulis(): string { return $this->penulis; }
    public function getKategori(): string { return $this->kategori; }
    public function getHargaPer7Hari(): int { return $this->hargaPer7Hari; }
    public function getWarna(): string { return $this->warna; }
    public function getTeks(): string { return $this->teks; }
    public function tersedia(): bool { return $this->tersedia; }

    public function setTersedia(bool $status): void
    {
        $this->tersedia = $status;
    }

    abstract public function tipe(): string;

    abstract public function hitungHarga(int $hari): int;

    /** Pembulatan ke atas per Rp 1.000. */
    protected static function bulatkan(float $nilai): int
    {
        return (int) (ceil($nilai / 1000) * 1000);
    }

    public static function dariArray(array $d): self
    {
        $kelas = match ($d['tipe'] ?? 'ebook') {
            'premium' => EBookPremium::class,
            default => EBook::class,
        };

        return new $kelas(
            $d['id'], $d['judul'], $d['penulis'], $d['kategori'],
            (int) $d['harga_per_7_hari'], (bool) $d['tersedia'],
            $d['warna'] ?? '#E9DFC8', $d['teks'] ?? '#17171A'
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id, 'judul' => $this->judul, 'penulis' => $this->penulis,
            'kategori' => $this->kategori, 'harga_per_7_hari' => $this->hargaPer7Hari,
            'tersedia' => $this->tersedia, 'warna' => $this->warna, 'teks' => $this->teks,
            'tipe' => $this->tipe(),
        ];
    }
}
