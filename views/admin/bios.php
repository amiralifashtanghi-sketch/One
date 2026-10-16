<?php
ob_start();
?>

<h2 style="margin-top: 0; margin-bottom: 1.5rem;">مدیریت صفحات بیو و محتوا</h2>

<div class="card">
    <div style="margin-bottom: 1rem;">
        <input type="text" id="bioSearchInput" onkeyup="filterBiosTable()" placeholder="🔍 جست‌وجو بر اساس یوزرنیم، نام مالک، شماره همراه یا عنوان بیو..." style="padding: 0.75rem 1rem; border-radius: 8px; border: 1px solid var(--border); width: 100%;">
    </div>

    <table id="biosTable">
        <thead>
            <tr>
                <th>یوزرنیم (آدرس بیو)</th>
                <th>مالک صفحه</th>
                <th>عنوان بیو</th>
                <th>تعداد بلوک‌ها</th>
                <th>بازدید کل</th>
                <th>عملیات</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($bios)): ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--muted);">هیچ صفحه بیویی هنوز ایجاد نشده است.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($bios as $b): ?>
                    <tr>
                        <td>
                            <a href="/<?= htmlspecialchars($b['username']) ?>" target="_blank" style="color: var(--primary); font-weight: bold; text-decoration: none;">
                                /<?= htmlspecialchars($b['username']) ?> 🔗
                            </a>
                        </td>
                        <td>
                            <strong><?= htmlspecialchars($b['user_name'] ?? 'کاربر') ?></strong>
                            <br><small style="color: var(--muted);"><?= htmlspecialchars($b['phone']) ?></small>
                        </td>
                        <td><?= htmlspecialchars($b['title'] ?? 'بدون عنوان') ?></td>
                        <td><span class="badge badge-warning"><?= number_format($b['blocks_count']) ?> بلوک</span></td>
                        <td><strong><?= number_format($b['views_count']) ?></strong></td>
                        <td>
                            <a href="/admin/bios/edit/<?= $b['id'] ?>" class="btn" style="padding: 0.35rem 0.75rem; font-size: 0.85rem;">✏️ مدیریت محتوای بیو</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    function filterBiosTable() {
        const input = document.getElementById('bioSearchInput');
        const filter = input.value.toLowerCase();
        const table = document.getElementById('biosTable');
        const tr = table.getElementsByTagName('tr');

        for (let i = 1; i < tr.length; i++) {
            let tdText = tr[i].textContent || tr[i].innerText;
            if (tdText.toLowerCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
