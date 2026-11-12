<?php
declare(strict_types=1);

namespace PustakaKu\Support;

final class View
{
    private const BULAN = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

    public static function e(mixed $v): string
    {
        return htmlspecialchars((string) $v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    public static function rupiah(int $n): string
    {
        return 'Rp ' . number_format($n, 0, ',', '.');
    }

    public static function tanggal(\DateTimeInterface $d): string
    {
        return $d->format('d') . ' ' . self::BULAN[(int) $d->format('n') - 1] . ' ' . $d->format('Y');
    }

    /** Render view ke dalam layout. */
    public static function render(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        ob_start();
        require BASE_PATH . "/views/$view.php";
        $content = ob_get_clean();
        require BASE_PATH . '/views/layout.php';
    }
}
