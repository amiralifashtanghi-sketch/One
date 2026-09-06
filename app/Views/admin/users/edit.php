<div class="page-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
    <div>
        <h1 style="margin:0;">ویرایش کاربر: <?= htmlspecialchars($user['name'] ?? '') ?></h1>
    </div>
    <div>
        <a href="/admin/users" class="btn btn-secondary">← بازگشت به لیست کاربران</a>
    </div>
</div>

<div class="card" style="max-width:600px;">
    <form method="POST" action="/admin/users/save/<?= $user['id'] ?? 0 ?>">
        <input type="hidden" name="_csrf_token" value="<?= \App\Core\Csrf::generate() ?>">

        <div class="form-group" style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">نام کاربر:</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($user['name'] ?? '') ?>" disabled>
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">شماره موبایل:</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" disabled>
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">نقش کاربری (RBAC):</label>
            <select name="role" class="form-control">
                <option value="admin" <?= ($user['role'] ?? '') === 'admin' ? 'selected' : '' ?>>مدیر ارشد (Administrator)</option>
                <option value="operator" <?= ($user['role'] ?? '') === 'operator' ? 'selected' : '' ?>>اپراتور (Operator)</option>
                <option value="customer" <?= ($user['role'] ?? '') === 'customer' ? 'selected' : '' ?>>مشتری (Customer)</option>
            </select>
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">وضعیت حساب:</label>
            <select name="is_active" class="form-control">
                <option value="1" <?= ($user['is_active'] ?? 1) == 1 ? 'selected' : '' ?>>فعال</option>
                <option value="0" <?= ($user['is_active'] ?? 1) == 0 ? 'selected' : '' ?>>مسدود / غیرفعال</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%;">ذخیره تغییرات کاربر</button>
    </form>
</div>
