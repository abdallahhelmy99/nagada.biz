<div class="container">
<form class="form-signin" action="auth/login" method="post">
<p class="form-signin-heading">
<img src="<?php echo base_url(); ?>assets/img/logo.png">
</p>
<!--
<h3 class="form-signin-heading">Please Signin First</h3>
-->
<input type="text" name="identity" class="form-control" placeholder="Username" required autofocus>
<input type="password" name="password" class="form-control" placeholder="Password" required>
<!--
<label class="checkbox">
<input type="checkbox" name="remember" value="remember"> Remember me
</label>
//-->
<button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
</form>

</div> <!-- /container -->
