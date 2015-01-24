<?php
namespace Acme\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Acme\BlogBundle\Model\CursoInterface;

/**
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class Curso implements CursoInterface
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
}