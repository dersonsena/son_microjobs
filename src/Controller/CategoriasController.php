<?php

namespace App\Controller;

use App\Entity\Categoria;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class CategoriasController extends Controller
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/categorias", name="categorias")
     */
    public function index()
    {
        return $this->render('categorias/index.html.twig', [
            'controller_name' => 'CategoriasController',
        ]);
    }

    /**
     * @Route("/categorias", name="categorias")
     * @Template("categorias/listar_topo.html.twig")
     */
    public function listarTopo()
    {
        $categorias = $this->entityManager
            ->getRepository(Categoria::class)
            ->findAll();

        return [
            'categorias' => $categorias
        ];
    }
}
