<?php
declare(strict_types=1);

namespace PustakaKu\Repositories;

use PustakaKu\Models\Member;

final class MemberRepository extends JsonRepository
{
    public function temukan(string $id): ?Member
    {
        foreach ($this->load() as $row) {
            if ($row['id'] === $id) {
                return Member::dariArray($row);
            }
        }
        return null;
    }

    public function temukanByEmail(string $email): ?Member
    {
        foreach ($this->load() as $row) {
            if (strcasecmp($row['email'], $email) === 0) {
                return Member::dariArray($row);
            }
        }
        return null;
    }

    public function simpan(Member $m): void
    {
        $this->upsert($m, $m->getId());
    }
}
