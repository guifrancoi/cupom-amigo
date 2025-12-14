<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!empty($_SESSION['user_id'])) {
    header('Location: /index.php');
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../helpers/functions.php';
require_once __DIR__ . '/../../ui/components/alert.php';

$error = $_SESSION['register_error'] ?? null;
$message = $_SESSION['register_message'] ?? null;
unset($_SESSION['register_error'], $_SESSION['register_message']);


$hideLayout = true;


try {
    $pdo = Database::getConnection();
    $stmtCats = $pdo->query('SELECT id_categoria, nome_categoria FROM CATEGORIA ORDER BY nome_categoria');
    $categorias = $stmtCats->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $categorias = [];
}

$title = 'Cadastrar';
?>
<?php require_once __DIR__ . '/../../ui/layout/head.php'; ?>

    
    <main class="main-content py-8 px-4">
        <div class="container max-w-2xl mx-auto">
            
            
            <div class="text-center mb-8">
                <a href="/index.php" class="inline-flex items-center gap-2 text-2xl font-bold text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" style="margin-top: 8px" viewBox="0 0 24 24" fill="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12.79 21 3 11.21v2c0 .53.21 1.04.59 1.41l7.79 7.79c.78.78 2.05.78 2.83 0l6.21-6.21c.78-.78.78-2.05 0-2.83z"></path><path d="M11.38 17.41c.78.78 2.05.78 2.83 0l6.21-6.21c.78-.78.78-2.05 0-2.83L12.63.58C12.25.21 11.74 0 11.21 0H5C3.9 0 3 .9 3 2v6.21c0 .53.21 1.04.59 1.41zM7.25 3c.69 0 1.25.56 1.25 1.25S7.94 5.5 7.25 5.5 6 4.94 6 4.25 6.56 3 7.25 3"></path>
                    </svg>
                    Cupom Amigo
                </a>
            </div>
            
            
            <div class="card">
                <header>
                    <h2>Criar sua conta</h2>
                    <p>Preencha os dados abaixo para se cadastrar no sistema</p>
                </header>
                
                <section>
                    
                    <?php if (!empty($message)): ?>
                        <?= renderAlert('success', $message) ?>
                    <?php endif; ?>
                    
                    <?php if (!empty($error)): ?>
                        <?= renderAlert('error', $error) ?>
                    <?php endif; ?>
                    
                    
                    <form method="post" action="/src/auth/process_cadastro.php" class="form grid gap-6" id="formCadastro">
                        
                        
                        <div class="flex flex-col gap-3">
                            <label>Tipo de Usuário</label>
                            <fieldset id="tipo_usuario_group" class="grid sm:grid-cols-2 gap-3">
                                <label class="font-normal flex items-center gap-2 p-3 rounded-lg border cursor-pointer has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                                    <input type="radio" name="tipo" value="associado" id="tipo_associado" checked>
                                    <div>
                                        <span class="font-medium">Associado</span>
                                        <p class="text-muted-foreground text-sm">Pessoa física com CPF</p>
                                    </div>
                                </label>
                                <label class="font-normal flex items-center gap-2 p-3 rounded-lg border cursor-pointer has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                                    <input type="radio" name="tipo" value="comercio" id="tipo_comercio">
                                    <div>
                                        <span class="font-medium">Comerciante</span>
                                        <p class="text-muted-foreground text-sm">Empresa com CNPJ</p>
                                    </div>
                                </label>
                            </fieldset>
                        </div>
                        
                        
                        <section class="grid gap-4 p-4 rounded-lg border bg-muted/30">
                            <h3 class="font-medium text-lg">Credenciais de Acesso</h3>
                            
                            <div class="grid gap-2">
                                <label for="usuario" id="label_usuario">CPF (apenas números) <span class="text-destructive">*</span></label>
                                <input 
                                    type="text" 
                                    id="usuario" 
                                    name="usuario"
                                    class="input"
                                    inputmode="numeric"
                                    required 
                                    placeholder="Digite apenas números"
                                >
                                <p class="text-muted-foreground text-sm">Este será seu login no sistema.</p>
                            </div>
                            
                            <div class="grid md:grid-cols-2 gap-4">
                                <div class="grid gap-2">
                                    <label for="senha">Senha <span class="text-destructive">*</span></label>
                                    <input type="password" id="senha" name="senha" class="input" required minlength="6">
                                    <p class="text-muted-foreground text-sm">Mínimo de 6 caracteres.</p>
                                </div>
                                <div class="grid gap-2">
                                    <label for="confirmar_senha">Confirmar Senha <span class="text-destructive">*</span></label>
                                    <input type="password" id="confirmar_senha" name="confirmar_senha" class="input" required minlength="6">
                                    <p class="text-muted-foreground text-sm">Repita a mesma senha.</p>
                                </div>
                            </div>
                        </section>
                        
                        
                        <section id="associado_section" class="grid gap-4 p-4 rounded-lg border">
                            <h3 class="font-medium text-lg flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                Dados Pessoais
                            </h3>
                            
                            
                            <div class="grid md:grid-cols-2 gap-4">
                                <div class="grid gap-2">
                                    <label for="nome_associado">Nome Completo <span class="text-destructive">*</span></label>
                                    <input type="text" id="nome_associado" name="nome_associado" class="input" placeholder="Digite seu nome completo" required>
                                    <p class="text-muted-foreground text-sm">Como aparecerá no sistema.</p>
                                </div>
                                <div class="grid gap-2">
                                    <label for="email_associado">E-mail <span class="text-destructive">*</span></label>
                                    <input type="email" id="email_associado" name="email_associado" class="input" placeholder="seu@email.com" required>
                                    <p class="text-muted-foreground text-sm">Para recuperação de senha.</p>
                                </div>
                            </div>
                            
                            
                            <div class="grid md:grid-cols-2 gap-4">
                                <div class="grid gap-2">
                                    <label for="dtn_nascimento">Data de Nascimento</label>
                                    <input type="date" id="dtn_nascimento" name="dtn_nascimento" class="input">
                                    <p class="text-muted-foreground text-sm">Usada para calcular sua idade.</p>
                                </div>
                                <div class="grid gap-2">
                                    <label for="sexo_associado">Sexo</label>
                                    <select id="sexo_associado" name="sexo_associado" class="select w-full">
                                        <option value="">-- Selecione --</option>
                                        <option value="M">Masculino</option>
                                        <option value="F">Feminino</option>
                                    </select>
                                    <p class="text-muted-foreground text-sm">Opcional.</p>
                                </div>
                            </div>
                            
                            <div class="grid gap-2">
                                <label for="endereco_associado">Endereço</label>
                                <input type="text" id="endereco_associado" name="endereco_associado" class="input" placeholder="Rua, número, complemento">
                                <p class="text-muted-foreground text-sm">Endereço completo.</p>
                            </div>
                            
                            
                            <div class="grid md:grid-cols-3 gap-4">
                                <div class="grid gap-2">
                                    <label for="cidade_associado">Cidade</label>
                                    <input type="text" id="cidade_associado" name="cidade_associado" class="input">
                                </div>
                                <div class="grid gap-2">
                                    <label for="estado_associado">Estado</label>
                                    <select id="estado_associado" name="estado_associado" class="select w-full">
                                        <option value="">UF</option>
                                        <option value="AC">AC</option><option value="AL">AL</option><option value="AP">AP</option>
                                        <option value="AM">AM</option><option value="BA">BA</option><option value="CE">CE</option>
                                        <option value="DF">DF</option><option value="ES">ES</option><option value="GO">GO</option>
                                        <option value="MA">MA</option><option value="MT">MT</option><option value="MS">MS</option>
                                        <option value="MG">MG</option><option value="PA">PA</option><option value="PB">PB</option>
                                        <option value="PR">PR</option><option value="PE">PE</option><option value="PI">PI</option>
                                        <option value="RJ">RJ</option><option value="RN">RN</option><option value="RS">RS</option>
                                        <option value="RO">RO</option><option value="RR">RR</option><option value="SC">SC</option>
                                        <option value="SP">SP</option><option value="SE">SE</option><option value="TO">TO</option>
                                    </select>
                                </div>
                                <div class="grid gap-2">
                                    <label for="cep_associado">CEP</label>
                                    <input type="text" id="cep_associado" name="cep_associado" class="input" inputmode="numeric" placeholder="00000-000">
                                </div>
                            </div>
                        </section>
                        
                        
                        <section id="comercio_section" class="grid gap-4 p-4 rounded-lg border hidden">
                            <h3 class="font-medium text-lg flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7"/><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><path d="M15 22v-4a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v4"/><path d="M2 7h20"/><path d="M22 7v3a2 2 0 0 1-2 2v0a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 16 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 12 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 8 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 4 12v0a2 2 0 0 1-2-2V7"/></svg>
                                Dados Empresariais
                            </h3>
                            
                            
                            <div class="grid md:grid-cols-2 gap-4">
                                <div class="grid gap-2">
                                    <label for="raz_social_comercio">Razão Social <span class="text-destructive">*</span></label>
                                    <input type="text" id="raz_social_comercio" name="raz_social_comercio" class="input" placeholder="Nome empresarial" required>
                                    <p class="text-muted-foreground text-sm">Nome oficial da empresa.</p>
                                </div>
                                <div class="grid gap-2">
                                    <label for="nome_fantasia_comercio">Nome Fantasia <span class="text-destructive">*</span></label>
                                    <input type="text" id="nome_fantasia_comercio" name="nome_fantasia_comercio" class="input" placeholder="Nome comercial" required>
                                    <p class="text-muted-foreground text-sm">Nome que aparecerá para clientes.</p>
                                </div>
                            </div>
                            
                            
                            <div class="grid md:grid-cols-2 gap-4">
                                <div class="grid gap-2">
                                    <label for="id_categoria">Categoria do Comércio <span class="text-destructive">*</span></label>
                                    <select id="id_categoria" name="id_categoria" class="select w-full" required>
                                        <option value="">-- Selecione a categoria --</option>
                                        <?php foreach ($categorias as $cat): ?>
                                            <option value="<?= htmlspecialchars($cat['id_categoria']) ?>">
                                                <?= htmlspecialchars($cat['nome_categoria']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <p class="text-muted-foreground text-sm">Segmento de atuação.</p>
                                </div>
                                <div class="grid gap-2">
                                    <label for="email_comercio">E-mail Comercial <span class="text-destructive">*</span></label>
                                    <input type="email" id="email_comercio" name="email_comercio" class="input" placeholder="comercio@email.com" required>
                                    <p class="text-muted-foreground text-sm">Para contato e recuperação.</p>
                                </div>
                            </div>
                            
                            <div class="grid gap-2">
                                <label for="endereco_comercio">Endereço</label>
                                <input type="text" id="endereco_comercio" name="endereco_comercio" class="input" placeholder="Rua, número, complemento">
                                <p class="text-muted-foreground text-sm">Endereço do estabelecimento.</p>
                            </div>
                            
                            
                            <div class="grid md:grid-cols-3 gap-4">
                                <div class="grid gap-2">
                                    <label for="cidade_comercio">Cidade</label>
                                    <input type="text" id="cidade_comercio" name="cidade_comercio" class="input">
                                </div>
                                <div class="grid gap-2">
                                    <label for="estado_comercio">Estado</label>
                                    <select id="estado_comercio" name="estado_comercio" class="select w-full">
                                        <option value="">UF</option>
                                        <option value="AC">AC</option><option value="AL">AL</option><option value="AP">AP</option>
                                        <option value="AM">AM</option><option value="BA">BA</option><option value="CE">CE</option>
                                        <option value="DF">DF</option><option value="ES">ES</option><option value="GO">GO</option>
                                        <option value="MA">MA</option><option value="MT">MT</option><option value="MS">MS</option>
                                        <option value="MG">MG</option><option value="PA">PA</option><option value="PB">PB</option>
                                        <option value="PR">PR</option><option value="PE">PE</option><option value="PI">PI</option>
                                        <option value="RJ">RJ</option><option value="RN">RN</option><option value="RS">RS</option>
                                        <option value="RO">RO</option><option value="RR">RR</option><option value="SC">SC</option>
                                        <option value="SP">SP</option><option value="SE">SE</option><option value="TO">TO</option>
                                    </select>
                                </div>
                                <div class="grid gap-2">
                                    <label for="cep_comercio">CEP</label>
                                    <input type="text" id="cep_comercio" name="cep_comercio" class="input" inputmode="numeric" placeholder="00000-000">
                                </div>
                            </div>
                        
                        </section>
                        
                        
                        <button type="submit" class="btn w-full">
                            Criar Conta
                        </button>
                        
                    </form>
                </section>
                
                <footer class="text-center">
                    <p class="text-sm text-muted-foreground">
                        Já tem uma conta? 
                        <a href="/src/views/auth/login.php" class="text-primary hover:underline font-medium">
                            Faça login
                        </a>
                    </p>
                </footer>
            </div>
            
        </div>
    </main>

    <script>

        const tipoAssoc = document.getElementById('tipo_associado');
        const tipoCom = document.getElementById('tipo_comercio');
        const secAssoc = document.getElementById('associado_section');
        const secCom = document.getElementById('comercio_section');
        const labelUsuario = document.getElementById('label_usuario');
        const inputUsuario = document.getElementById('usuario');


        const camposObrigatoriosAssociado = ['nome_associado', 'email_associado'];
        const camposObrigatoriosComercio = ['raz_social_comercio', 'nome_fantasia_comercio', 'id_categoria', 'email_comercio'];

        function toggleSections() {

            const camposAssoc = secAssoc.querySelectorAll('input, select');
            const camposCom = secCom.querySelectorAll('input, select');
            
            if (tipoAssoc.checked) {
                secAssoc.classList.remove('hidden');
                secCom.classList.add('hidden');
                labelUsuario.textContent = 'CPF (apenas números)';
                inputUsuario.placeholder = 'Digite 11 dígitos';
                inputUsuario.pattern = '[0-9]{11}';
                

                camposAssoc.forEach(campo => {
                    campo.disabled = false;
                    if (camposObrigatoriosAssociado.includes(campo.id)) {
                        campo.setAttribute('required', 'required');
                    }
                });
                

                camposCom.forEach(campo => {
                    campo.disabled = true;
                    campo.removeAttribute('required');
                });
            } else {
                secAssoc.classList.add('hidden');
                secCom.classList.remove('hidden');
                labelUsuario.textContent = 'CNPJ (apenas números)';
                inputUsuario.placeholder = 'Digite 14 dígitos';
                inputUsuario.pattern = '[0-9]{14}';
                

                camposAssoc.forEach(campo => {
                    campo.disabled = true;
                    campo.removeAttribute('required');
                });
                

                camposCom.forEach(campo => {
                    campo.disabled = false;
                    if (camposObrigatoriosComercio.includes(campo.id)) {
                        campo.setAttribute('required', 'required');
                    }
                });
            }
        }

        tipoAssoc.addEventListener('change', toggleSections);
        tipoCom.addEventListener('change', toggleSections);
        

        document.getElementById('formCadastro').addEventListener('submit', function(e) {
            const senha = document.getElementById('senha').value;
            const confirmar = document.getElementById('confirmar_senha').value;
            
            if (senha !== confirmar) {
                e.preventDefault();
                document.getElementById('senha-dialog').showModal();
                return false;
            }
        });
        

        toggleSections();
    </script>
    
    <?php 
    require_once __DIR__ . '/../../ui/components/dialog.php';
    echo renderAlertDialog(
        'senha-dialog',
        'Senhas não coincidem',
        'As senhas digitadas não são iguais. Por favor, verifique e tente novamente.',
        'Entendi'
    );
    ?>
</body>
</html>
