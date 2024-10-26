#!/bin/bash

SSH_USER="westsidemotorcyc"
SSH_HOST="westsidemotorcycle.com.br"
SSH_KEY="ssh_key.pem"
REMOTE_DIR="api.westsidemotorcycle.com.br/"
LOCAL_DIR="."
EXCLUDE_FILE="deploy-exclude.txt"

function echo_message() {
    echo -e "\n### $1 ###\n"
}

if [ ! -d "$LOCAL_DIR" ]; then
    echo_message "O diretório local $LOCAL_DIR não existe. Abortando o deploy."
    exit 1
fi

cat <<EOL > $EXCLUDE_FILE
.git/
node_modules/
vendor/
storage/
.env
deploy-exclude.txt
ssh_key.pem
error_log
EOL

echo_message "Enviando arquivos para o servidor..."
rsync -avz --exclude-from=$EXCLUDE_FILE -e "ssh -i $SSH_KEY" $LOCAL_DIR/ $SSH_USER@$SSH_HOST:$REMOTE_DIR


echo_message "Conectando ao servidor para atualizar dependências..."
ssh -i $SSH_KEY $SSH_USER@$SSH_HOST << EOF
    cd $REMOTE_DIR
    composer install --no-dev --optimize-autoloader
    php artisan migrate --force
    php artisan db:seed --force
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    exit
EOF

echo_message "Limpando arquivos temporários..."
rm $EXCLUDE_FILE

echo_message "Deploy concluído com sucesso!"
