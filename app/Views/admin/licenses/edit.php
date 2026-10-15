<div class="page-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
    <div>
        <h1 style="margin:0;">ویرایش لایسنس: <?= htmlspecialchars($license['license_key'] ?? '') ?></h1>
    </div>
    <div>
        <a href="/admin/licenses" class="btn btn-secondary">← بازگشت به لیست لایسنس‌ها</a>
    </div>
</div>

<div class="card" style="max-width:700px;">
    <form method="POST" action="/admin/licenses/save/<?= $license['id'] ?? 0 ?>">
        <input type="hidden" name="_csrf_token" value="<?= \App\Core\Csrf::generate() ?>">

        <div class="form-group" style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">کد لایسنس (یکتا):</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($license['license_key'] ?? '') ?>" disabled>
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">سقف تعداد دامنه فعال‌سازی:</label>
            <input type="number" name="max_domains" class="form-control" value="<?= $license['max_domains'] ?? 1 ?>">
        </div>

        <div class="form-group" style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">وضعیت لایسنس:</label>
            <select name="status" class="form-control">
                <option value="active" <?= ($license['status'] ?? '') === 'active' ? 'selected' : '' ?>>فعال (Active)</option>
                <option value="revoked" <?= ($license['status'] ?? '') === 'revoked' ? 'selected' : '' ?>>باطل‌شده (Revoked)</option>
                <option value="expired" <?= ($license['status'] ?? '') === 'expired' ? 'selected' : '' ?>>منقضی‌شده (Expired)</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%;">ذخیره تغییرات لایسنس</button>
    </form>
</div>
