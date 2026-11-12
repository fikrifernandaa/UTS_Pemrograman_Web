<?php
declare(strict_types=1);

namespace PustakaKu\Repositories;

use PustakaKu\Models\Media;

final class BookRepository extends JsonRepository
{
    /** @return Media[] */
    public function semua(): array
    {
        return array_map([Media::class, 'dariArray'], $this->load());
    }

    /** @return Media[] */
    public function cari(string $kata = '', string $kategori = ''): array
    {
        return array_values(array_filter($this->semua(), static function (Media $m) use ($kata, $kategori): bool {
            $cocokKata = $kata === '' || stripos($m->getJudul() . ' ' . $m->getPenulis(), $kata) !== false;
            return $cocokKata && ($kategori === '' || $m->getKategori() === $kategori);
        }));
    }

    public function temukan(string $id): ?Media
    {
        foreach ($this->semua() as $m) {
            if ($m->getId() === $id) {
                return $m;
            }
        }
        return null;
    }

    /** @return string[] */
    public function daftarKategori(): array
    {
        $k = array_unique(array_map(static fn (Media $m) => $m->getKategori(), $this->semua()));
        sort($k);
        return $k;
    }

    public function simpan(Media $m): void
    {
        $this->upsert($m, $m->getId());
    }
}
