<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel = "stylesheet" href="styles2.css">
    <title>Activitées</title>
</head>
<body>

    <?php
    if(isset($_POST['update'])){
        $index = $_POST['index']; 
        $newNom = $_POST['nom']; 
        $newDescription = $_POST['description'];
        $newDate = $_POST['date'];

        updateData('info2.json',$index,$newNom, $newDescription, $newDate);

        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }

    if(isset($_POST['modifier'])){
        $index = $_POST['index'];
        displayupdatedate('info2.json', $index);
    } else{
        savedata('info2.json', $_POST);
        displaydata('info2.json');
    }

    if(isset ($_POST['supprimer'])){
        $index = $_POST['index'];
        deleteData('info2.json', $index);

        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
    ?>
    </table>
</body>
</html>
<?php
   function savedata ($jsonFile,$postData){
        $data  = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($postData['nom']) && isset($postData['description']) && isset($postData['date'])){
            $nom = htmlspecialchars($postData['nom']);
            $description = htmlspecialchars($postData['description']);
            $date = htmlspecialchars($postData['date']);
            $data[] = [
                'nom'=> $nom,
                'description'=> $description,
                'date' =>$date

            ];

        if(file_exists($jsonFile)){
            $existingData = json_decode(file_get_contents($jsonFile));
            if(is_array($existingData)){
                $data = array_merge($existingData,$data);
            }
        }
        file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT));
        }
    }

    function displaydata($jsonFile){
        if (file_exists($jsonFile)){
            $jsonContent = file_get_contents($jsonFile);
            $data = json_decode($jsonContent, true);
            if(!empty($data)){
                echo "<table border='1' cellpadding='10'>";
                echo "<tr><th>Nom</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Modifier</th>
                        <th>Supprimer</th></tr>";
                 foreach($data as $index =>$activity){
                    echo "<tr>";
                    echo "<td>".htmlspecialchars($activity['nom'])."</td>";
                    echo "<td>".htmlspecialchars($activity['description'])."</td>";
                    echo "<td>".htmlspecialchars($activity['date'])."</td>";
                    echo "<td>
                    <form method ='POST' action=''>
                        <input type='hidden' name='index' value='{$index}'>
                        <input type = submit name = modifier value = Modifier>
                    </form>
                    </td>";
                    echo "<td>
                    <form method ='POST' action=''>
                        <input type='hidden' name='index' value='{$index}'>
                        <input type = submit name = supprimer value = Supprimer>    
                    </form>
                    </td>";
                    echo "</tr>";
            
                }
                echo "</table>";
            }
        }
    }

    function displayupdatedate($jsonFile,$index){
        if (file_exists($jsonFile)){
            $jsonContent = file_get_contents($jsonFile);
            $data = json_decode($jsonContent,true);

            if(isset($data[$index])){
                $activity = $data[$index];
                $nom = htmlspecialchars($activity['nom']);
                $description = htmlspecialchars($activity['description']);
                $date = htmlspecialchars($activity['date']);

                echo" 
                <h2>Modifier l'activité</h2>
                <form method = 'POST' action =''>
                    <input type='hidden' name='index' value='{$index}'>
                    <label for = 'nom'> Nom : </label>
                    <input type='text' id='nom' name = 'nom' value = '{$nom}' required> <br><br>

                    <label for = 'description'> Description : </label>
                    <input type = 'text' id='description' name='description' value='{$description}' required> <br><br>

                    <label for = 'date'>Date : </label>
                    <input type ='date' id='date' name='date' value ='{$date}' required> <br><br>
                    
                    <input type= 'submit' name='update' value='Mettre à jour'>
                </form>
                ";
            }
        }

    }

    function updateData ($jsonFile, $index, $newNom, $newDesc, $newDate){
        if (file_exists($jsonFile)){
            $jsonContent = file_get_contents($jsonFile);
            $data = json_decode($jsonContent,true);
            if(isset($data[$index])){
                $data[$index]['nom'] = htmlspecialchars($newNom);
                $data[$index]['description'] = htmlspecialchars($newDesc);
                $data[$index]['date'] = htmlspecialchars($newDate);

                file_put_contents($jsonFile, json_encode($data,JSON_PRETTY_PRINT));
                
                echo "<p> L'activité a été mise à jour avec succèes.</p>";
            }
        }
    }

    function deleteData($jsonFile, $index){
        if(file_exists($jsonFile)){
            $jsonContent = file_get_contents($jsonFile);
            $data = json_decode ($jsonContent, true);
            if(isset($data[$index])){
                unset($data[$index]);
                $data = array_values($data);
                file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT));
                echo"<p>L'activité a été supprimer avec succès.</p>";
            }
        }
    }

    /*if(isset($_POST['Create'])){
        create_file("info2.json");
    }else if(isset($_POST['Other'])){
        if($_POST['action'] == 1){
            read_file("info2.json");
        }else if($_POST['action'] == 2){
            update_file("info2.json");
        }else if($_POST['action'] == 3){
            delete_file("info2.json");
        }
    }   */
    

?>

