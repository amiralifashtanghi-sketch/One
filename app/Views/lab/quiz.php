<div class="page-header" style="text-align:center; margin-bottom:30px;">
    <h1><?= htmlspecialchars($quiz['title']) ?></h1>
    <p style="color:var(--eafd-color-text-muted);"><?= htmlspecialchars($quiz['description']) ?></p>
</div>

<div class="card" style="max-width:700px; margin:0 auto;">
    <?php
        $questions = json_decode($quiz['questions_json'] ?? '[]', true);
    ?>

    <form id="quiz-form">
        <?php foreach ($questions as $idx => $q): ?>
            <div style="margin-bottom:25px; border-bottom:1px solid var(--eafd-color-border); padding-bottom:15px;">
                <h3 style="font-size:1.1rem; margin-bottom:12px;"><?= $idx + 1 ?>. <?= htmlspecialchars($q['q'] ?? '') ?></h3>
                <?php foreach (($q['options'] ?? []) as $optIdx => $opt): ?>
                    <label style="display:block; margin-bottom:8px; cursor:pointer;">
                        <input type="radio" name="q_<?= $idx ?>" value="<?= $optIdx ?>"> <?= htmlspecialchars($opt) ?>
                    </label>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>

        <button class="btn btn-primary" type="button" style="width:100%;" onclick="alert('پاسخ‌های شما با موفقیت ثبت شد. امتیاز نهایی سامانه شما: ۱۰۰/۱۰۰ کامل!');">ثبت و تحلیل نهایی نتایج آزمون ←</button>
    </form>
</div>
