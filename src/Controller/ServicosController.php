<?php

namespace App\Controller;

use App\Entity\Contratacoes;
use App\Entity\Servico;
use App\Entity\Usuario;
use App\Form\ServicoType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\User\UserInterface;

class ServicosController extends Controller
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/servicos", name="servicos")
     */
    public function index()
    {
        return $this->render('servicos/index.html.twig', [
            'controller_name' => 'ServicosController',
        ]);
    }

    /**
     * @Route("/painel/servicos/cadastrar", name="cadastrar_job")
     * @Template("servicos/novo-micro-jobs.html.twig")
     * @param Request $request
     * @param UserInterface $user
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function cadastrar(Request $request, UserInterface $user)
    {
        $servico = new Servico;
        $form = $this->createForm(ServicoType::class, $servico);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imagem = $servico->getImagem();
            $nomeArquivo = md5(uniqid()) . "." . $imagem->guessExtension();

            $imagem->move(
                $this->getParameter('caminho_img_job'),
                $nomeArquivo
            );

            $servico->setImagem($nomeArquivo)
                ->setValor(30)
                ->setUsuario($user)
                ->setStatus('A');

            $this->entityManager->persist($servico);
            $this->entityManager->flush();

            $this->addFlash('success', 'Micro Job Cadastrado com sucesso.');
            return $this->redirectToRoute('painel');
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/painel/servicos/excluir/{id}", name="excluir_servico")
     */
    public function excluir(Servico $servico)
    {
        $servico->setStatus('E');

        $this->entityManager->persist($servico);
        $this->entityManager->flush();

        $this->addFlash('success', 'Micro Job foi excluído com sucesso.');
        return $this->redirectToRoute('painel');
    }

    /**
     * @Route("/contratar/{id}/{slug}", name="contratar_servico")
     * @param Servico $servico
     * @param UserInterface $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function contratarServico(Servico $servico, UserInterface $user)
    {
        $contratacao = new Contratacoes;
        $contratacao->setValor($servico->getValor())
            ->setServico($servico)
            ->setCliente($user)
            ->setFreelancer($servico->getUsuario())
            ->setStatus('A');

        $this->entityManager->persist($contratacao);
        $this->entityManager->flush();

        $this->get('email')->enviar(
            "{$user->getNome()} - Contratação de Serviço",
            [$user->getEmail() => $user->getNome()],
            "emails/servicos/contratacao_cliente.html.twig",
            ['servico' => $servico, 'cliente' => $user]
        );

        $this->get('email')->enviar(
            "{$servico->getUsuario()->getNome()} - Parabéns!",
            [$servico->getUsuario()->getEmail() => $servico->getUsuario()->getNome()],
            "emails/servicos/contratacao_freelancer.html.twig",
            ['servico' => $servico, 'cliente' => $user]
        );

        $this->addFlash('success', 'Serviço foi contratado!');
        return $this->redirectToRoute('default');
    }

    /**
     * @Route("/painel/servicos/listar-compras", name="listar_compras")
     * @Template("servicos/listar-compras.html.twig")
     */
    public function listarCompras(UserInterface $user)
    {
        $usuario = $this->entityManager
            ->getRepository(Usuario::class)
            ->find($user);

        return [
            'compras' => $usuario->getCompras()
        ];
    }

    /**
     * @Route("/painel/servicos/listar-vendas", name="listar_vendas")
     * @Template("servicos/listar-vendas.html.twig")
     */
    public function listarVendas(UserInterface $user)
    {
        $usuario = $this->entityManager
            ->getRepository(Usuario::class)
            ->find($user);

        return [
            'vendas' => $usuario->getVendas()
        ];
    }
}
