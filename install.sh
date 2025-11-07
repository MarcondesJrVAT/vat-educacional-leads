#!/bin/bash

# Script de instalação rápida para o Sistema de Captação de Leads MVC
# Execute: bash install.sh

set -e

# 1. Verificar PHP
if ! command -v php &> /dev/null; then
    echo "❌ PHP não está instalado! Instale com: sudo apt-get install php php-cli"
    exit 1
fi

PHP_VERSION=$(php -r 'echo PHP_VERSION;')
if [[ $(php -r 'echo version_compare(PHP_VERSION, "7.4", ">=");') != "1" ]]; then
    echo "❌ PHP 7.4+ é necessário. Versão detectada: $PHP_VERSION"
    exit 1
fi

echo "✅ PHP encontrado: $PHP_VERSION"

# 2. Verificar extensões
for ext in pdo pdo_mysql mbstring; do
    php -r "if (!extension_loaded('$ext')) exit(1);" || { echo "❌ Extensão PHP '$ext' não encontrada!"; exit 1; }
done
echo "✅ Extensões PDO, PDO_MYSQL e MBSTRING OK"

# 3. Criar pastas essenciais
mkdir -p storage/data storage/logs public/assets/pdf
chmod -R 755 storage public

touch storage/logs/app.log
chmod 664 storage/logs/app.log

touch storage/data/leads.csv
chmod 664 storage/data/leads.csv

# 4. Verificar PDF de amostra
if [ ! -f public/assets/pdf/sample-course.pdf ]; then
    echo "⚠️  PDF de amostra não encontrado em public/assets/pdf/sample-course.pdf"
    echo "  → Crie a partir do template: public/assets/pdf/sample-course-template.html"
    echo "  → Ou adicione seu próprio PDF com esse nome."
else
    echo "✅ PDF de amostra encontrado."
fi

# 5. Verificar config.php
if [ ! -f config/config.php ]; then
    echo "❌ config/config.php não encontrado! Copie e configure a partir de config/config.example.php"
    exit 1
fi

# 6. Instruções finais
cat <<EOF

🎉 Instalação concluída!

1. Edite config/config.php e configure seus emails (FROM_EMAIL, ADMIN_EMAIL, SMTP, etc).
2. (Opcional) Configure o banco de dados e ajuste as credenciais se desejar salvar leads no MySQL.
3. Execute:

   php -S localhost:8080 -t public

4. Acesse: http://localhost:8080

Pronto para captar leads! 🚀
EOF

exit 0
