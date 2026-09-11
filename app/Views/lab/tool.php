<div class="page-header" style="text-align:center; margin-bottom:30px;">
    <h1><?= htmlspecialchars($tool['title']) ?></h1>
    <p style="color:var(--eafd-color-text-muted);"><?= htmlspecialchars($tool['description']) ?></p>
</div>

<div class="card" style="max-width:700px; margin:0 auto;">
    <?php if ($tool['tool_type'] === 'contrast'): ?>
        <h3>محاسبه‌گر نسبت کنتراست WCAG 2.2 AA</h3>
        <div class="form-group" style="margin-top:20px; margin-bottom:15px;">
            <label>رنگ پس‌زمینه (Background):</label>
            <input type="color" id="tool-bg" value="#090D16" style="width:100%; height:45px; border:none; cursor:pointer;">
        </div>
        <div class="form-group" style="margin-bottom:20px;">
            <label>رنگ متن (Text):</label>
            <input type="color" id="tool-text" value="#F1F5F9" style="width:100%; height:45px; border:none; cursor:pointer;">
        </div>
        <div style="text-align:center; padding:20px; background:#0d131f; border-radius:8px;">
            <span id="tool-result" class="badge badge-success" style="font-size:1.1rem; padding:8px 16px;">نسبت کنتراست: ۱۶.۴:۱ (تایید)</span>
        </div>
    <?php elseif ($tool['tool_type'] === 'generator'): ?>
        <h3>تولیدکننده رمز عبور ایمن EAFD</h3>
        <div style="margin-top:20px; text-align:center;">
            <input type="text" id="generated-pass" class="form-control" style="font-family:monospace; text-align:center; font-size:1.2rem; font-weight:bold; letter-spacing:2px;" readonly value="eA3#fD9!kL2$pQ8@">
            <button class="btn btn-primary" type="button" style="margin-top:15px;" onclick="document.getElementById('generated-pass').value = Math.random().toString(36).slice(-10) + 'A1!';">تولید کلمه عبور جدید 🔄</button>
        </div>
    <?php endif; ?>
</div>
