#!/bin/bash

# Script de instalação rápida para o sistema de captação de leads

echo "🚀 Instalando Sistema de Captação de Leads..."
echo ""

# Verificar se o PHP está instalado
if ! command -v php &> /dev/null; then
    echo "❌ PHP não está instalado!"
    echo "Instale o PHP com: sudo apt-get install php php-cli php-mbstring"
    exit 1
fi

echo "✅ PHP encontrado: $(php -v | head -n 1)"
echo ""

# Criar diretório de uploads se necessário
mkdir -p uploads
chmod 755 uploads

# Verificar se o arquivo de configuração existe
if [ ! -f "config.php" ]; then
    echo "❌ Arquivo config.php não encontrado!"
    exit 1
fi

echo "📝 IMPORTANTE: Configure o arquivo config.php com suas informações de email!"
echo ""
echo "Edite as seguintes linhas:"
echo "  - FROM_EMAIL"
echo "  - ADMIN_EMAIL"
echo "  - SMTP_USERNAME (se usar SMTP)"
echo "  - SMTP_PASSWORD (se usar SMTP)"
echo ""

# Criar arquivo CSV se não existir
if [ ! -f "leads.csv" ]; then
    touch leads.csv
    chmod 644 leads.csv
    echo "✅ Arquivo leads.csv criado"
fi

# Verificar se existe um PDF de amostra
if [ ! -f "sample-course.pdf" ]; then
    echo "⚠️  ATENÇÃO: Arquivo sample-course.pdf não encontrado!"
    echo ""
    echo "Para criar um PDF a partir do template HTML:"
    echo "  1. Abra o arquivo sample-course-template.html no navegador"
    echo "  2. Imprima como PDF (Ctrl+P > Salvar como PDF)"
    echo "  3. Salve como 'sample-course.pdf' neste diretório"
    echo ""
fi

# Verificar permissões
echo "🔒 Verificando permissões..."
chmod 644 index.html success.html config.php process.php
chmod 600 leads.csv 2>/dev/null || true

echo ""
echo "✅ Instalação concluída!"
echo ""
echo "📍 Para testar o sistema:"
echo ""
echo "   1. Configure o config.php com suas credenciais de email"
echo "   2. Adicione o arquivo sample-course.pdf"
echo "   3. Execute: php -S localhost:8000"
echo "   4. Acesse: http://localhost:8000"
echo ""
echo "🎉 Pronto para começar a captar leads!"
