<?php

namespace App\AdminBundle\Controller;

use App\Entity\Contratacoes;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RelatoriosController extends Controller
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/relatorios/faturamento", name="admin_relatorio_faturamento")
     */
    public function faturamento(Request $request)
    {
        $exportar = $request->get('exportar');

        $faturamento = $this->entityManager
            ->getRepository(Contratacoes::class)
            ->retornarFaturamento();

        if ($exportar === 'pdf') {
            $html = $this->render('@Admin/relatorios/relatorio_faturamento.twig', [
                'faturamento' => $faturamento
            ]);

            $dompdf = $this->get('dompdf');
            return $dompdf->streamHtml($html, "relatorio_faturamento.pdf");
        }

        return $this->render('@Admin/relatorios/faturamento.html.twig', [
            'faturamento' => $faturamento
        ]);
    }
}
