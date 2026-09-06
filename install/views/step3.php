<h2>گام ۳: پیکربندی پایگاه داده</h2>
<p>اطلاعات پایگاه داده MySQL هاست اشتراکی خود را وارد نمایید:</p>

<?php if (isset($dbResult) && !$dbResult['success']): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($dbResult['message']) ?></div>
<?php endif; ?>

<form method="POST" action="index.php?step=3">
    <div class="form-group">
        <label>نوع پایگاه داده:</label>
        <select name="driver" class="form-control">
            <option value="sqlite">SQLite (بدون نیاز به تنظیمات سروری - پیشنهادی تست)</option>
            <option value="mysql">MySQL / MariaDB (پایگاه داده اصلی سرور)</option>
        </select>
    </div>
    <div class="form-group">
        <label>آدرس سرور دیتابیس (DB Host):</label>
        <input type="text" name="host" class="form-control" value="localhost">
    </div>
    <div class="form-group">
        <label>نام پایگاه داده (DB Name):</label>
        <input type="text" name="dbname" class="form-control" value="eafd_db">
    </div>
    <div class="form-group">
        <label>نام کاربری دیتابیس (DB User):</label>
        <input type="text" name="username" class="form-control" value="root">
    </div>
    <div class="form-group">
        <label>رمز عبور دیتابیس (DB Password):</label>
        <input type="password" name="password" class="form-control" value="">
    </div>

    <button type="submit" class="btn btn-primary">تست و ذخیره پیکربندی دیتابیس ←</button>
</form>
