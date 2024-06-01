<?php 

class User {
    public $id;
	public $name;
    public $lastname;
	public $email;
    public $password;
	public $phoneNumber;
	public $role;

    public function __construct($id, $name, $lastname, $email, $password, $phoneNumber, $role){
        $this->id = $id;
		$this->name = $name;
        $this->lastname = $lastname;
        $this->email = $email;
		$this->password = $password;
		$this->phoneNumber = $phoneNumber;
		$this->role = $role;
    }

    public function validateUser(): void {
        if(empty($this->name)) {
            throw new Exception("Полето име е задължително!");
        }
        if(empty($this->lastname)) {
            throw new Exception("Полето фамилия е задължително!");
        }
        if(empty($this->email)) {
            throw new Exception("Полето имeйл е задължително!");
        }
    }
}











?>