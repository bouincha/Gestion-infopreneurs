// Petites interactions de l'interface (aucune dépendance, aucun build).
(function () {
    'use strict';

    // Menu latéral sur mobile
    document.querySelectorAll('[data-sidebar-toggle]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.body.classList.toggle('sidebar-open');
        });
    });

    // Notifications : disparition automatique
    function dismiss(toast) {
        toast.classList.add('is-leaving');
        setTimeout(function () { toast.remove(); }, 250);
    }
    document.querySelectorAll('.toast').forEach(function (toast) {
        var timer = setTimeout(function () { dismiss(toast); }, 4500);
        var close = toast.querySelector('button');
        if (close) close.addEventListener('click', function () { clearTimeout(timer); dismiss(toast); });
    });

    // Fenêtre de confirmation pour les suppressions : <form data-confirm="...">
    var modal = document.getElementById('confirm-modal');
    var pendingForm = null;
    function closeModal() { modal.hidden = true; pendingForm = null; }
    if (modal) {
        document.querySelectorAll('form[data-confirm]').forEach(function (form) {
            form.addEventListener('submit', function (e) {
                if (form.dataset.confirmed) return;
                e.preventDefault();
                pendingForm = form;
                modal.querySelector('[data-confirm-title]').textContent = form.dataset.confirmTitle || 'Confirmer la suppression';
                modal.querySelector('[data-confirm-text]').textContent = form.dataset.confirm;
                modal.hidden = false;
                modal.querySelector('[data-confirm-ok]').focus();
            });
        });
        modal.querySelector('[data-confirm-ok]').addEventListener('click', function () {
            if (!pendingForm) return;
            pendingForm.dataset.confirmed = '1';
            pendingForm.submit();
        });
        modal.querySelectorAll('[data-confirm-cancel]').forEach(function (el) {
            el.addEventListener('click', closeModal);
        });
        modal.addEventListener('click', function (e) { if (e.target === modal) closeModal(); });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !modal.hidden) closeModal(); });
    }

    // Aperçu de l'image + glisser-déposer : <div class="upload" data-upload>
    document.querySelectorAll('[data-upload]').forEach(function (zone) {
        var input = zone.querySelector('input[type="file"]');
        var preview = zone.querySelector('.upload-preview');
        var name = zone.querySelector('[data-upload-name]');
        var remove = zone.querySelector('[data-upload-remove]');

        function show(file) {
            if (!file || !file.type.startsWith('image/')) return;
            var reader = new FileReader();
            reader.onload = function (e) {
                preview.innerHTML = '';
                var img = document.createElement('img');
                img.src = e.target.result;
                img.alt = '';
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
            if (name) name.textContent = file.name + ' · ' + Math.round(file.size / 1024) + ' Ko';
            if (remove) remove.checked = false;
        }

        input.addEventListener('change', function () { show(input.files[0]); });
        ['dragenter', 'dragover'].forEach(function (evt) {
            zone.addEventListener(evt, function (e) { e.preventDefault(); zone.classList.add('is-dragover'); });
        });
        ['dragleave', 'drop'].forEach(function (evt) {
            zone.addEventListener(evt, function (e) { e.preventDefault(); zone.classList.remove('is-dragover'); });
        });
        zone.addEventListener('drop', function (e) {
            if (!e.dataTransfer.files.length) return;
            input.files = e.dataTransfer.files;
            show(input.files[0]);
        });
    });

    // Sélecteur de couleur synchronisé avec le champ texte et les pastilles
    document.querySelectorAll('[data-color-field]').forEach(function (field) {
        var picker = field.querySelector('input[type="color"]');
        var text = field.querySelector('input[type="text"]');
        var preview = document.querySelector(field.dataset.colorField);
        function apply(value) {
            if (!/^#[0-9a-fA-F]{6}$/.test(value)) return;
            picker.value = value;
            text.value = value;
            if (preview) preview.style.setProperty('--c', value);
        }
        picker.addEventListener('input', function () { apply(picker.value); });
        text.addEventListener('input', function () { apply(text.value); });
        field.parentElement.querySelectorAll('[data-swatch]').forEach(function (swatch) {
            swatch.addEventListener('click', function () { apply(swatch.dataset.swatch); });
        });
    });

    // Filtres : envoi automatique au changement d'une liste déroulante
    document.querySelectorAll('form[data-auto-submit] select').forEach(function (select) {
        select.addEventListener('change', function () { select.form.submit(); });
    });

    // Afficher / masquer le mot de passe
    document.querySelectorAll('[data-password-toggle]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = document.getElementById(btn.dataset.passwordToggle);
            var visible = input.type === 'text';
            input.type = visible ? 'password' : 'text';
            btn.setAttribute('aria-label', visible ? 'Afficher le mot de passe' : 'Masquer le mot de passe');
            btn.querySelector('[data-eye-open]').hidden = !visible;
            btn.querySelector('[data-eye-closed]').hidden = visible;
        });
    });

    // Raccourci « / » pour la recherche
    document.addEventListener('keydown', function (e) {
        var search = document.querySelector('[data-search]');
        if (e.key === '/' && search && !/input|textarea|select/i.test(document.activeElement.tagName)) {
            e.preventDefault();
            search.focus();
        }
    });
})();
