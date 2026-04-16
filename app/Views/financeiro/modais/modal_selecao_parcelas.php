<div class="modal fade" id="modalSelecaoParcelas" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-navy">
                <h5 class="modal-title"><i class="fas fa-list-ul mr-2"></i> Selecionar Parcelas para Recibo</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-imprimir-multiplos" action="<?= base_url('contas-receber/imprimir-recibos-lote') ?>" method="POST" target="_blank">
                <div class="modal-body">
                    <div id="info_agrupador_modal" class="mb-3"></div>
                    
                    <table class="table table-sm table-hover table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%" class="text-center">
                                    <input type="checkbox" id="selecionar_todas_pagas">
                                </th>
                                <th>Parcela</th>
                                <th>Vencimento</th>
                                <th>Data Pagto</th>
                                <th class="text-right">Valor Pago</th>
                            </tr>
                        </thead>
                        <tbody id="lista_parcelas_pagas">
                            </tbody>
                    </table>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary" id="btn-gerar-lote" disabled>
                        <i class="fas fa-file-pdf mr-1"></i> GERAR PDF UNIFICADO
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>