<?php
declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));
require BASE_PATH . '/src/autoload.php';

use PustakaKu\Exceptions\RentalException;
use PustakaKu\Repositories\{BookRepository, MemberRepository, RentalRepository};
use PustakaKu\Services\RentalService;
use PustakaKu\Support\View;

session_start();
$_SESSION['csrf'] ??= bin2hex(random_bytes(16));

$books = new BookRepository(BASE_PATH . '/data/books.json');
$members = new MemberRepository(BASE_PATH . '/data/members.json');
$rentals = new RentalRepository(BASE_PATH . '/data/rentals.json');
$service = new RentalService($books, $members, $rentals);

$page = $_GET['page'] ?? 'dashboard';
$isPost = $_SERVER['REQUEST_METHOD'] === 'POST';
$csrf = $_SESSION['csrf'];
if ($isPost && !hash_equals($csrf, (string) ($_POST['csrf'] ?? ''))) {
    http_response_code(419);
    exit('Token keamanan tidak valid. Muat ulang halaman.');
}

$member = isset($_SESSION['member_id']) ? $members->temukan($_SESSION['member_id']) : null;

if ($page === 'logout') {
    session_destroy();
    header('Location: index.php?page=login');
    exit;
}

if ($page === 'login') {
    if ($member) {
        redirect('dashboard');
    }
    $errors = [];
    $email = '';
    if ($isPost) {
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Format email tidak valid.';
        }
        if (strlen($password) < 6) {
            $errors['password'] = 'Kata sandi minimal 6 karakter.';
        }
        if (!$errors) {
            $m = $members->temukanByEmail($email);
            if ($m !== null && $m->verifikasiPassword($password)) {
                session_regenerate_id(true);
                $_SESSION['member_id'] = $m->getId();
                redirect('dashboard');
            }
            $errors['umum'] = 'Email atau kata sandi salah.';
        }
    }
    View::render('login', compact('page', 'member', 'csrf', 'errors', 'email'));
    exit;
}

if (!$member) {
    redirect('login');
}

try {
    if ($isPost && $page === 'sewa') {
        $r = $service->sewa($member, (string) ($_POST['book_id'] ?? ''), (int) ($_POST['hari'] ?? 0));
        flash('ok', 'Penyewaan berhasil. Jatuh tempo ' . tanggal($r->jatuhTempo()) . '.');
        redirect('dashboard');
    }
    if ($isPost && $page === 'kembali') {
        $service->kembalikan($member, (string) ($_POST['rental_id'] ?? ''));
        flash('ok', 'E-book berhasil dikembalikan.');
        redirect('dashboard');
    }
    if ($isPost && $page === 'perpanjang') {
        $service->perpanjang($member, (string) ($_POST['rental_id'] ?? ''));
        flash('ok', 'Masa sewa diperpanjang 7 hari.');
        redirect('dashboard');
    }
} catch (RentalException $ex) {
    flash('err', $ex->getMessage());
    redirect($page === 'sewa' ? 'katalog' : 'dashboard');
}

if ($page === 'katalog') {
    $q = trim((string) ($_GET['q'] ?? ''));
    $kategori = (string) ($_GET['kategori'] ?? '');
    $items = $books->cari($q, $kategori);
    $kategoriList = $books->daftarKategori();
    View::render('katalog', compact('page', 'member', 'csrf', 'q', 'kategori', 'items', 'kategoriList'));
    exit;
}

$peta = [];
foreach ($books->semua() as $b) {
    $peta[$b->getId()] = $b;
}
$riwayat = $rentals->milik($member->getId());
$aktif = array_values(array_filter($riwayat, static fn ($r) => $r->aktif()));
$sekarang = new DateTimeImmutable('today');
View::render('dashboard', compact('page', 'member', 'csrf', 'peta', 'riwayat', 'aktif', 'sekarang'));
