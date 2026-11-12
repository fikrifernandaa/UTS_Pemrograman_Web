<?php
declare(strict_types=1);

use PustakaKu\Support\View;

function e(mixed $v): string { return View::e($v); }
function rupiah(int $n): string { return View::rupiah($n); }
function tanggal(DateTimeInterface $d): string { return View::tanggal($d); }

function redirect(string $page): never
{
    header('Location: index.php?page=' . urlencode($page));
    exit;
}

function flash(string $type, string $msg): void
{
    $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
}
