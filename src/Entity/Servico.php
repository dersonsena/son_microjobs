<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ServicoRepository")
 */
class Servico
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank(message="Campo Título não pode ficar em branco")
     * @Assert\Length(
     *     min="40",
     *     max="60",
     *     minMessage="Deve ter no Mínimo {{ limit }} caracteres",
     *     maxMessage="Deve ter no Máximo {{ limit }} caracteres"
     * )
     */
    private $titulo;

    /**
     * @ORM\Column(type="string", length=60)
     * @Gedmo\Slug(fields={"titulo"}, updatable=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $valor;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Campo Descrição não pode ficar em branco")
     */
    private $descricao;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $informacoes_adicionais;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Campo Prazo de Entrega não pode ficar em branco")
     */
    private $prazo_entrega;

    /**
     * @ORM\Column(type="string", length=1, options={
     *     "fixed" = true,
     *     "comment": "Usar P para publicado, A para em Análise, I para inativo, E para excluído e R para Rejeitado"
     * })
     * @Assert\Choice(choices={"P", "A", "I", "E", "R"})
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

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Selecione uma imagem para o Job")
     * @Assert\Image(
     *     mimeTypes={"image/*"},
     *     mimeTypesMessage="Tipo de arquivo Inválido",
     *     minWidth="400",
     *     minHeight="400",
     *     maxHeight="1000",
     *     maxWidth="1000",
     *     minHeightMessage="Mínimo 400px de altura",
     *     maxHeightMessage="Máximo 1000px de altura",
     *     maxWidthMessage="Máximo 1000px de largura",
     *     minWidthMessage="Mínimo 400px de largura",
     * )
     */
    private $imagem;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Categoria", mappedBy="servicos")
     */
    private $categorias;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Usuario", inversedBy="servicos")
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id", nullable=false)
     */
    private $usuario;

    public function __construct()
    {
        $this->categorias = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getValor()
    {
        return $this->valor;
    }

    public function setValor(string $valor): self
    {
        $this->valor = $valor;

        return $this;
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function setDescricao(string $descricao): self
    {
        $this->descricao = $descricao;

        return $this;
    }

    public function getInformacoesAdicionais(): ?string
    {
        return $this->informacoes_adicionais;
    }

    public function setInformacoesAdicionais(?string $informacoes_adicionais): self
    {
        $this->informacoes_adicionais = $informacoes_adicionais;

        return $this;
    }

    public function getPrazoEntrega(): ?int
    {
        return $this->prazo_entrega;
    }

    public function setPrazoEntrega(int $prazo_entrega): self
    {
        $this->prazo_entrega = $prazo_entrega;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus($status): self
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

    public function getImagem()
    {
        return $this->imagem;
    }

    public function setImagem($imagem): self
    {
        $this->imagem = $imagem;

        return $this;
    }

    /**
     * @return Collection|Categoria[]
     */
    public function getCategorias(): Collection
    {
        return $this->categorias;
    }

    public function addCategoria(Categoria $categoria): self
    {
        if (!$this->categorias->contains($categoria)) {
            $this->categorias[] = $categoria;
            $categoria->addServico($this);
        }

        return $this;
    }

    public function removeCategoria(Categoria $categoria): self
    {
        if ($this->categorias->contains($categoria)) {
            $this->categorias->removeElement($categoria);
            $categoria->removeServico($this);
        }

        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }
}
