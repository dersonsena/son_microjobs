<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Form\UsuarioType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
            $usuario->setSenha($senhaCriptografada);
            $usuario->setToken(md5(uniqid()));
            $usuario->setRoles('ROLE_ADMIN');

            $this->entityManager->persist($usuario);
            $this->entityManager->flush();

            $mensagem = (new \Swift_Message("{$usuario->getNome()}, ative sua conta no MicroJobs"))
                ->setFrom('noreply@email.com')
                ->setTo([$usuario->getEmail() => $usuario->getNome()])
                ->setBody($this->renderView('emails/usuarios/registro.html.twig', [
                    'nome' => $usuario->getNome(),
                    'token' => $usuario->getToken()
                ]), 'text/html');

            $mailer->send($mensagem);

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
     * @Route("/painel", name="painel")
     * @Template("default/painel.html.twig")
     */
    public function painel()
    {
        return [];
    }

    /**
     * @Route("/usuarios/ativar-conta/{token}", name="email_ativar_conta")
     */
    public function ativarConta($token)
    {
        $usuario = $this->entityManager
            ->getRepository(Usuario::class)
            ->findOneBy(['token' => $token]);

        $usuario->setStatus(true);

        $this->entityManager->persist($usuario);
        $this->entityManager->flush();

        $this->addFlash('success', 'Seu cadastro foi ativado com sucesso! Informe seu e-mail e senha para entrar.');
        return $this->redirectToRoute('login');
    }
}
