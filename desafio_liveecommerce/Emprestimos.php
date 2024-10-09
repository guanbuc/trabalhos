<?php

include('index.php');

$classe = new Usuarios();
$classe->CreateTbLivros();
$classe->CreateTbEmprestimos();
$classe->CreateTbUsuarios();

if ($Titulo == NULL || 
    $Autor == NULL || 
    $Editora == NULL ||
    $Usuario == NULL ||
    $Cpf == NULL) {

    echo "<strong> Por favor, preencher todos os campos1! </strong>";

}else{

    $stmt = "SELECT COUNT(*) cont FROM Livros WHERE Titulo = " . "'$Titulo'";    
    $result = $classe->query($stmt);

    if ($result->fetchArray()['cont'] == 0){
        $array = array("Titulo" => $Titulo, "Autor" => $Autor, "Editora"=> $Editora);
        $classe->InserirLivro($array);
    }

    $stmt = "SELECT COUNT(*) cont FROM Usuarios WHERE CPF = " . "'$Cpf'";    
    $result = $classe->query($stmt); 

    if ($result->fetchArray()['cont'] == 0){
        $array = array("Usuario" => $Usuario, "Cpf" => $Cpf);
        $classe->InserirUsuario($array);
    }
    
    $result = $classe->Emprestar($emprestar, $Titulo, $Cpf); 
    
    $stmt = "SELECT DISTINCT 
                            L.Titulo, 
                            L.Autor, 
                            L.Editora, 
                            U.Nome,
                            MAX(e.Data) as Data,                             
                            CASE E.Status WHEN 1 THEN 'Alugado' ELSE 'Devolvido' END AS STATUS
                        FROM Livros l,
                        Usuarios u,
                        Emprestimos e
                        WHERE E.IdLivro = L.Id 
                        AND E.IdCliente = U.Id 
                        GROUP BY L.Titulo, 
                                 L.Autor, 
                                 L.Editora, 
                                 U.Nome,
                                 CASE E.Status WHEN 1 THEN 'Alugado' ELSE 'Devolvido' END";

    $classe->open('./ecommercedb');

    $result = $classe->query($stmt);

    echo "<style>
            table, th, td {
            border: 1px solid black;
            }
          </style>
            <table>
            <tr bgcolor='#6699FF'>
                <td> Data </td>
                <td> Titulo </td>
                <td> Autor </td>
                <td> Editora </td>
                <td> Nome </td>
                <td> STATUS </td>
            </tr>
            ";

    while ($row = $result->fetchArray()) {
        echo "<tr>
                <td> ". $row['Data'] . "</td>
                <td> ". $row['Titulo'] . "</td>
                <td> ". $row['Autor'] . "</td>
                <td> ". $row['Editora'] . "</td>
                <td> ". $row['Nome'] . "</td>
                <td> ". $row['STATUS'] . "</td>
              </tr>";
    }

    echo "</table>";

    //echo "Today is " . date("d/m/Y h:i:sa") . "<br>";
    
}



?>