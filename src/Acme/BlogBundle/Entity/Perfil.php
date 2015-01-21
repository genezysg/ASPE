<?php
namespace Acme\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Acme\BlogBundle\Model\PerfilInterface;

/**
 * Perfil
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class Perfil implements PerfilInterface
{
	/**
	 * @var integer
	 *
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $codigo;
	
	/**
	 * @var string
	 * @ORM\Column(length=25)
	 */
	private $nome;
	
	/**
	 * @ORM\OneToMany(targetEntity="Usuario", mappedBy="perfil")
	 */
	private $usuarios;
	/**
	 * Get Codigo
	 *
	 * @return PerfilInterface
	 */
	public function getCodigo() {
		return $this->codigo;
	}
	/**
	 * Get Nome
	 *
	 * @return PerfilInterface
	 */
	public function getNome() {
		return $this->nome;
	}
	/**
	 * Set Nome
	 * @param string $nome
	 * @return PerfilInterface
	 */
	public function setNome($nome) {
		$this->nome = $nome;
		return $this;
	}
	public function __toString(){
		return $this->nome;
	}
}