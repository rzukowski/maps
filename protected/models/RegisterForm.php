<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RegisterForm
 *
 * @author rafal
 */
class RegisterForm extends CFormModel{

    public $name ; 
    public $email; 
    public $password ; 
    public $retypepassword;
    public $gender;
    
public function tableName() { return 'user'; }

public function rules() { 
    return array( 
        array('name, email, password, retypepassword', 'required'),
        array('gender','genderValid'),
        array('name','unique','message'=>'Użytkownik o podanej nazwie został już zarejestrowany.','className'=>'User'),
        array('email','unique','message'=>'Podany email jest już zarejestrowany w bazie','className'=>'User'),
        array('name, email, password, retypepassword', 'length', 'max'=>200,'message'=>'Email za długi. Maksymalny rozmiar to 200 znaków.'), 
        array('email', 'email', 'message'=>'Proszę podać poprawny email'), 
        array('retypepassword', 'required', 'on'=>'Register'), 
        array('retypepassword', 'compare', 'compareAttribute'=>'password','message'=>'Hasło i potwierdzenie hasła są różne.')
        );
        
}

public function genderValid($attribute,$params){
    
    if($this->gender==null){
        
        $this->addError('gender','Musisz wybrać płeć');
        
    }
    
    
}
}
