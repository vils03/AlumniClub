<?php 

class User {
    public $id;
	public $name;
    public $lastname;
	public $email;
    public $password;
	public $phoneNumber;
	public $userType;

    public function __construct($id, $name, $lastname, $email, $password, $phoneNumber, $userType){
        $this->id = $id;
		$this->name = $name;
        $this->lastname = $lastname;
        $this->email = $email;
		$this->password = $password;
		$this->phoneNumber = $phoneNumber;
		$this->userType = $userType;
    }

    public function storeInDB(): void {
        require_once "../db/DB.php";

        try{
			$db = new DB();
			$conn = $db->getConnection();
		}
		catch (PDOException $e) {
			echo json_encode([
				'success' => false,
				'message' => "Неуспешно свързване с базата данни",
			]);
		}
        $insertMainUser = $conn->prepare(
            "INSERT INTO `users` (firstname, lastname, emailaddress, userpassword, phoneNumber, userType)
             VALUES (:name, :lastname, :email, :password, :phoneNumber, :userType)");
            
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

        $insertResult = $insertMainUser->execute([
                'name' => $this->name,
                'lastname' => $this->lastname,
                'email' => $this->email,
				'password' => $hashedPassword,
				'email' => $this->email,
				'phoneNumber' => $this->phoneNumber,
				'userType' => $this->userType,
        ]);

        if (!$insertResult) {
            $errorInfo = $insertMainUser->errorInfo();
            $errorMessage = "";
            
            if ($errorInfo[1] == 1062) {
                $errorMessage = "Вече съществува потребител с този имейл";
            } else {
                $errorMessage = "Грешка при запис на информацията.";
            }
            throw new Exception($errorMessage);
        }
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
        if(empty($this->password)) {
            throw new Exception("Полето парола е задължително!");
        }
        if(empty($this->phoneNumber)) {
            throw new Exception("Полето телефон е задължително!");
        }
        if(empty($this->userType)) {
            throw new Exception("Полето роля е задължително!");
        }
        if(strlen($this->name) < 2 || strlen($this->name) >= 30 || !(preg_match('/^[\p{Cyrillic}]+[- \']?[\p{Cyrillic}]+$/u', $this->name) || preg_match('/^[a-zA-Z]+[- \']?[a-zA-Z]+$/', $this->name))) {
            throw new Exception("Моля, попълнете валидно име!");
        }
        if(strlen($this->lastname) < 2 || strlen($this->lastname) >= 30 || !(preg_match('/^[\p{Cyrillic}]+[- \']?[\p{Cyrillic}]+$/u', $this->lastname) || preg_match('/^[a-zA-Z]+[- \']?[a-zA-Z]+$/', $this->lastname))) {
            throw new Exception("Моля, попълнете валиднa фамилия!");
        }
        if(!(preg_match('/^[^@]+@[^@]+\.[^@]+$/', $this->email))) {
            throw new Exception("Моля, попълнете валиден имейл.");
        }
        if(strlen($this->password) < 8) {
            throw new Exception("Моля, попълнете валидна парола, която е поне 8 символа.");
        }
        if(!(preg_match('/^[0-9]{10}$/', $this->phoneNumber))) {
            throw new Exception("Моля, попълнете валиден телефонен номер от типа 08xxxxxxxx.");
        }
        if(strcmp($this->userType,'graduate')!=0 && strcmp($this->userType,'recruiter')!=0) {
            throw new Exception("Невалиден тип потребител");
        }
    }
}

class Graduate extends User {
    public $id;
    public $fn;
    public $major;
    public $class;
    public $status;
    public $location;

    public function __construct($id, $name, $lastname, $email, $password, $phoneNumber, $userType,
                                    $fn, $major, $class, $status, $location)
    {
        parent:: __construct($id, $name, $lastname, $email, $password, $phoneNumber, $userType);
        $this->id = $id;
		$this->fn = $fn;
		$this->major = $major;
		$this->class = $class;
		$this->status = $status;
		$this->location = $location;
    }

    public function fetchUserId (PDO $conn) {
        $sql = "SELECT UserId FROM User WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$this->email]);

        return $stmt->fetch(PDO::FETCH_ASSOC)["UserId"];
    }

    public function fetchMajorId(PDO $conn) {
        $sql = "SELECT MajorId FROM Major WHERE MajorName = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$this->major]);

        return $stmt->fetch(PDO::FETCH_ASSOC)["MajorId"];
    }

    public function storeInDB(): void {
        require_once "../db/DB.php";

        try{
            $db = new DB();
            $conn = $db->getConnection();
			$conn->beginTransaction();
		}
		catch (PDOException $e) {
			echo json_encode([
				'success' => false,
				'message' => "Неуспешно свързване с базата данни",
			]);
		}
        try {
            $errorMessage = "";
            if (parent::storeInDB()){
                
                $userId = fetchUserId($conn);
                $majorId = fetchMajor($conn);

                $insertGraduate = $conn->prepare(
                    "INSERT INTO `Graduate` (GraduateId, fn, major, class, status, location, majorId)
                    VALUES (:GraduateId, :fn, :major, :class, :status, :location, :majorId)");
            
                $insertResult = $insertGraduate->execute([
                    'GraduateId' => $userId,
                    'fn' => $this->fn,
                    'major' => $this->major,
                    'class' => $this->class,
                    'status' => $this->status,
                    'location' => $this->location,
                    'MajorId' => $majorId,
                ]);
                if ($insertResult) {
                    $this->conn->commit();
                } else {
                    $this->conn->rollback();
                }
             
            } else {
                $this->conn->rollback();
            }
        } catch (PDOException $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
            exit();
        }
        
    }

    public function validate(): void {
        try {
            parent::validateUser();
        }
        catch (PDOException $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
            exit();
        }

        if(empty($this->fn)) {
            throw new Exception("Полето факултетен номер е задължително!");
        }
        if(empty($this->major)) {
            throw new Exception("Полето специалност е задължително!");
        }
        if(empty($this->class)) {
            throw new Exception("Полето випуск е задължително!");
        }
        if(empty($this->status)) {
            throw new Exception("Полето статус е задължително!");
        }
        if(empty($this->location)) {
            throw new Exception("Полето локация е задължително!");
        }
        if(strlen($this->fn) < 6 || strlen($this->fn) > 10) {
            throw new Exception("Моля, попълнете валиден факултетен номер, който е с дължина между 6 и 10 символа!");
        }
        $validMajor = ['SI', 'KN', 'IS', 'I', 'AD', 'M', 'MI', 'PM'];
        if(!in_array($this->major, $validMajor)) {
            throw new Exception("Моля, попълнете валидна специалност!");
        }
        if(strlen($this->location) < 10 || strlen($this->location) > 100) {
            throw new Exception("Моля, попълнете валидна локация!");
        }
    }

}

class Recruiter extends User {
    public $id;
    public $companyName;

    public function __construct($id, $name, $lastname, $email, $password, $phoneNumber, $userType,
                                    $companyName)
    {
        parent:: __construct($id, $name, $lastname, $email, $password, $phoneNumber, $userType);
        $this->id = $id;
		$this->companyName = $companyName;
    }

    public function fetchUserId (PDO $conn) {
        $sql = "SELECT UserId FROM User WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$this->email]);

        return $stmt->fetch(PDO::FETCH_ASSOC)["UserId"];
    }

    public function storeInDB(): void {
        require_once "../db/DB.php";
        
        try{
            $db = new DB();
			$conn = $db->getConnection();
            $conn->beginTransaction();
		}
		catch (PDOException $e) {
			echo json_encode([
				'success' => false,
				'message' => "Неуспешно свързване с базата данни",
			]);
		}
        try {
            if (parent::storeInDB()){
                
                $userId = fetchUserId($conn);

                $insertRecruiter = $conn->prepare(
                    "INSERT INTO `Recruiter` (RecruiterId, companyName)
                    VALUES (:RecruiterId, :companyName)");
            
                $insertResult = $insertGraduate->execute([
                    'RecruiterId' => $userId,
                    'companyName'=> $this->companyName,
                ]);
                if ($insertResult) {
                    $this->conn->commit();
                } else {
                    $this->conn->rollback();
                }
             
            } else {
                $this->conn->rollback();
            }
        } catch (PDOException $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
            exit();
        }
        
    }

    public function validate(): void {
        try {
            parent::validateUser();
        }
        catch (PDOException $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
            exit();
        }

        if(empty($this->companyName)) {
            throw new Exception("Полето име на компания е задължително!");
        }
        if(strlen($this->companyName) < 2 || strlen($this->companyName) > 50) {
            throw new Exception("Моля, попълнете валидно име на компания, коeто е с дължина между 2 и 50 символа!");
        }
    }
}
?>
