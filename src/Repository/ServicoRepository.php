<?php

namespace App\Repository;

use App\Entity\Servico;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Servico|null find($id, $lockMode = null, $lockVersion = null)
 * @method Servico|null findOneBy(array $criteria, array $orderBy = null)
 * @method Servico[]    findAll()
 * @method Servico[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServicoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Servico::class);
    }

    public function findByUsuarioAndStatus($user = null, $status = null)
    {
        $query = $this->createQueryBuilder('s');

        if (!is_null($user)) {
            $query->where('s.usuario = :usuario')
                ->setParameter('usuario', $user);
        }

        if (!is_null($status) && !empty($status)) {
            $query->andWhere('s.status = :status')
                ->setParameter('status', $status);
        }

        return $query->orderBy('s.data_cadastro', 'desc')
            ->getQuery()
            ->getResult();
    }

    public function findByListagem($busca = null, $limite = 16, $ordem = 'desc')
    {
        $query = $this->createQueryBuilder('s')
            ->where("s.status = 'P'");

        if (!is_null($busca)) {
            $query->andWhere('s.titulo LIKE :busca')
                ->setParameter('busca', '%' . $busca . '%');
        }

        return $query->setMaxResults($limite)
            ->orderBy('s.data_cadastro', $ordem)
            ->getQuery()
            ->getResult();
    }
}
