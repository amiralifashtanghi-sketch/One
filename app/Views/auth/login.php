<div class="card" style="max-width:450px; margin:40px auto; padding:40px;">
    <h1 style="font-size:1.8rem; text-align:center; margin-bottom:10px; color:var(--eafd-color-primary);">ورود به سامانه EAFD</h1>
    <p style="text-align:center; margin-bottom:30px; font-size:0.9rem;">شماره موبایل یا ایمیل خود را وارد نمایید</p>

    <form method="POST" action="/login">
        <input type="hidden" name="_csrf_token" value="<?= \App\Core\Csrf::generate() ?>">

        <div class="form-group" style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">شماره موبایل یا ایمیل:</label>
            <input type="text" name="identifier" class="form-control" value="" placeholder="۰۹۱۲1234567 یا ایمیل" required>
        </div>

        <div class="form-group" style="margin-bottom:25px;">
            <label style="display:block; margin-bottom:5px; font-weight:bold;">رمز عبور:</label>
            <input type="password" name="password" class="form-control" value="" required>
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%; padding:12px; font-size:1rem;">ورود به حساب کاربری ←</button>
    </form>
</div>
