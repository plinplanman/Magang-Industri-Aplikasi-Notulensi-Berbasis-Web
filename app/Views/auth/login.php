<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

<div class="container">
	<div class="row">
		<div class="col-sm-6 offset-sm-3">

			<div class="card">
				<h2 class="card-header"><?= lang('Auth.loginTitle') ?></h2>
				<div class="card-body">

					<?= view('Myth\Auth\Views\_message_block') ?>

					<form action="<?= url_to('login') ?>" method="post" onsubmit="return validateForm()">
						<?= csrf_field() ?>

						<!-- Input Email/Username -->
						<div class="form-group">
							<label for="login"><?= lang('Auth.emailOrUsername') ?></label>
							<input type="text" class="form-control" name="login" placeholder="<?= lang('Auth.emailOrUsername') ?>" required>
						</div>

						<!-- Input Password -->
						<div class="form-group">
							<label for="password"><?= lang('Auth.password') ?></label>
							<input type="password" class="form-control" name="password" placeholder="<?= lang('Auth.password') ?>" required>
						</div>


						<button type="submit" class="btn btn-primary btn-block"><?= lang('Auth.loginAction') ?></button>
					</form>

					



					<hr>

					<?php if ($config->allowRegistration) : ?>
						<p><a href="<?= url_to('register') ?>"><?= lang('Auth.needAnAccount') ?></a></p>
					<?php endif; ?>
					<?php if ($config->activeResetter): ?>
						<p><a href="<?= url_to('forgot') ?>"><?= lang('Auth.forgotYourPassword') ?></a></p>
					<?php endif; ?>
				</div>
			</div>

		</div>
	</div>
</div>



<?= $this->endSection() ?>