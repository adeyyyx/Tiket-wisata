<!-- Modal Login -->
<div class="modal fade" id="loginModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="post" action="index.php?page=login" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Login</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <?php if (!empty($error) && $_GET['page'] === 'login'): ?>
                    <div class="alert alert-danger"><?= e($error) ?></div>
                <?php endif; ?>
                <?php include "views/auth/login_form.php"; ?>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Login</button>
                <button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#registerModal"
                    data-bs-dismiss="modal">
                    Belum punya akun? Register
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Register -->
<div class="modal fade" id="registerModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="post" action="index.php?page=register" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Register</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <?php include "views/auth/register_form.php"; ?>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Register</button>
                <button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#loginModal"
                    data-bs-dismiss="modal">
                    Sudah punya akun? Login
                </button>
            </div>
        </form>
    </div>
</div>