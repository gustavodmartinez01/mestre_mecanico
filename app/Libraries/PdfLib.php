<?php

namespace App\Libraries;

use Mpdf\Mpdf;

class PdfLib
{
    protected $mpdf;

    public function __construct()
    {
        $this->mpdf = new Mpdf([
            'mode'          => 'utf-8',
            'format'        => 'A4',
            'margin_left'   => 10,
            'margin_right'  => 10,
            'margin_top'    => 15,
            'margin_bottom' => 15,
            // DICA DE OURO: Define pastas temporárias para evitar travamento de permissão
            'tempDir'       => WRITEPATH . 'cache', 
            // Permite carregar imagens do disco local (FCPATH)
            'allow_local_file_access' => true, 
        ]);

        $this->mpdf->SetFooter('Mestre Mecânico | Gerado em: {DATE d/m/Y H:i} | Página {PAGENO}');
    }

    public function gerar($html, $arquivo = 'documento.pdf', $destino = 'I')
    {
        // Limpa qualquer saída anterior
        if (ob_get_length() > 0) ob_end_clean();

        // Tenta aumentar o limite de memória apenas para a geração do PDF
        ini_set("memory_limit", "256M");

        try {
            $this->mpdf->WriteHTML($html);
            
            // O Output 'I' já envia os headers de PDF automaticamente, 
            // mas o exit garante que o CodeIgniter não envie nada depois.
            $this->mpdf->Output($arquivo, $destino);
            exit; 
            
        } catch (\Exception $e) {
            die("Erro ao gerar PDF: " . $e->getMessage());
        }
    }
}