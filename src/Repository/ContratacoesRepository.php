<?php

namespace App\Repository;

use App\Entity\Contratacoes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PDO;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Contratacoes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contratacoes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contratacoes[]    findAll()
 * @method Contratacoes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContratacoesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Contratacoes::class);
    }

    public function retornarFaturamento()
    {
        $sql = "
            SELECT SUM(valor) AS faturamento, data_cadastro
            FROM contratacoes
            GROUP BY MONTH(data_cadastro)
            ORDER BY data_cadastro DESC
        ";

        return $this->getEntityManager()
            ->getConnection()
            ->executeQuery($sql)
            ->fetchAll(PDO::FETCH_OBJ);
    }
}
