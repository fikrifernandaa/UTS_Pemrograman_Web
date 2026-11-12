<?php
declare(strict_types=1);

namespace PustakaKu\Models;

final class Rental implements \JsonSerializable
{
    public function __construct(
        private string $id,
        private string $memberId,
        private string $bookId,
        private \DateTimeImmutable $mulai,
        private int $hari,
        private int $total,
        private string $status = 'aktif',
    ) {
    }

    public function getId(): string { return $this->id; }
    public function getMemberId(): string { return $this->memberId; }
    public function getBookId(): string { return $this->bookId; }
    public function getMulai(): \DateTimeImmutable { return $this->mulai; }
    public function getHari(): int { return $this->hari; }
    public function getTotal(): int { return $this->total; }
    public function getStatus(): string { return $this->status; }
    public function aktif(): bool { return $this->status === 'aktif'; }

    public function jatuhTempo(): \DateTimeImmutable
    {
        return $this->mulai->modify("+{$this->hari} days");
    }

    /** Sisa hari (negatif = terlambat). */
    public function sisaHari(\DateTimeImmutable $sekarang): int
    {
        $a = $sekarang->setTime(0, 0);
        return (int) $a->diff($this->jatuhTempo()->setTime(0, 0))->format('%r%a');
    }

    public function perpanjang(int $hari, int $biaya): void
    {
        $this->hari += $hari;
        $this->total += $biaya;
    }

    public function selesaikan(): void
    {
        $this->status = 'selesai';
    }

    public static function dariArray(array $d): self
    {
        return new self($d['id'], $d['member_id'], $d['book_id'], new \DateTimeImmutable($d['mulai']), (int) $d['hari'], (int) $d['total'], $d['status']);
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id, 'member_id' => $this->memberId, 'book_id' => $this->bookId,
            'mulai' => $this->mulai->format('Y-m-d'), 'hari' => $this->hari,
            'total' => $this->total, 'status' => $this->status,
        ];
    }
}
