<?php
namespace Acme\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Acme\BlogBundle\Model\UsuarioInterface;
/**
 * @ORM\Entity()
 * @ORM\Table(name="usuario")
 */

class Usuario implements UsuarioInterface{
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $matricula;
	/**
	 * @ORM\Column(length=100)
	 */
	protected $nome;
	/**
	 * @ORM\Column(length=20) 
	 */	
	protected $senha;
	/**	
	 * @ORM\Column(length=10)
	 */	
	protected $data_nasc;	
	/**	
	 * @ORM\Column(length=11)
	 */
	protected $cpf;
	/**
	 * @ORM\Column(length=100)
	 */ 
	protected $email;
	/** 
	 * @ORM\Column(length=8)
	 */
	protected $telefone;
	/**
	 * @ORM\Column(length=9)	
	 */
	protected $telefone_celular;

	/**
	 * @ORM\ManyToOne(targetEntity="Perfil", inversedBy="usuarios")
	 * @ORM\JoinColumn(name="cod_perfil", referencedColumnName="codigo")
	 */
	protected $perfil;
    /**
     * Get matricula
     *
     * @return integer 
     */
    public function getMatricula()
    {
        return $this->matricula;
    }

    /**
     * Set nome
     *
     * @param string $nome
     * @return Usuario
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    
        return $this;
    }

    /**
     * Get nome
     *
     * @return string 
     */
    public function getNome()
    {
        return $this->nome;
    }
    /**
     * Set senha
     *
     * @param string $senha
     * @return Usuario
     */
    public function setSenha($senha)
    {
    	$this->senha = $senha;
    	return $this;
    }
    
    /**
     * Get senha
     *
     * @return string
     */
    public function getSenha()
    {
    	return $this->senha;
    }
    
    /**
     * Set data_nasc
     *
     * @param string $dataNasc
     * @return Usuario
     */
    public function setDataNasc($dataNasc)
    {
        $this->data_nasc = $dataNasc;
    
        return $this;
    }

    /**
     * Get data_nasc
     *
     * @return string 
     */
    public function getDataNasc()
    {
        return $this->data_nasc;
    }

    /**
     * Set cpf
     *
     * @param string $cpf
     * @return Usuario
     */
    public function setCpf($cpf)
    {
        $this->cpf = $cpf;
    
        return $this;
    }

    /**
     * Get cpf
     *
     * @return string 
     */
    public function getCpf()
    {
        return $this->cpf;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Usuario
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set telefone
     *
     * @param string $telefone
     * @return Usuario
     */
    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;
    
        return $this;
    }

    /**
     * Get telefone
     *
     * @return string 
     */
    public function getTelefone()
    {
        return $this->telefone;
    }

    /**
     * Set telefone_celular
     *
     * @param string $telefoneCelular
     * @return Usuario
     */
    public function setTelefoneCelular($telefoneCelular)
    {
        $this->telefone_celular = $telefoneCelular;
    
        return $this;
    }

    /**
     * Get telefone_celular
     *
     * @return string 
     */
    public function getTelefoneCelular()
    {
        return $this->telefone_celular;
    } 
	
    /**
     * Set perfil
     *
     * @param Perfil $perfil
     * @return Usuario
     */
    public function setPerfil($perfil){
    	$this->perfil = $perfil;
    }
    
    /**
     * Get perfil
     *
     * @return Perfil
     */
    public function getPerfil(){
    	return $this->perfil;
    }
}