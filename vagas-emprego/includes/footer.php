<?php
// arquivo: includes/footer.php

?>
    </div>
    <footer class="bg-dark text-white py-2">  <!-- Reduzi o padding vertical de py-4 para py-2 -->
        <div class="container text-center">
            <p class="mb-0 small">&copy; <?= date('Y'); ?> Sistema de Vagas de Emprego</p>  <!-- Adicionei small e mb-0 -->
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
// Fechar automaticamente após 5 segundos
document.addEventListener('DOMContentLoaded', function() {
    let alert = document.querySelector('.alert');
    if (alert) {
        setTimeout(() => {
            let bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    }
});
</script>
</body>
</html>