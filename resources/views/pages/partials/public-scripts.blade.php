<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    class ThemeManager {
        constructor() {
            this.STORAGE_KEY = 'theme-preference';
            this.DARK_THEME = 'dark';
            this.LIGHT_THEME = 'light';
            this.themeToggleBtn = document.getElementById('themeToggle');
            this.bodyElement = document.body;
            this.init();
        }

        init() {
            this.setTheme(this.getPreferredTheme());

            if (this.themeToggleBtn) {
                this.themeToggleBtn.addEventListener('click', () => this.toggleTheme());
            }

            if (window.matchMedia) {
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                    if (!this.hasUserPreference()) {
                        this.setTheme(e.matches ? this.DARK_THEME : this.LIGHT_THEME);
                    }
                });
            }
        }

        getPreferredTheme() {
            const userPreference = this.getUserPreference();
            return userPreference || this.getSystemPreference();
        }

        getUserPreference() {
            return localStorage.getItem(this.STORAGE_KEY);
        }

        hasUserPreference() {
            return localStorage.getItem(this.STORAGE_KEY) !== null;
        }

        getSystemPreference() {
            return (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches)
                ? this.DARK_THEME
                : this.LIGHT_THEME;
        }

        setTheme(theme) {
            if (![this.DARK_THEME, this.LIGHT_THEME].includes(theme)) {
                theme = this.LIGHT_THEME;
            }

            this.bodyElement.setAttribute('data-bs-theme', theme);
            localStorage.setItem(this.STORAGE_KEY, theme);
            this.updateMetaThemeColor(theme);
        }

        toggleTheme() {
            const currentTheme = this.bodyElement.getAttribute('data-bs-theme') || this.LIGHT_THEME;
            const newTheme = currentTheme === this.LIGHT_THEME ? this.DARK_THEME : this.LIGHT_THEME;
            this.setTheme(newTheme);
        }

        updateMetaThemeColor(theme) {
            let metaThemeColor = document.querySelector('meta[name="theme-color"]');

            if (!metaThemeColor) {
                metaThemeColor = document.createElement('meta');
                metaThemeColor.setAttribute('name', 'theme-color');
                document.head.appendChild(metaThemeColor);
            }

            metaThemeColor.setAttribute(
                'content',
                theme === this.DARK_THEME ? '#1a1a2e' : '#ffffff'
            );
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        new ThemeManager();
    });
</script>
