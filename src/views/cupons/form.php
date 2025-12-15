<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../helpers/functions.php';
require_once __DIR__ . '/../../ui/components/alert.php';


if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'comercio') {
    header('Location: /src/views/auth/login.php');
    exit;
}

$error = $_SESSION['form_error'] ?? null;
$message = $_SESSION['form_message'] ?? null;
unset($_SESSION['form_error'], $_SESSION['form_message']);

$title = 'Criar Cupom';


$minDate = date('Y-m-d');

$useSidebar = true;
$currentPage = 'criar-cupom';
?>
<?php require_once __DIR__ . '/../../ui/layout/head.php'; ?>

<div class="mx-auto">
    
    <div class="mb-6">
        <h1 class="text-2xl font-bold">Criar Novo Cupom</h1>
        <p class="text-muted-foreground">Preencha os dados para criar uma nova promoção</p>
    </div>
        
        
        <div class="card">
            <section>
                
                <?php if (!empty($message)): ?>
                    <?= renderAlert('success', $message) ?>
                <?php endif; ?>
                
                <?php if (!empty($error)): ?>
                    <?= renderAlert('error', $error) ?>
                <?php endif; ?>
                
                
                <form method="post" action="/src/controllers/CupomController.php" class="form grid gap-6">
                    <input type="hidden" name="action" value="criar">
                    
                    <div class="grid gap-2">
                        <label for="dsc_cupom">Título da Promoção</label>
                        <input 
                            type="text" 
                            id="dsc_cupom" 
                            name="dsc_cupom" 
                            class="input"
                            required 
                            maxlength="25"
                            placeholder="Ex: Desconto de Natal em Alimentos"
                        >
                        <p class="text-muted-foreground text-sm">
                            Nome que será exibido para os associados (máx. 25 caracteres).
                        </p>
                    </div>
                    
                    
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="grid gap-2">
                            <label for="dta_inicio">Data de Início</label>
                            <input 
                                type="date" 
                                id="dta_inicio" 
                                name="dta_inicio" 
                                class="input"
                                required
                                min="<?= $minDate ?>"
                            >
                            <p class="text-muted-foreground text-sm">
                                Quando a promoção começa a valer.
                            </p>
                        </div>
                        <div class="grid gap-2">
                            <label for="dta_fim">Data de Término</label>
                            <input 
                                type="date" 
                                id="dta_fim" 
                                name="dta_fim" 
                                class="input"
                                required
                                min="<?= $minDate ?>"
                            >
                            <p class="text-muted-foreground text-sm">
                                Último dia de validade.
                            </p>
                        </div>
                    </div>
                    
                    
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="grid gap-2">
                            <label for="vlr_desconto">Percentual de Desconto (%)</label>
                            <input 
                                type="number" 
                                id="vlr_desconto" 
                                name="vlr_desconto" 
                                class="input"
                                step="0.01" 
                                min="0.01" 
                                max="100" 
                                required 
                                placeholder="10"
                            >
                            <p class="text-muted-foreground text-sm">
                                Entre 0.01% e 100%.
                            </p>
                        </div>
                        <div class="grid gap-2">
                            <label for="qtd_cupom">Quantidade de Cupons</label>
                            <input 
                                type="number" 
                                id="qtd_cupom" 
                                name="qtd_cupom" 
                                class="input"
                                min="1" 
                                max="1000"
                                required 
                                placeholder="50"
                            >
                            <p class="text-muted-foreground text-sm">
                                Cada cupom terá código único.
                            </p>
                        </div>
                    </div>
                    
                    
                    <div class="p-4 rounded-lg border bg-muted/30">
                        <h3 class="font-medium mb-2">Resumo da Promoção</h3>
                        <p class="text-sm text-muted-foreground">
                            Serão gerados <strong id="preview_qtd">0</strong> cupons com 
                            <strong id="preview_desconto">0%</strong> de desconto, 
                            válidos de <strong id="preview_inicio">--/--/----</strong> até 
                            <strong id="preview_fim">--/--/----</strong>.
                        </p>
                    </div>
                    
                    <div class="flex gap-3">
                        <button type="submit" class="btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Criar Cupom
                        </button>
                        <a href="/index.php" class="btn-outline">
                            Cancelar
                        </a>
                    </div>
                    
                </form>
            </section>
        </div>
        
    </div>

<script>

    function updatePreview() {
        const qtd = document.getElementById('qtd_cupom').value || '0';
        const desconto = document.getElementById('vlr_desconto').value || '0';
        const inicio = document.getElementById('dta_inicio').value;
        const fim = document.getElementById('dta_fim').value;
        
        document.getElementById('preview_qtd').textContent = qtd;
        document.getElementById('preview_desconto').textContent = desconto + '%';
        
        if (inicio) {
            const [y, m, d] = inicio.split('-');
            document.getElementById('preview_inicio').textContent = `${d}/${m}/${y}`;
        }
        
        if (fim) {
            const [y, m, d] = fim.split('-');
            document.getElementById('preview_fim').textContent = `${d}/${m}/${y}`;
        }
    }
    

    document.getElementById('dta_inicio').addEventListener('change', function() {
        document.getElementById('dta_fim').min = this.value;
        updatePreview();
    });
    
    document.getElementById('dta_fim').addEventListener('change', updatePreview);
    document.getElementById('qtd_cupom').addEventListener('input', updatePreview);
    document.getElementById('vlr_desconto').addEventListener('input', updatePreview);
</script>

<?php require_once __DIR__ . '/../../ui/layout/footer.php'; ?>
