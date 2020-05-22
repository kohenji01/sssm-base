<form class="form-signin" method="post" action="/Auth/login">
    <div id="login_box">
        <i class="fas fa-sign-in-alt fa-4x"></i>
        <h1 class="h5 mb-4 font-weight-normal">ログイン</h1>
        <label for="inputID" class="sr-only">Emailアドレス</label>
        <input name="login_id" value="" id="inputID" class="form-control" placeholder="ID" required autofocus>
        <label for="inputPassword" class="sr-only">パスワード</label>
        <input type="password" name="login_pw" value="" id="inputPassword" class="form-control" placeholder="パスワード" required>
        <div class="form-check mb-3">
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">ログイン</button>
    </div>
    {if $CI->systemErrorMessage}<div class="alert alert-danger" role="alert"><strong>エラー！</strong><br><small>{$CI->systemErrorMessage}</small></div>{/if}
</form>
<pre style="text-align: left;">{$smarty.session|print_r:true}</pre>
{*<pre style="text-align: left;">{$CI|print_r:true}</pre>*}