<?php if ($user): ?>
        </main>
        
        <footer class="footer">
            <span>© <?= date('Y') ?> AKADEMIX</span>
        </footer>
    </div> <!-- /main-content -->
</div> <!-- /app-wrapper -->
<?php else: ?>
</div> <!-- /hero-wrapper -->
<?php endif; ?>
<script>
// ── Dark Mode Toggle ────────────────────────────────
(function() {
    var btn = document.getElementById('themeToggle');
    if (!btn) return;
    btn.addEventListener('click', function() {
        var html = document.documentElement;
        var current = html.getAttribute('data-theme') || 'light';
        var next = current === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-theme', next);
        localStorage.setItem('akademix_theme', next);
    });
})();

// ── File Preview ────────────────────────────────────
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('input[type="file"]').forEach(function(input) {
        input.addEventListener('change', function(e) {
            let existingPreview = input.nextElementSibling;
            if (existingPreview && existingPreview.classList.contains('file-preview-container')) {
                existingPreview.remove();
            }
            if (this.files && this.files[0]) {
                const file = this.files[0];
                const previewContainer = document.createElement('div');
                previewContainer.className = 'file-preview-container';
                previewContainer.style.cssText = 'margin-top:8px;padding:8px;background:var(--surface-dim);border:1px dashed var(--outline-strong);border-radius:6px;font-size:12px;';
                if (file.type.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.style.cssText = 'max-width:200px;max-height:150px;display:block;margin-bottom:6px;border-radius:4px;';
                    img.onload = function() { URL.revokeObjectURL(this.src); }
                    previewContainer.appendChild(img);
                }
                const fileInfo = document.createElement('div');
                fileInfo.style.color = 'var(--on-surface-variant)';
                fileInfo.innerHTML = `<strong>File terpilih:</strong> ${file.name} (${(file.size / 1024).toFixed(1)} KB)`;
                previewContainer.appendChild(fileInfo);
                input.parentNode.insertBefore(previewContainer, input.nextSibling);
            }
        });
    });
});
</script>
</body>
</html>
<?php clear_old_input(); ?>
