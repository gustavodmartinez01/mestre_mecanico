<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// --- ROTAS PÚBLICAS ---
$routes->get('/', 'LoginController::index');
$routes->get('login', 'LoginController::index');
$routes->post('login/autenticar', 'LoginController::autenticar');
$routes->get('logout', 'LoginController::logout');

// Rota de visualização externa (Cliente acessa sem login)
$routes->get('view/os/(:any)', 'OrdemServicoController::visualizar_cliente/$1');


// --- ROTAS PROTEGIDAS (GRUPO MASTER) ---
$routes->group('', ['filter' => 'auth'], function($routes) {
    
    $routes->get('dashboard', 'DashboardController::index');

    // Módulo: Caixa e Liquidação Financeira
    $routes->group('caixa', function($routes) {
        $routes->get('checkout/(:num)', 'CaixaController::checkout/$1');
        $routes->post('processar', 'CaixaController::processarPagamento');
        $routes->get('sucesso/(:segment)', 'CaixaController::sucesso/$1');
        $routes->get('imprimir/(:num)/(:any)', 'CaixaController::imprimirDocumento/$1/$2'); 
    });

    // Módulo: Funcionários
    $routes->group('funcionarios', function($routes) {
        $routes->get('/', 'FuncionarioController::index');
        $routes->get('novo', 'FuncionarioController::novo');
        $routes->post('salvar', 'FuncionarioController::salvar');
        $routes->get('editar/(:num)', 'FuncionarioController::editar/$1');
        $routes->get('excluir/(:num)', 'FuncionarioController::excluir/$1');
        $routes->get('pdf/(:num)', 'FuncionarioController::gerarPdf/$1');
    });

    // Módulo: Fornecedores
    $routes->group('fornecedores', function($routes) {
        $routes->get('/', 'FornecedorController::index');
        $routes->get('novo', 'FornecedorController::novo');
        $routes->get('editar/(:num)', 'FornecedorController::editar/$1');
        $routes->post('salvar', 'FornecedorController::salvar');
        $routes->get('excluir/(:num)', 'FornecedorController::excluir/$1');
        $routes->get('pdf/(:num)', 'FornecedorController::gerarPdf/$1');
    });

    // Módulo: Clientes
    $routes->group('clientes', function($routes) {
        $routes->get('/', 'ClienteController::index');
        $routes->get('novo', 'ClienteController::novo');
        $routes->get('editar/(:num)', 'ClienteController::editar/$1');
        $routes->post('salvar', 'ClienteController::salvar');
        $routes->get('excluir/(:num)', 'ClienteController::excluir/$1');
        $routes->get('detalhes/(:num)', 'ClienteController::detalhes/$1');
        $routes->get('pdf/(:num)', 'ClienteController::gerarPdf/$1');
    });

    // Módulo: Veículos
    $routes->group('veiculos', function($routes) {
        $routes->get('buscarPorCliente/(:num)', 'VeiculoController::buscarPorCliente/$1');
        $routes->post('salvar', 'VeiculoController::salvar');
        $routes->post('salvarRapido', 'VeiculoController::salvarRapido');
        $routes->get('editar/(:num)', 'VeiculoController::editar/$1');
        $routes->get('excluir/(:num)', 'VeiculoController::excluir/$1');
    });

    // Módulo: Serviços
    $routes->group('servicos', function($routes) {
        $routes->get('/', 'ServicoController::index');
        $routes->get('novo', 'ServicoController::novo');
        $routes->post('salvar', 'ServicoController::salvar');
        $routes->get('editar/(:num)', 'ServicoController::editar/$1');
        $routes->get('excluir/(:num)', 'ServicoController::excluir/$1');
        $routes->get('pdf', 'ServicoController::gerarPdf');
    });

    // Módulo: Produtos
    $routes->group('produtos', function($routes) {
        $routes->get('/', 'ProdutoController::index');
        $routes->get('novo', 'ProdutoController::novo');
        $routes->post('salvar', 'ProdutoController::salvar');
        $routes->get('editar/(:num)', 'ProdutoController::editar/$1');
        $routes->get('excluir/(:num)', 'ProdutoController::excluir/$1');
        $routes->get('movimentar/(:num)', 'ProdutoController::movimentar/$1');
        $routes->post('processar_movimentacao', 'ProdutoController::processar_movimentacao');
        $routes->get('pdf', 'ProdutoController::gerarPdf');
    });

    // Módulo: Ordens de Serviço (OS)
    $routes->group('os', function($routes) {
        $routes->get('/', 'OrdemServicoController::index');
        $routes->get('nova', 'OrdemServicoController::nova');
        $routes->post('salvar_abertura', 'OrdemServicoController::salvar_abertura');
        $routes->get('gerenciar/(:num)', 'OrdemServicoController::gerenciar/$1');
        $routes->post('aplicar_checklist', 'OrdemServicoController::aplicar_checklist');
        $routes->post('atualizar_item_checklist', 'OrdemServicoController::atualizar_item_checklist');
        $routes->post('adicionar_item', 'OrdemServicoController::adicionar_item');
        $routes->get('remover_item/(:num)/(:num)', 'OrdemServicoController::remover_item/$1/$2');
        $routes->post('upload_foto/(:num)', 'OrdemServicoController::upload_foto/$1');
        $routes->get('excluir_foto/(:num)/(:num)', 'OrdemServicoController::excluir_foto/$1/$2');
        $routes->get('finalizar/(:num)', 'OrdemServicoController::finalizar/$1');
        $routes->get('aprovar/(:num)', 'OrdemServicoController::aprovar/$1');
        $routes->get('alterar-status/(:num)/(:any)', 'OrdemServicoController::alterarStatus/$1/$2');
        $routes->get('imprimir/(:num)', 'OrdemServicoController::imprimir/$1');
        $routes->get('whatsapp/(:num)', 'OrdemServicoController::whatsapp/$1');
        $routes->get('pdf', 'OrdemServicoController::gerarPdf');
        $routes->post('incluir_item_checklist', 'OrdemServicoController::incluir_item_checklist');
        $routes->get('relatorio/(:num)', 'OrdemServicoController::relatorio/$1');
        $routes->get('buscar_itens_json/(:any)', 'OrdemServicoController::buscar_itens_json/$1');
        $routes->get('cancelar/(:num)', 'OrdemServicoController::cancelar/$1');
    });

    // Módulo: Cadastro Mestre de Checklists
    $routes->group('checklists', function($routes) {
        $routes->get('/', 'ChecklistController::index');
        $routes->get('novo', 'ChecklistController::novo');
        $routes->get('editar/(:num)', 'ChecklistController::editar/$1');
        $routes->post('salvar', 'ChecklistController::salvar');
        $routes->get('excluir/(:num)', 'ChecklistController::excluir/$1');
    });

    // Rotas de Financeiro
    $routes->get('financeiro/pagamento/(:num)', 'FinanceiroController::pagamento/$1');
    $routes->post('financeiro/processar_pagamento', 'FinanceiroController::processar_pagamento');
    

    $routes->group('contas-receber', function($routes) {
    $routes->get('/', 'ContasReceberController::index'); // Tela Principal
    $routes->get('completar/(:any)', 'ContasReceberController::completar/$1'); // Finalizar config da conta
    $routes->get('receber/(:any)', 'ContasReceberController::receber/$1'); // Baixa de parcela
    $routes->post('processar-baixa', 'ContasReceberController::processar_baixa');
        $routes->post('confirmar-recebimento', 'ContasReceberController::confirmar_recebimento');

    $routes->post('salvar-parcelamento', 'ContasReceberController::salvar_parcelamento');
    $routes->get('obter-proxima/(:any)', 'ContasReceberController::obter_proxima/$1');
    $routes->get('cancelar/(:any)', 'ContasReceberController::cancelar_grupo/$1');
    $routes->get('novo', 'ContasReceberController::novo');
    $routes->post('salvar', 'ContasReceberController::salvar');
    $routes->get('imprimir-extrato/(:any)', 'ContasReceberController::imprimir_extrato/$1');
    // Rota para o Recibo de uma parcela específica (ID da parcela)
    $routes->get('imprimir-recibo/(:num)', 'ContasReceberController::imprimir_recibo/$1');
    
    // Rota para o Comprovante de Cancelamento do grupo (ID agrupador)
    $routes->get('imprimir-cancelamento/(:any)', 'ContasReceberController::imprimir_cancelamento/$1');
    $routes->get('listar-pagas/(:any)', 'ContasReceberController::listar_pagas/$1');
    $routes->post('imprimir-recibos-lote', 'ContasReceberController::imprimir_recibos_lote');

});

$routes->group('contas-pagar', function($routes) {
    $routes->get('/', 'ContasPagarController::index');
    $routes->post('salvar', 'ContasPagarController::salvar');
    $routes->get('cancelar/(:any)', 'ContasPagarController::cancelar/$1');
    $routes->get('obter-proxima/(:any)', 'ContasPagarController::obter_proxima/$1');
    $routes->post('confirmar-pagamento', 'ContasPagarController::confirmar_pagamento');
    $routes->get('detalhes/(:any)', 'ContasPagarController::detalhes/$1');
});

$routes->group('compras', ['filter' => 'auth'], function($routes) {
    // Listagem principal
    $routes->get('/', 'ComprasController::index');
    
    // Cadastro de Nova Requisição
    $routes->get('nova', 'ComprasController::nova');
    $routes->post('salvar', 'ComprasController::salvar');
    $routes->post('salvar/(:any)?', 'ComprasController::salvar/$1');

    
    // Edição de Requisição Aberta
    $routes->get('editar/(:num)', 'ComprasController::editar/$1');
   // $routes->post('salvar/(:num)', 'ComprasController::salvar/$1');
    
    // Fluxo de Fechamento (Recebimento)
    $routes->get('fechar/(:num)', 'ComprasController::fechar/$1');
    $routes->post('finalizar_requisicao', 'ComprasController::finalizar_requisicao');
    
    // Ações Extras
    $routes->get('excluir/(:num)', 'ComprasController::excluir/$1');
    $routes->get('detalhes/(:num)', 'ComprasController::detalhes/$1');
    $routes->get('buscar_itens_ajax/', 'ComprasController::buscar_itens_ajax');

    $routes->get('imprimir/(:num)', 'ComprasController::imprimir/$1');
    $routes->get('whatsapp/(:num)', 'ComprasController::whatsapp/$1');

});
    $routes->get('relatorios/', 'RelatorioController::index');
    $routes->get('relatorios/gerar', 'RelatorioController::index');


}); // FIM DO GRUPO AUTH (Corrigido: adicionado o parêntese final)
$routes->get('view/requisicao/(:any)', 'ComprasController::visualizar_publico/$1');


