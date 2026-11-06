alert("witaj NA PK!");

const STORAGE_KEY = 'theme';
const toggle = document.querySelector('.theme-toggle');
const icon = toggle?.querySelector('.theme-toggle__icon');
const systemDark = window.matchMedia('(prefers-color-scheme: dark)');

function getTheme() {
    return document.documentElement.dataset.theme === 'dark' ? 'dark' : 'light';
}

function getSavedTheme() {
    try {
        const value = localStorage.getItem(STORAGE_KEY);
        return value === 'light' || value === 'dark' ? value : null;
    } catch (error) {
        return null;
    }
}

function syncToggle() {
    if (!toggle) {
        return;
    }

    const isDark = getTheme() === 'dark';

    toggle.setAttribute('aria-pressed', String(isDark));
    toggle.setAttribute(
        'aria-label',
        isDark ? 'Włącz jasny motyw' : 'Włącz ciemny motyw'
    );

    if (icon) {
        icon.textContent = isDark ? '☀' : '☾';
    }
}

function setTheme(theme, remember = true) {
    document.documentElement.dataset.theme = theme;

    if (remember) {
        try {
            localStorage.setItem(STORAGE_KEY, theme);
        } catch (error) {
            // Motyw nadal działa w bieżącym dokumencie.
        }
    }

    syncToggle();
}

toggle?.addEventListener('click', function () {
    setTheme(getTheme() === 'dark' ? 'light' : 'dark');
});

systemDark.addEventListener('change', function (event) {
    if (getSavedTheme() === null) {
        setTheme(event.matches ? 'dark' : 'light', false);
    }
});

syncToggle();
