<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Form\UsuarioType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UsuariosController extends Controller
{
    /**
     * @Route("/usuarios", name="usuarios")
     */
    public function index()
    {
        return $this->render('usuarios/index.html.twig', [
            'controller_name' => 'UsuariosController',
        ]);
    }

    /**
     * @Route("/usuarios/register", name="usuarios_register")
     * @Template("/usuarios/register.html.twig")
     */
    public function create(Request $request)
    {
        $usuario = new Usuario;
        $form = $this->createForm(UsuarioType::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $encoder = $this->get('security.password_encoder');
            $senhaCriptografada = $encoder->encodePassword($usuario, $form->getData()->getPassword());
            $usuario->setSenha($senhaCriptografada);
            $usuario->setToken(md5(uniqid()));
            $usuario->setRoles('ROLE_ADMIN');

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($usuario);
            $entityManager->flush();

            $this->addFlash('success', 'Seu cadastro foi criado com sucesso!');

            return $this->redirectToRoute('default');
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/usuarios/login", name="login")
     * @Template("usuarios/login.html.twig")
     */
    public function login(Request $request, AuthenticationUtils $authUtils)
    {
        $error = $authUtils->getLastAuthenticationError();
        $userName = $authUtils->getLastUsername();

        return [
            'userName' => $userName,
            'error' => $error
        ];
    }

    /**
     * @Route("/painel", name="painel")
     */
    public function painel()
    {
        return new Response('<html><body>PAINEL</body></html>');
    }
}
