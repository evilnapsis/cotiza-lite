<?php
namespace App\Service;

use CotizationData;
use CotizationItemData;
use ServiceData;
use StatusData;
use SettingData;
use PersonData;
use FormaData;
use FPDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

/**
 * Servicio encargado de la generación de reportes y exportación en Excel (PhpSpreadsheet) y PDF (FPDF).
 */
class ReportService {

    private CotizationService $cotizationService;

    public function __construct() {
        $this->cotizationService = new CotizationService();
    }

    public function getCotizationsByRange(string $start, string $finish): array {
        return $this->cotizationService->getByDateRange($start, $finish);
    }

    /**
     * Exporta el reporte de cotizaciones en un rango de fechas a formato Excel (.xlsx)
     */
    public function exportToExcel(string $start, string $finish): void {
        $products = CotizationData::getAllByRange($start, $finish);
        $spreadsheet = new Spreadsheet();

        $spreadsheet->getProperties()
            ->setCreator("Evilnapsis")
            ->setLastModifiedBy("Evilnapsis")
            ->setTitle("Reporte de Cotizaciones")
            ->setSubject("Reportes Cotiza Lite")
            ->setDescription("Reporte de cotizaciones exportado desde Cotiza Lite 2");

        $sheet = $spreadsheet->setActiveSheetIndex(0);
        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(35);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(25);

        // Header styles
        $sheet->getStyle('A5:E5')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
        $sheet->getStyle('A5:E5')->getFont()->setBold(true);
        $sheet->getStyle('A5:E5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF00AA55');

        $sheet->setCellValue('B2', 'Cotizaciones (' . $start . ' al ' . $finish . ')');
        $sheet->getStyle('B2')->getFont()->setBold(true)->setSize(14);

        $sheet->setCellValue('A5', 'Id')
            ->setCellValue('B5', 'Total')
            ->setCellValue('C5', 'Cliente')
            ->setCellValue('D5', 'Estado')
            ->setCellValue('E5', 'Fecha');

        $row = 6;
        $tags = StatusData::getAll();

        foreach ($products as $product) {
            $statusName = "Desconocido";
            foreach ($tags as $tag) {
                if ($tag->id == $product->status_id) {
                    $statusName = $tag->name;
                    break;
                }
            }

            $clientName = "Sin asignar";
            if (!empty($product->person_id)) {
                $cli = PersonData::getById($product->person_id);
                if ($cli) {
                    $clientName = $cli->name . ' ' . $cli->lastname;
                }
            }

            $items = CotizationItemData::getAllBy("cotization_id", $product->id);
            $total = 0;
            foreach ($items as $it) {
                $serv = ServiceData::getById($it->service_id);
                if ($serv) {
                    $total += ($it->q * $serv->amount);
                }
            }

            $sheet->setCellValue('A' . $row, $product->id)
                ->setCellValue('B' . $row, '$ ' . number_format($total, 2, '.', ','))
                ->setCellValue('C' . $row, $clientName)
                ->setCellValue('D' . $row, $statusName)
                ->setCellValue('E' . $row, $product->created_at);

            $row++;
        }

        if (ob_get_length()) {
            ob_end_clean();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="reporte-cotizaciones-' . date('Y-m-d') . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * Genera la cotización oficial en formato PDF usando FPDF
     */
    public function generatePdf(int $id): void {
        require_once dirname(__DIR__, 3) . '/fpdf/fpdf.php';

        $symbol = "$";
        $logo = SettingData::getByPreffix("general_logo")->val ?? '';
        $company = SettingData::getByPreffix("general_company")->val ?? '';
        $companyAddress = SettingData::getByPreffix("general_address")->val ?? '';
        $companyPhone = SettingData::getByPreffix("general_phone")->val ?? '';
        $companyRfc = SettingData::getByPreffix("general_rfc")->val ?? '';

        $sell = CotizationData::getById($id);
        if (!$sell) {
            die("Cotización no encontrada");
        }

        $operations = CotizationItemData::getAllBy("cotization_id", $id);
        $forma = !empty($sell->forma_id) ? FormaData::getById($sell->forma_id) : null;
        $formaName = $forma ? $forma->name : 'No definida';

        $pdf = new FPDF('P');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 8);

        $plusforimage = 0;
        if (!empty($logo)) {
            $src = dirname(__DIR__, 3) . "/storage/configuration/" . $logo;
            if (file_exists($src)) {
                $pdf->Image($src, 12, 5, 45, 25);
                $plusforimage = 25;
            }
        }

        $pdf->SetFont('Arial', 'B', 22);

        // Encabezado
        $pdf->setY(10);
        $pdf->setX(110);
        $pdf->Cell(5, 0, utf8_decode("COTIZACIÓN"));

        $pdf->SetFont('Arial', '', 12);
        $pdf->setY(20);
        $pdf->setX(140);
        $pdf->Cell(5, 0, "FECHA: ");
        $pdf->setY(17);
        $pdf->setX(160);
        $pdf->Cell(30, 5, date("d/m/Y", strtotime($sell->created_at)), 1);

        $pdf->setY(23);
        $pdf->setX(126);
        $pdf->Cell(5, 5, utf8_decode('COTIZACIÓN # '));
        $pdf->setY(23);
        $pdf->setX(160);
        $pdf->Cell(30, 5, $sell->id, 1);

        $pdf->setY(30);
        $pdf->setX(126);
        $pdf->Cell(65, 5, "FORMA DE PAGO", 1);
        $pdf->setY(35);
        $pdf->setX(126);
        $pdf->Cell(65, 5, strtoupper(utf8_decode($formaName)), 1);

        // Bloques Emisor / Cliente
        $pdf->SetTextColor(255, 255, 255);
        $pdf->setFillColor(0, 0, 0);
        $pdf->setY(45);
        $pdf->setX(12);
        $pdf->Cell(85, 35, "", 1, 1, 'C');
        $pdf->setY(45);
        $pdf->setX(12);
        $pdf->Cell(85, 5, "DATOS DE LA EMPRESA", 0, 1, 'C', 1);

        $pdf->setY(45);
        $pdf->setX(106);
        $pdf->Cell(85, 35, "", 1, 1, 'C');
        $pdf->setY(45);
        $pdf->setX(106);
        $pdf->Cell(85, 5, "DATOS DEL CLIENTE", 0, 1, 'C', 1);

        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 9);

        // Datos empresa
        $pdf->setY(52);
        $pdf->setX(15);
        $pdf->Cell(80, 5, utf8_decode($company ?: "Empresa"));
        $pdf->setY(57);
        $pdf->setX(15);
        $pdf->Cell(80, 5, utf8_decode("RFC: " . $companyRfc));
        $pdf->setY(62);
        $pdf->setX(15);
        $pdf->Cell(80, 5, utf8_decode("Dirección: " . $companyAddress));
        $pdf->setY(67);
        $pdf->setX(15);
        $pdf->Cell(80, 5, utf8_decode("Teléfono: " . $companyPhone));

        // Datos cliente
        $cliente = "Sin asignar";
        $clientRfc = "";
        $clientAddress = "";
        $clientPhone = "";
        if (!empty($sell->person_id)) {
            $cli = PersonData::getById($sell->person_id);
            if ($cli) {
                $cliente = $cli->name . ' ' . $cli->lastname;
                $clientRfc = $cli->rfc;
                $clientAddress = $cli->address;
                $clientPhone = $cli->phone;
            }
        }

        $pdf->setY(52);
        $pdf->setX(110);
        $pdf->Cell(80, 5, utf8_decode("CLIENTE: " . $cliente));
        $pdf->setY(57);
        $pdf->setX(110);
        $pdf->Cell(80, 5, utf8_decode("RFC: " . $clientRfc));
        $pdf->setY(62);
        $pdf->setX(110);
        $pdf->Cell(80, 5, utf8_decode("DIRECCIÓN: " . $clientAddress));
        $pdf->setY(67);
        $pdf->setX(110);
        $pdf->Cell(80, 5, utf8_decode("TELÉFONO: " . $clientPhone));

        // Tabla de Conceptos / Servicios
        $pdf->setFillColor(0, 0, 0);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->setY(88);
        $pdf->setX(12);
        $pdf->Cell(20, 6, "CANTIDAD", 1, 0, 'C', 1);
        $pdf->Cell(110, 6, "PRODUCTO / SERVICIO", 1, 0, 'C', 1);
        $pdf->Cell(25, 6, "P.U.", 1, 0, 'C', 1);
        $pdf->Cell(25, 6, "TOTAL", 1, 1, 'C', 1);

        $pdf->SetTextColor(0, 0, 0);
        $total = 0;
        $pdf->SetFont('Arial', '', 9);

        foreach ($operations as $op) {
            $product = ServiceData::getById($op->service_id);
            $pName = $product ? $product->name : 'Item';
            $pPrice = $product ? (float)$product->amount : 0.0;
            $subtotal = $op->q * $pPrice;
            $total += $subtotal;

            $pdf->setX(12);
            $pdf->Cell(20, 6, $op->q, 'LR', 0, 'C');
            $pdf->Cell(110, 6, utf8_decode($pName), 'LR');
            $pdf->Cell(25, 6, "$symbol " . number_format($pPrice, 2, ".", ","), 'LR', 0, 'R');
            $pdf->Cell(25, 6, "$symbol " . number_format($subtotal, 2, ".", ","), 'LR', 1, 'R');
        }

        $pdf->setX(12);
        $pdf->Cell(180, 0, '', 'T');
        $pdf->Ln(2);

        // Total
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->setX(120);
        $pdf->Cell(35, 7, "TOTAL: ", 0, 0, "R");
        $pdf->Cell(37, 7, "$symbol " . number_format($total, 2, ".", ","), 1, 1, "R");

        // Notas
        if (!empty($sell->notes)) {
            $pdf->Ln(4);
            $pdf->setX(12);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(180, 5, "NOTAS / OBSERVACIONES:", 0, 1);
            $pdf->SetFont('Arial', '', 9);
            $pdf->setX(12);
            $pdf->MultiCell(180, 5, utf8_decode($sell->notes), 1);
        }

        // Bloque de Firma
        $pdf->Ln(10);
        $pdf->setX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->setFillColor(0, 0, 0);
        $pdf->Cell(180, 5, "FIRMA Y CONFORMIDAD DE ENTREGA", 0, 1, 'C', 1);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->setX(12);
        $pdf->Cell(180, 18, "", 1, 1, 'C');

        if (ob_get_length()) {
            ob_end_clean();
        }
        $pdf->Output('I', "Cotizacion_#{$sell->id}.pdf");
        exit;
    }
}
