<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContratacoesRepository")
 */
class Contratacoes
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Servico", inversedBy="contratacoes")
     */
    private $servico;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Usuario", inversedBy="compras")
     */
    private $cliente;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Usuario", inversedBy="vendas")
     */
    private $freelancer;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $valor;

    /**
     * @ORM\Column(type="string", length=1, options={"fixed" = true}))
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $data_cadastro;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="update")
     */
    private $data_alteracao;

    public function getId()
    {
        return $this->id;
    }

    public function getServico(): ?Servico
    {
        return $this->servico;
    }

    public function setServico(?Servico $servico): self
    {
        $this->servico = $servico;

        return $this;
    }

    public function getCliente(): ?Usuario
    {
        return $this->cliente;
    }

    public function setCliente(?Usuario $cliente): self
    {
        $this->cliente = $cliente;

        return $this;
    }

    public function getFreelancer(): ?Usuario
    {
        return $this->freelancer;
    }

    public function setFreelancer(?Usuario $freelancer): self
    {
        $this->freelancer = $freelancer;

        return $this;
    }

    public function getValor()
    {
        return $this->valor;
    }

    public function setValor($valor): self
    {
        $this->valor = $valor;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDataCadastro(): ?\DateTimeInterface
    {
        return $this->data_cadastro;
    }

    public function setDataCadastro(\DateTimeInterface $data_cadastro): self
    {
        $this->data_cadastro = $data_cadastro;

        return $this;
    }

    public function getDataAlteracao(): ?\DateTimeInterface
    {
        return $this->data_alteracao;
    }

    public function setDataAlteracao(?\DateTimeInterface $data_alteracao): self
    {
        $this->data_alteracao = $data_alteracao;

        return $this;
    }
}
