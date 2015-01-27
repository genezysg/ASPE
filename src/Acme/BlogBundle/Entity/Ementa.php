<?php
namespace Acme\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Acme\BlogBundle\Model\EmentaInterface;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Ementa implements EmentaInterface
{
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $codigo;
	
	/** 
	 * @ORM\ManyToOne(targetEntity="Disciplina", inversedBy="topicos")
	 * @ORM\JoinColumn(name="cod_disciplina", referencedColumnName="codigo")
	 */
	private $disciplina;
	
	/** 
	 * @ORM\ManyToOne(targetEntity="Topico")
	 * @ORM\JoinColumn(name="cod_topico", referencedColumnName="codigo")
	 */
	private $topico;
	/**
	 * @ORM\Column(type="integer")
	 */
	private $indice;
	
	public function getCodigo() {
		return $this->codigo;
	}
	
	public function getDisciplina() {
		return $this->disciplina;
	}
	
	public function setDisciplina($disciplina) {
		$this->disciplina = $disciplina;		
	}
	
	public function getTopico() {
		return $this->topico;
	}
	
	public function setTopico($topico) {
		$this->topico = $topico;
	}
	
	public function getIndice() {
		return $this->indice;
	}
	
	public function setIndice($indice) {
		$this->indice = $indice;
	}
	
	public function __toString() {
		return $this->disciplina." - ".$this->topico;
	}
}