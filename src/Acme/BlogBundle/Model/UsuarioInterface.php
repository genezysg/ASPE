<?php

namespace Acme\BlogBundle\Model;

Interface UsuarioInterface{
	/**
	 * Get matricula
	 *
	 * @return integer
	 */
	public function getMatricula();
	
	/**
	 * Set nome
	 *
	 * @param string $nome
	 * @return Usuario
	 */
	public function setNome($nome);
	
	/**
	 * Get nome
	 *
	 * @return string
	 */
	public function getNome();
	
	/**
	 * Set data_nasc
	 *
	 * @param \DateTime $data_nasc
	 * @return Usuario
	 */
	public function setDataNasc($dataNasc);
	
	/**
	 * Get data_nasc
	 *
	 * @return \DateTime
	 */
	public function getDataNasc();	
		
	/**
	 * Set cpf
	 *
	 * @param string $cpf
	 * @return Usuario
	 */
	public function setCpf($cpf);
	
	/**
	 * Get cpf
	 *
	 * @return string
	 */
	public function getCpf();
	
	/**
	 * Set email
	 *
	 * @param string $email
	 * @return Usuario
	 */
	public function setEmail($email);
	
	/**
	 * Get email
	 *
	 * @return string
	 */
	public function getEmail();
	
	/**
	 * Set telefone
	 *
	 * @param string $telefone
	 * @return Usuario
	 */
	public function setTelefone($telefone);
	
	/**
	 * Get telefone
	 *
	 * @return string
	 */
	public function getTelefone();
	
	/**
	 * Set telefone_celular
	 *
	 * @param string $telefoneCelular
	 * @return Usuario
	 */
	public function setTelefoneCelular($telefoneCelular);
	
	/**
	 * Get telefone_celular
	 *
	 * @return string
	 */
	public function getTelefoneCelular();
	
}