<?php
declare(strict_types=1);

namespace PustakaKu\Repositories;

/** Basis penyimpanan berkas JSON (alternatif database, sesuai catatan tugas). */
abstract class JsonRepository
{
    public function __construct(protected string $file)
    {
    }

    protected function load(): array
    {
        if (!is_file($this->file)) {
            return [];
        }
        return json_decode((string) file_get_contents($this->file), true, 512, JSON_THROW_ON_ERROR) ?? [];
    }

    protected function save(array $rows): void
    {
        file_put_contents(
            $this->file,
            json_encode(array_values($rows), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            LOCK_EX
        );
    }

    /** Ganti baris ber-id sama, atau tambahkan jika belum ada. */
    protected function upsert(\JsonSerializable $obj, string $id): void
    {
        $rows = $this->load();
        $found = false;
        foreach ($rows as $i => $row) {
            if ($row['id'] === $id) {
                $rows[$i] = $obj->jsonSerialize();
                $found = true;
            }
        }
        if (!$found) {
            $rows[] = $obj->jsonSerialize();
        }
        $this->save($rows);
    }
}
