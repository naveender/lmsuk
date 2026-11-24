/**
 * Aspire Learners - Enterprise Theme Switcher
 * Handles instant client-side theme switching, server persistence, and zero-flicker state sync.
 */
(function(window, document) {
    'use strict';

    function getCurrentTheme() {
        if (document.body && document.body.classList.contains('dark-layout')) {
            return 'dark';
        }
        if (document.documentElement && document.documentElement.getAttribute('data-theme') === 'dark') {
            return 'dark';
        }
        try {
            var stored = localStorage.getItem('theme');
            if (stored === 'dark' || stored === 'light') {
                return stored;
            }
        } catch (e) {}
        return 'light';
    }

    function updateDOMTheme(theme) {
        var isDark = theme === 'dark';
        var html = document.documentElement;
        var body = document.body;

        // 1. Update Root & Body Attributes / Classes
        if (html) {
            html.setAttribute('data-theme', theme);
            html.classList.remove(isDark ? 'light-layout' : 'dark-layout');
            html.classList.add(isDark ? 'dark-layout' : 'light-layout');
        }

        if (body) {
            body.setAttribute('data-layout', isDark ? 'dark-layout' : 'light-layout');
            body.classList.remove(isDark ? 'light-layout' : 'dark-layout');
            body.classList.add(isDark ? 'dark-layout' : 'light-layout');
        }

        // 2. Update Header Buttons
        var toggleBtns = document.querySelectorAll('.theme-toggle-btn, #adminThemeToggleBtn, #nonAdminThemeToggleBtn');
        toggleBtns.forEach(function(btn) {
            btn.setAttribute('data-theme', theme);
            var title = isDark ? 'Switch to Light mode' : 'Switch to Dark mode';
            btn.setAttribute('title', title);
            btn.setAttribute('aria-label', title);

            var iconLight = btn.querySelector('.theme-icon-light');
            var iconDark = btn.querySelector('.theme-icon-dark');
            if (iconLight) iconLight.style.display = isDark ? 'none' : '';
            if (iconDark) iconDark.style.display = isDark ? '' : 'none';

            // Micro-animation
            btn.style.transform = 'scale(0.92)';
            setTimeout(function() {
                btn.style.transform = '';
            }, 180);
        });

        // 3. Update Admin Dropdown Items
        var adminIcon = document.getElementById('adminThemeDropdownIcon');
        var adminLabel = document.getElementById('adminThemeDropdownLabel');
        var adminBadge = document.getElementById('adminThemeCurrentBadge');

        if (adminIcon) {
            adminIcon.className = 'feather icon-' + (isDark ? 'sun' : 'moon');
        }
        if (adminLabel) {
            adminLabel.textContent = isDark ? 'Light Mode' : 'Dark Mode';
        }
        if (adminBadge) {
            adminBadge.textContent = isDark ? 'Dark' : 'Light';
        }

        // 4. Update Non-Admin Dropdown Items
        var nonAdminIcon = document.getElementById('nonAdminThemeDropdownIcon');
        var nonAdminLabel = document.getElementById('nonAdminThemeDropdownLabel');
        if (nonAdminIcon) {
            nonAdminIcon.className = 'feather icon-' + (isDark ? 'sun' : 'moon') + ' mr-50';
        }
        if (nonAdminLabel) {
            nonAdminLabel.textContent = isDark ? 'Light Mode' : 'Dark Mode';
        }

        // 5. Update ApexCharts if present
        if (window.ApexCharts && typeof window.ApexCharts.exec === 'function') {
            try {
                var chartElements = document.querySelectorAll('.apexcharts-canvas');
                chartElements.forEach(function(el) {
                    var chartId = el.getAttribute('id');
                    if (chartId) {
                        window.ApexCharts.exec(chartId, 'updateOptions', {
                            theme: { mode: isDark ? 'dark' : 'light' }
                        }, false, true);
                    }
                });
            } catch (err) {}
        }

        // 6. Dispatch custom event for custom components/charts
        try {
            window.dispatchEvent(new CustomEvent('appThemeChanged', { detail: { theme: theme } }));
        } catch (e) {}
    }

    function setServerTheme(theme) {
        // Save to localStorage
        try {
            localStorage.setItem('theme', theme);
        } catch (e) {}

        // Save to cookie (1 year expiry)
        try {
            document.cookie = "theme=" + encodeURIComponent(theme) + ";path=/;max-age=31536000;SameSite=Lax";
        } catch (e) {}

        // Send AJAX sync to Laravel backend
        try {
            var csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
            var csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';

            fetch('/change-theme?theme=' + encodeURIComponent(theme), {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            }).catch(function(err) {
                // Silently ignore network failures as cookie and localStorage are already set
            });
        } catch (err) {}
    }

    window.toggleAppTheme = function(forcedTheme) {
        var current = getCurrentTheme();
        var nextTheme = forcedTheme ? forcedTheme : (current === 'dark' ? 'light' : 'dark');
        updateDOMTheme(nextTheme);
        setServerTheme(nextTheme);
        return nextTheme;
    };

    function bindThemeToggleEvents() {
        var selector = '.theme-toggle-btn, #adminThemeToggleBtn, #nonAdminThemeToggleBtn, #adminThemeDropdownToggle, #nonAdminThemeDropdownToggle, a[href*="change-theme"]';
        
        document.addEventListener('click', function(e) {
            var targetBtn = e.target.closest(selector);
            if (targetBtn) {
                e.preventDefault();
                e.stopPropagation();
                window.toggleAppTheme();
            }
        });
    }

    // Initialize as soon as DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            var current = getCurrentTheme();
            updateDOMTheme(current);
            bindThemeToggleEvents();
        });
    } else {
        var current = getCurrentTheme();
        updateDOMTheme(current);
        bindThemeToggleEvents();
    }

})(window, document);
