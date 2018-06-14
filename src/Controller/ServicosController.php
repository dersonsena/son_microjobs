<?php

namespace App\Controller;

use App\Entity\Servico;
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
}
