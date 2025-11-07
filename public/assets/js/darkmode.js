(function() {
  function applyTheme(theme) {
    const root = document.documentElement;
    const icon = document.getElementById('darkmode-icon');
    if (theme === 'dark') {
      root.classList.add('dark');
      if (icon) {
        icon.classList.remove('fa-sun');
        icon.classList.add('fa-moon');
      }
    } else {
      root.classList.remove('dark');
      if (icon) {
        icon.classList.remove('fa-moon');
        icon.classList.add('fa-sun');
      }
    }
  }

  // Inicialização baseada no localStorage ou preferência do SO
  try {
    const stored = localStorage.getItem('theme');
    const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    const initial = stored || (prefersDark ? 'dark' : 'light');
    applyTheme(initial);
  } catch (e) {}

  // Toggle do botão
  document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('darkmode-toggle');
    if (!btn) return;

    btn.addEventListener('click', function() {
      const isDark = document.documentElement.classList.contains('dark');
      const next = isDark ? 'light' : 'dark';
      applyTheme(next);
      try { localStorage.setItem('theme', next); } catch (e) {}
      // Atualiza rótulo de acessibilidade
      btn.setAttribute('aria-label', next === 'dark' ? 'Ativar modo claro' : 'Ativar modo escuro');
    });

    // Ajusta ícone inicial
    const isDark = document.documentElement.classList.contains('dark');
    const icon = document.getElementById('darkmode-icon');
    if (icon) {
      icon.classList.toggle('fa-sun', !isDark);
      icon.classList.toggle('fa-moon', isDark);
    }
  });
})();
