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
                previewContainer.style.marginTop = '8px';
                previewContainer.style.padding = '8px';
                previewContainer.style.background = '#f8fafc';
                previewContainer.style.border = '1px dashed #cbd5e1';
                previewContainer.style.borderRadius = '6px';
                previewContainer.style.fontSize = '12px';
                
                if (file.type.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.style.maxWidth = '200px';
                    img.style.maxHeight = '150px';
                    img.style.display = 'block';
                    img.style.marginBottom = '6px';
                    img.style.borderRadius = '4px';
                    img.onload = function() { URL.revokeObjectURL(this.src); }
                    previewContainer.appendChild(img);
                }
                
                const fileInfo = document.createElement('div');
                fileInfo.style.color = '#475569';
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
