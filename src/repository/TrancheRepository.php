<?php
namespace App\Repository;

use App\Entity\Tranche;
use App\Repository\IRepository\TrancheRepositoryInterface;
use PDO;
use PDOException;

class TrancheRepository implements TrancheRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function insert(Tranche $tranche): void
    {
        $sql = "INSERT INTO tranche (nom, min, max, prix_par_kwh) VALUES (:nom, :min, :max, :prix_par_kwh)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nom' => $tranche->getNom(),
            ':min' => $tranche->getMin(),
            ':max' => $tranche->getMax(),
            ':prix_par_kwh' => $tranche->getPrixParKwh(),
        ]);
    }

    public function selectBy(int $kw): ?Tranche
    {
        // La condition SQL est : min <= kw AND (max IS NULL OR kw <= max)
        $sql = "SELECT nom, min, max, prix_par_kwh FROM tranche WHERE min <= :kw AND (max IS NULL OR max >= :kw) LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':kw' => $kw]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        return new Tranche(
            $row['nom'],
            (int)$row['min'],
            $row['max'] !== null ? (int)$row['max'] : null,
            (float)$row['prix_par_kwh']
        );
    }

    /**
     * @return Tranche[]
     */
    public function selectAll(): array
    {
        $sql = "SELECT nom, min, max, prix_par_kwh FROM tranche ORDER BY min ASC";
        $stmt = $this->pdo->query($sql);
        $tranches = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tranches[] = new Tranche(
                $row['nom'],
                (int)$row['min'],
                $row['max'] !== null ? (int)$row['max'] : null,
                (float)$row['prix_par_kwh']
            );
        }

        return $tranches;
    }
}
