<h1 align="center">🎟️ Cupom Amigo</h1>

<p align='center'>
  <img alt='PHP' src='https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white' />
  <img alt='MySQL' src='https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white' />
  <img alt='Tailwind CSS' src='https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white' />
  <img alt='JavaScript' src='https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black' />
</p>

## 💻 Sobre o Projeto

**Cupom Amigo** é uma plataforma web que conecta associados e comércios através de um sistema de cupons de desconto. O sistema permite que comércios cadastrem e gerenciem cupons promocionais, enquanto associados podem visualizar, buscar e utilizar esses cupons em estabelecimentos parceiros.

<img src="https://i.imgur.com/ApkDGWA.png" width="100%">

### ✨ Principais Funcionalidades

#### Para Associados

- 🔐 **Autenticação Segura**: Sistema de login e cadastro com validação
- 🎟️ **Meus Cupons**: Visualização de cupons disponíveis e utilizados
- 🔖 **Reserva de Cupons**: Reserva de cupons ativos dos comércios

#### Para Comércios

- 📊 **Dashboard de Gerenciamento**: Painel completo de controle de cupons
- ➕ **Criação de Cupons**: Cadastro de cupons com quantidade e validade
- ✅ **Utilização de Cupons**: Validação e uso de cupons através de código único

## 📦 Instalação e Configuração

### Pré-requisitos

- **XAMPP** (ou ambiente similar com Apache e MySQL)
- **PHP 7.4+**
- **MySQL 5.7+**

### Passos de Instalação

```bash
# Clone o repositório
git clone git@github.com:guifrancoi/cupom-amigo.git

# Entre no diretório do projeto
cd cupom-amigo
```

### Configuração da Aplicação

Edite o arquivo de configuração do banco de dados em `src/config/database.php`:

```php
$host = '127.0.0.1';
$dbname = 'cupom_amigo';
$user = 'root';
$pass = '';
```

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.
