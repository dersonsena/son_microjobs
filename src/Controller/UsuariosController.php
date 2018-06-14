<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Form\UsuarioType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UsuariosController extends Controller
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

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
     * @param Request $request
     * @param \Swift_Mailer $mailer
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function create(Request $request, \Swift_Mailer $mailer)
    {
        $usuario = new Usuario;
        $form = $this->createForm(UsuarioType::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $encoder = $this->get('security.password_encoder');
            $senhaCriptografada = $encoder->encodePassword($usuario, $form->getData()->getPassword());

            $usuario->setSenha($senhaCriptografada)
                ->setToken(md5(uniqid()))
                ->setRoles('ROLE_FREELA');

            $this->entityManager->persist($usuario);
            $this->entityManager->flush();

            $this->get('email')->enviar(
                "{$usuario->getNome()}, ative sua conta no MicroJobs",
                [$usuario->getEmail() => $usuario->getNome()],
                "emails/usuarios/registro.html.twig",
                [
                    'nome' => $usuario->getNome(),
                    'token' => $usuario->getToken()
                ]
            );

            $this->addFlash('success', 'Cadastrado com sucesso! Verifique seu e-mail para completar o cadastro.');
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
     * @Route("/usuarios/ativar-conta/{token}", name="email_ativar_conta")
     */
    public function ativarConta($token)
    {
        $usuario = $this->entityManager
            ->getRepository(Usuario::class)
            ->findOneBy(['token' => $token, 'status' => 0]);

        if (is_null($usuario)) {
            $this->addFlash('danger', 'O Token informado é inválido!');
            return $this->redirectToRoute('login');
        }

        $usuario->setStatus(true);

        $this->entityManager->persist($usuario);
        $this->entityManager->flush();

        $this->addFlash('success', 'Seu cadastro foi ativado com sucesso! Informe seu e-mail e senha para entrar.');
        return $this->redirectToRoute('login');
    }

    /**
     * @Route("/painel/usuarios/mudar-para-cliente", name="mudar_para_cliente")
     * @Template("usuarios/mudar-para-cliente.html.twig")
     */
    public function mudarParaCliente()
    {
        return [];
    }

    /**
     * @Route("/painel/usuarios/mudar-para-cliente/confirmar", name="confirmar_mudar_para_cliente")
     * @param UserInterface $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmarMudarParaCliente(UserInterface $user)
    {
        $usuario = $this->entityManager
            ->getRepository(Usuario::class)
            ->find($user);

        $usuario->limparRoles();
        $usuario->setRoles('ROLE_CLIENTE');

        $this->entityManager->persist($usuario);
        $this->entityManager->flush();

        $this->addFlash('success', 'Seu perfil foi alterado para cliente.');
        return $this->redirectToRoute('painel');
    }
}
