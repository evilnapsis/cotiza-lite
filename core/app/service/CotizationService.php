<?php
namespace App\Service;

use CotizationData;
use CotizationItemData;
use ServiceData;
use PersonData;
use StatusData;
use FormaData;
use UserData;

/**
 * Servicio para la gestión integral de Cotizaciones, Ítems y Cálculos financieros.
 */
class CotizationService {

    public function getAll(): array {
        $cotizations = CotizationData::getAll();
        foreach ($cotizations as $cot) {
            $this->enrich($cot);
        }
        return $cotizations;
    }

    public function getById($id): ?CotizationData {
        $cot = CotizationData::getById($id);
        if ($cot) {
            $this->enrich($cot);
        }
        return $cot;
    }

    public function create(int $userId): int {
        $cot = new CotizationData();
        $cot->addExtraFieldString("user_id", (string)$userId);
        $cot->addExtraFieldString("status_id", "1"); // Borrador
        $cot->addExtraField("created_at", "NOW()");
        $res = $cot->add();
        return (int)$res[1];
    }

    public function updateClientAndStatus(int $id, ?int $personId, int $statusId): void {
        $cot = CotizationData::getById($id);
        if (!$cot) return;

        if (!empty($personId)) {
            $cot->addExtraField("person_id", (string)$personId);
        } else {
            $cot->addExtraField("person_id", "NULL");
        }
        $cot->addExtraFieldString("status_id", (string)$statusId);
        $cot->update();
    }

    public function updateNotesAndPayment(int $id, ?int $formaId, string $notes): void {
        $cot = CotizationData::getById($id);
        if (!$cot) return;

        if (!empty($formaId)) {
            $cot->addExtraFieldString("forma_id", (string)$formaId);
        } else {
            $cot->addExtraField("forma_id", "NULL");
        }
        $cot->addExtraFieldString("notes", $notes);
        $cot->update();
    }

    public function addItem(int $cotizationId, int $serviceId, float $q): void {
        $item = new CotizationItemData();
        $item->addExtraFieldString("cotization_id", (string)$cotizationId);
        $item->addExtraFieldString("service_id", (string)$serviceId);
        $item->addExtraFieldString("q", (string)$q);
        $item->add();
    }

    public function deleteItem(int $itemId): void {
        $item = CotizationItemData::getById($itemId);
        if ($item) {
            $item->del();
        }
    }

    public function delete(int $id): void {
        $cot = CotizationData::getById($id);
        if (!$cot) return;

        $items = CotizationItemData::getAllBy("cotization_id", $id);
        foreach ($items as $it) {
            $it->del();
        }
        $cot->del();
    }

    public function getItems(int $cotizationId): array {
        $items = CotizationItemData::getAllBy("cotization_id", $cotizationId);
        $total = 0;
        foreach ($items as $it) {
            $service = ServiceData::getById($it->service_id);
            $it->service = $service;
            $it->service_name = $service ? $service->name : 'Servicio no encontrado';
            $it->service_code = $service ? $service->code : '';
            $it->service_kind = $service ? $service->kind : '';
            $it->price = $service ? (float)$service->amount : 0.0;
            $it->subtotal = (float)$it->q * $it->price;
            $total += $it->subtotal;
        }
        return ['items' => $items, 'total' => $total];
    }

    public function getByDateRange(string $start, string $finish): array {
        $cotizations = CotizationData::getAllByRange($start, $finish);
        foreach ($cotizations as $cot) {
            $this->enrich($cot);
        }
        return $cotizations;
    }

    public function count(): int {
        return count(CotizationData::getAll());
    }

    /**
     * Obtiene estadísticas diarias de cotizaciones vendidas (status_id = 3) en los últimos 30 días
     */
    public function getSoldDailyStatsLast30Days(): array {
        $con = \Database::getCon();
        $startDate = date('Y-m-d', strtotime('-29 days'));
        $endDate = date('Y-m-d');

        $sql = "SELECT DATE(c.created_at) as sale_date, 
                       COUNT(DISTINCT c.id) as total_count, 
                       COALESCE(SUM(ci.q * s.amount), 0) as total_amount 
                FROM cotization c 
                LEFT JOIN cotization_item ci ON ci.cotization_id = c.id 
                LEFT JOIN service s ON s.id = ci.service_id 
                WHERE c.status_id = 3 
                  AND DATE(c.created_at) >= '$startDate' 
                  AND DATE(c.created_at) <= '$endDate' 
                GROUP BY DATE(c.created_at) 
                ORDER BY sale_date ASC";

        $query = $con->query($sql);
        $salesMap = [];
        if ($query) {
            while ($row = $query->fetch_assoc()) {
                $salesMap[$row['sale_date']] = [
                    'count' => (int)$row['total_count'],
                    'amount' => (float)$row['total_amount']
                ];
            }
        }

        $meses = [
            1 => 'Ene', 2 => 'Feb', 3 => 'Mar', 4 => 'Abr',
            5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago',
            9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dic'
        ];

        $labels = [];
        $amounts = [];
        $counts = [];
        $dates = [];
        $totalSoldSum = 0;
        $totalSoldCount = 0;

        for ($i = 29; $i >= 0; $i--) {
            $curDate = date('Y-m-d', strtotime("-$i days"));
            $dia = date('j', strtotime($curDate));
            $mesNum = (int)date('n', strtotime($curDate));
            $mesTxt = $meses[$mesNum] ?? date('M', strtotime($curDate));
            $displayDate = $dia . ' ' . $mesTxt;

            $dates[] = $curDate;
            $labels[] = $displayDate;

            $amt = isset($salesMap[$curDate]) ? (float)$salesMap[$curDate]['amount'] : 0.0;
            $cnt = isset($salesMap[$curDate]) ? (int)$salesMap[$curDate]['count'] : 0;

            $amounts[] = $amt;
            $counts[] = $cnt;
            $totalSoldSum += $amt;
            $totalSoldCount += $cnt;
        }

        return [
            'labels' => $labels,
            'amounts' => $amounts,
            'counts' => $counts,
            'dates' => $dates,
            'total_sold_sum' => $totalSoldSum,
            'total_sold_count' => $totalSoldCount,
            'start_date' => date('d/m/Y', strtotime($startDate)),
            'end_date' => date('d/m/Y', strtotime($endDate))
        ];
    }

    private function enrich(CotizationData $cot): void {
        $client = !empty($cot->person_id) ? PersonData::getById($cot->person_id) : null;
        $cot->client = $client;
        $cot->client_name = $client ? ($client->name . ' ' . $client->lastname) : 'Sin asignar';

        $status = !empty($cot->status_id) ? StatusData::getById($cot->status_id) : null;
        $cot->status = $status;
        $cot->status_name = $status ? $status->name : 'Desconocido';

        $forma = !empty($cot->forma_id) ? FormaData::getById($cot->forma_id) : null;
        $cot->forma = $forma;
        $cot->forma_name = $forma ? $forma->name : 'No definida';

        $user = !empty($cot->user_id) ? UserData::getById($cot->user_id) : null;
        $cot->user = $user;
        $cot->user_name = $user ? $user->name : 'Sistema';

        $itemsData = $this->getItems($cot->id);
        $cot->items = $itemsData['items'];
        $cot->total = $itemsData['total'];
    }
}
