<?php
declare(strict_types=1);

namespace PustakaKu\Models;

abstract class User implements \JsonSerializable
{
    public function __construct(
        private string $id,
        private string $nama,
        private string $email,
        private string $passwordHash,
    ) {
    }

    public function getId(): string { return $this->id; }
    public function getNama(): string { return $this->nama; }
    public function getEmail(): string { return $this->email; }

    public function verifikasiPassword(string $password): bool
    {
        return password_verify($password, $this->passwordHash);
    }

    abstract public function peran(): string;

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id, 'nama' => $this->nama, 'email' => $this->email,
            'password_hash' => $this->passwordHash, 'peran' => $this->peran(),
        ];
    }
}
