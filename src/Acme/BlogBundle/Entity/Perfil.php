<?php
namespace Acme\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Acme\BlogBundle\Model\PerfilInterface;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Perfil implements PerfilInterface
{
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $codigo;
	
	/**
	 * @ORM\Column(length=25)
	 */
	private $nome;
	
	/**
	 * @ORM\OneToMany(targetEntity="Usuario", mappedBy="perfil")
	 */
	private $usuarios;
	
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
	
	public function getUsuarios() {
		$nomes = array();
		foreach ($this->usuarios as $usuario)
			$nomes = $usuario->getNome();
		return  $nomes;
	}
	
	public function __toString(){
		return $this->nome;
	}
	
	
}