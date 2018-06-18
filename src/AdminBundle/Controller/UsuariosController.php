<?php

namespace App\AdminBundle\Controller;

use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UsuariosController extends Controller
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/usuarios/listar", name="admin_usuarios_listar")
     * @Template("@Admin/usuarios/listar.html.twig")
     */
    public function listar(Request $request)
    {
        $status = $request->get('status');

        if ($status === '' || is_null($status)) {
            $usuarios = $this->entityManager
                ->getRepository(Usuario::class)
                ->findAll();
        } else {
            $usuarios = $this->entityManager
                ->getRepository(Usuario::class)
                ->findBy(['status' => $status]);
        }

        return [
            'usuarios' => $usuarios,
            'status' => $status
        ];
    }
}
