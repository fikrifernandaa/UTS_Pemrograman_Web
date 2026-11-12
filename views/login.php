<main class="wrap narrow">
    <span class="tag">Masuk Member</span>
    <h1>Masuk untuk menyewa e-book.</h1>
    <form class="card pad form" method="post" action="index.php?page=login" data-validate novalidate>
        <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
        <?php if (isset($errors['umum'])): ?><p class="err-box" role="alert"><?= e($errors['umum']) ?></p><?php endif; ?>
        <div class="field">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" required value="<?= e($email) ?>" placeholder="rani.putri@mail.id" autocomplete="email">
            <small class="err"><?= e($errors['email'] ?? '') ?></small>
        </div>
        <div class="field">
            <label for="password">Kata sandi</label>
            <input id="password" name="password" type="password" required minlength="6" autocomplete="current-password">
            <small class="err"><?= e($errors['password'] ?? '') ?></small>
        </div>
        <button class="btn" type="submit">Masuk</button>
        <p class="mut">Akun demo: rani.putri@mail.id / pustaka123</p>
    </form>
</main>
