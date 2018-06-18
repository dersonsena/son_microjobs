<?php

namespace App\AdminBundle\Controller;

use App\Entity\Servico;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ServicosController extends Controller
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Request $request
     * @return array
     * @Route("/listar-jobs", name="admin_listar_jobs")
     * @Template("@Admin/servicos/listar_jobs.html.twig")
     */
    public function listarJobs(Request $request)
    {
        $status = $request->get('status');

        $jobs = $this->entityManager
            ->getRepository(Servico::class)
            ->findByUsuarioAndStatus(null, $status);

        return [
            'status' => $status,
            'microJobs' => $jobs
        ];
    }
}
