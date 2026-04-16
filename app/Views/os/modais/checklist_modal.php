<div class="modal fade" id="modalCheckEntrada" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-warning shadow-lg">
            <div class="modal-header bg-warning p-2 text-center text-dark">
                <h6 class="modal-title w-100 font-weight-bold uppercase"><i class="fas fa-sign-in-alt mr-2"></i>Incluir Item de Entrada</h6>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="text-xs font-weight-bold">ITEM PARA INSPEÇÃO:</label>
                    <select id="select_entrada" class="form-control select2" style="width: 100%">
                        <option value="">Selecione um item...</option>
                        <?php 
                        $currentCat = "";
                        foreach($lista_check_entrada as $e): 
                            // Lógica para criar grupos por categoria
                            if ($currentCat != $e['categoria']): 
                                if ($currentCat != "") echo '</optgroup>';
                                $currentCat = $e['categoria'];
                                echo '<optgroup label="' . strtoupper($currentCat ?? 'GERAL') . '">';
                            endif;
                        ?>
                            <option value="<?= $e['id'] ?>"><?= $e['descricao'] ?></option>
                        <?php endforeach; if ($currentCat != "") echo '</optgroup>'; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="text-xs font-weight-bold">OBSERVAÇÃO / MEDIÇÃO:</label>
                    <input type="text" id="obs_entrada" class="form-control" placeholder="Ex: Nível baixo, 85.000km, etc">
                </div>
            </div>
            <div class="modal-footer p-2">
                <button type="button" class="btn btn-primary btn-block font-weight-bold" onclick="salvarCheck('entrada')">
                    <i class="fas fa-plus-circle mr-1"></i> ADICIONAR AO CHECKLIST
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCheckTecnico" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-primary shadow-lg">
            <div class="modal-header bg-primary text-white p-2 text-center">
                <h6 class="modal-title w-100 font-weight-bold uppercase"><i class="fas fa-microchip mr-2"></i>Incluir Conferência Técnica</h6>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="text-xs font-weight-bold">ITEM TÉCNICO:</label>
                    <select id="select_tecnico" class="form-control select2" style="width: 100%">
                        <option value="">Selecione um item...</option>
                        <?php 
                        $currentCat = "";
                        foreach($lista_check_servicos as $s): 
                            if ($currentCat != $s['categoria']): 
                                if ($currentCat != "") echo '</optgroup>';
                                $currentCat = $s['categoria'];
                                echo '<optgroup label="' . strtoupper($currentCat ?? 'GERAL') . '">';
                            endif;
                        ?>
                            <option value="<?= $s['id'] ?>"><?= $s['descricao'] ?></option>
                        <?php endforeach; if ($currentCat != "") echo '</optgroup>'; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="text-xs font-weight-bold">OBSERVAÇÃO TÉCNICA:</label>
                    <input type="text" id="obs_tecnico" class="form-control" placeholder="Ex: Pressão 32psi, Pastilha 5mm, etc">
                </div>
            </div>
            <div class="modal-footer p-2">
                <button type="button" class="btn btn-primary btn-block font-weight-bold" onclick="salvarCheck('servico')">
                    <i class="fas fa-plus-circle mr-1"></i> ADICIONAR À CONFERÊNCIA
                </button>
            </div>
        </div>
    </div>
</div>