<?php

Class MyDB extends SQLite3{

    function __construct(){
    try{
        $this->open('./ecommercedb');
        }catch(Exception $e){
        echo 'Exceção capturada:', $e->getMessage(), "<br>";
        return FALSE;
       }
    }
}

Class Livros extends MyDB{

    function CreateTbLivros(){
        $stmt = "SELECT EXISTS(SELECT 1 FROM sqlite_master WHERE type='table' AND name='Livros')";
        $result = $this->query($stmt);
        
        if($result->fetchArray()[0] == 0){
            $this->exec('CREATE TABLE Livros(Id INTEGER PRIMARY KEY AUTOINCREMENT, 
                                                Titulo STRING UNIQUE, 
                                                Autor STRING,
                                                Editora STRING)');
        }
    }

    Function InserirLivro($array){

        $stmt = "INSERT INTO Livros(Titulo, Autor, Editora) VALUES ('" . $array['Titulo'] . "', '" . $array['Autor']  . "', '" .  $array['Editora'] . "')";
        
        $this->exec($stmt);

    }

    function Emprestar($status, $Titulo, $Cpf){
        $E = new Emprestimos();

        if ($status == 1){

            $command = "SELECT Id FROM Livros WHERE Titulo = '$Titulo'";
            $result = $this->query($command);
            $row = $result->fetchArray();
            $IdLivro = $row['Id'];

            $command = "SELECT Id FROM Usuarios WHERE CPF = '$Cpf'";
            $result = $this->query($command);
            $row = $result->fetchArray();
            $IdCliente = $row['Id'];

            $this->close();
            
            $array = array("IdLivro" => $IdLivro, "IdCliente" => $IdCliente, "Status"=> 1);

            $E->InserirEmprestimos($array);

            return $Titulo;
        }elseif($status == 0){
            
            $command = "SELECT Id FROM Livros WHERE Titulo = '$Titulo'";
            $result = $this->query($command);
            $row = $result->fetchArray();
            $IdLivro = $row['Id'];

            $command = "SELECT Id FROM Usuarios WHERE CPF = '$Cpf'";
            $result = $this->query($command);
            $row = $result->fetchArray();
            $IdCliente = $row['Id'];

            $this->close();

            $array = array("IdLivro" => $IdLivro, "IdCliente" => $IdCliente, "Status"=> 0);

            $E->AtualizarEmprestimos($array);;
        };
    }

}

Class Emprestimos extends Livros{

    function CreateTbEmprestimos(){
        $stmt = "SELECT EXISTS(SELECT 1 FROM sqlite_master WHERE type='table' AND name='Emprestimos')";
        $result = $this->query($stmt);

        if($result->fetchArray()[0] == 0){
            $this->exec('CREATE TABLE Emprestimos(Id INTEGER PRIMARY KEY AUTOINCREMENT, 
                                                    IdLivro NUMERIC, 
                                                    IdCliente NUMERIC,
                                                    Status NUMERIC,
                                                    Data DATETIME)');
        }
    }

    Function InserirEmprestimos($array){

        $stmt = "INSERT INTO Emprestimos(IdLivro, IdCliente, Status, Data) VALUES (" . $array['IdLivro'] . ", " . $array['IdCliente']  . ", " .  $array['Status'] . ", datetime('now'))";
        
        $this->exec($stmt);

    }

    function AtualizarEmprestimos($array){
        try{
            $stmt = "UPDATE Emprestimos SET STATUS = " . $array['Status'] . " WHERE IdLivro = " . $array['IdLivro'] . " AND IdCLiente = " . $array['IdCliente'] . " AND Status = 1";
            $this->exec($stmt);
        
        }catch(Exception $e){

            NULL;
        }
    }

}

Class Usuarios extends Emprestimos{
    function CreateTbUsuarios(){
        $stmt = "SELECT EXISTS(SELECT 1 FROM sqlite_master WHERE type='table' AND name='Usuarios')";
        $result = $this->query($stmt);
        
        if($result->fetchArray()[0] == 0){
            $this->exec('CREATE TABLE Usuarios(Id INTEGER PRIMARY KEY AUTOINCREMENT, 
                                                    Nome STRING, 
                                                    CPF VARCHAR UNIQUE)');
        }
    }

    Function InserirUsuario($array){

        $stmt = "INSERT INTO Usuarios(Nome, CPF) VALUES ('" . $array['Usuario'] . "', '" . $array['Cpf']  . "')";
        
        $this->exec($stmt);

    }

}

echo "<form method='GET' action='/Emprestimos.php' name='formulario'>
    <label for='titulo'>Titulo:</label>
    <input type='text' name='titulo' value='$Titulo'/><br>
    <label for='autor'>Autor:</label>
    <input type='text' name='autor' value='$Autor'/><br>
    <label for='editora'>Editora:</label>
    <input type='text' name='editora' value='$Editora'/><br>
    <label for='usuario'>Usuário:</label>
    <input type='text' name='usuario' value='$Usuario'/><br>
    <label for='cpf'>CPF:</label>
    <input type='text' name='cpf' value='$Cpf'/><br>
    <input type='radio' id='emprestar' name='emprestar' value='1'>
    <label for='emprestar'>emprestar</label><br>
    <input type='radio' id='emprestar' name='emprestar' value='0'>
    <label for='devolver'>devolver</label><br>
    <input type='submit' name='botao' value='enviar'/><br>";

$Titulo = $_GET['titulo'];
$Autor = $_GET['autor'];
$Editora = $_GET['editora'];
$Usuario = $_GET['usuario'];
$Cpf = $_GET['cpf'];
$emprestar = $_GET['emprestar'];

?>