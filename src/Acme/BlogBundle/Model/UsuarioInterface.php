<?php

namespace Acme\BlogBundle\Model;

Interface UsuarioInterface{

	public function getMatricula();
	
	public function setNome($nome);
	public function getNome();
	
	public function setDataNasc($dataNasc);
	public function getDataNasc();
		
	public function setCpf($cpf);
	public function getCpf();
	
	public function setEmail($email);
	public function getEmail();
	
	public function setTelefone($telefone);
	public function getTelefone();
	
	public function setTelefoneCelular($telefoneCelular);
	public function getTelefoneCelular();
	
}