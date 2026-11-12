<?php
declare(strict_types=1);

namespace PustakaKu\Repositories;

use PustakaKu\Models\Rental;

final class RentalRepository extends JsonRepository
{
    /** @return Rental[] terbaru lebih dulu */
    public function milik(string $memberId): array
    {
        $list = array_filter(array_map([Rental::class, 'dariArray'], $this->load()), static fn (Rental $r) => $r->getMemberId() === $memberId);
        usort($list, static fn (Rental $a, Rental $b) => $b->getMulai() <=> $a->getMulai());
        return $list;
    }

    public function temukan(string $id): ?Rental
    {
        foreach ($this->load() as $row) {
            if ($row['id'] === $id) {
                return Rental::dariArray($row);
            }
        }
        return null;
    }

    public function buatId(): string
    {
        return 'r' . bin2hex(random_bytes(4));
    }

    public function simpan(Rental $r): void
    {
        $this->upsert($r, $r->getId());
    }
}
