# Classe Captcha

Uma classe PHP simples para gerar e verificar imagens CAPTCHA. Esta classe cria uma imagem contendo um número aleatório de 4 dígitos, que o usuário deve inserir em um campo de texto para verificação.

## Funcionalidades

- Gera uma imagem CAPTCHA com um número aleatório de 4 dígitos.
- Incorpora a imagem diretamente no HTML como uma string codificada em base64.
- Verifica a entrada do usuário contra um valor hash oculto.

## Requisitos

- PHP 5.4 ou superior.
- Biblioteca GD para criação de imagens.
- Arquivo de fonte TTF (incluído no projeto).

## Instalação

1. Clone o repositório ou baixe o arquivo ZIP.
2. Inclua o arquivo `Captcha.php` em seu projeto.

    ```php
    require_once 'caminho/para/Captcha.php';
    ```
3. Crie sua "frase secreta" no arquivo `Captcha.php`.

## Uso

### Gerando o CAPTCHA

Para gerar a imagem CAPTCHA e o formulário, chame o método estático `Gerar` dentro da tag `<form>`:

    <?php
    require_once 'caminho/para/Captcha.php';

    Captcha::Gerar();
    ?>

### Conferindo o CAPTCHA

Para conferir se o número é o mesmo da imagem, chame o método `Conferir` antes de carregar a página:

    <?php
    require_once 'caminho/para/Captcha.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (Captcha::Conferir()) {
            echo "CAPTCHA verificado com sucesso!";
        } else {
            echo "Falha na verificação do CAPTCHA. Tente novamente.";
        }
    }
    ?>