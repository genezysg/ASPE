<?php
namespace Acme\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Acme\BlogBundle\Model\PageInterface;
use Acme\BlogBundle\Model\DisciplinaInterface;

/**
 * 
 * @ORM\Table()
 * @ORM\Entity()
 */
class Disciplina implements DisciplinaInterface
{
	/**	 	 
	 * @ORM\Column(name="codigo", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $codigo;
	/**
	 * @ORM\Column(length=50)
	 */
	private $nome;
	/**
	 * @ORM\Column(type="integer")
	 */
	private $horas;
	/**
	 * @ORM\ManyToMany(targetEntity="Topico")
	 * @ORM\JoinTable(name="ementa",
	 *      joinColumns={@ORM\JoinColumn(name="cod_disciplina", referencedColumnName="codigo")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="cod_topico", referencedColumnName="codigo")}
	 *      )
	 **/
	private $topicos;
	
	public function getCodigo() {
		return $this->codigo;
	}
	public function getNome() {
		return $this->nome;
	}
	public function setNome($nome) {
		$this->nome = $nome;
		return $this;
	}
	public function getHoras() {
		return $this->horas;
	}
	public function setHoras($horas) {
		$this->horas = $horas;
		return $this;
	}	
	public function getTopicos(){
		return $this->topicos;
	}
}