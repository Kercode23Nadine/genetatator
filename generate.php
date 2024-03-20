

<!doctype html>
<html lang="fr">

<head>
    <title>GENERATATOR</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="asset/style/style.css">
    <script src="asset/script/script.js" defer></script>
    <header class="tete">
        <h1>GENERATATOR</h1>
    </header>
    <main class="container">

<!-- ===========Formualaire Generation mot de passe================== -->
        <form class="form" action="generate.php" method="POST">
          <div class="generation">
            <input id="site" class="password" name="site" type="text" placeholder="Site">
            <input id="pseudo" class="password" name="pseudo" type="text" placeholder="Pseudo">
            
            <input id="password" class="password" name="password" type="text" value="<?php generate_password() ?>">
            <button  class="btn generate" name="generate" type="submit">Generate</button>
            <button id="save" class="btn save">Save</button>
          </div>
            <button id="copy" class="btn copy" type="button">Copy</button>
            <!-- <input type="number" min="8" id="length" class="length" name="length" placeholder="Length"> -->
        </form>

<!-- ====================Tableau des mots de passes recherché dans la base de donnée=========================== -->
<?php
function connectPDO()
{
    $host = "localhost";
    $dbname = "sprint";
    $user = "root";
    $password = "";

    try {
        $conn = new PDO("mysql:host=" . $host . ";dbname=" . $dbname, $user, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $conn;
    } catch (PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage() . " ";
        return null;
    }
}
function addPassword($site,$pseudo,$password){
$conn = connectPDO();

if (!$conn) {
    echo "Erreur de base de données : Connexion non établie.";
    return false;
}
$site=htmlspecialchars($site);
$pseudo=htmlspecialchars($pseudo);
$password=htmlspecialchars($password);

if (!preg_match("/^[a-zA-Z0-9 ]{2,50}$/", $pseudo)) {
    echo "Erreur : pseudo invalide.";
    return false;
}
if (!preg_match('/^(?:www\.)?[-a-zA-Z0-9@:%.\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b(?:[-a-zA-Z0-9()@:%_\+.~#?&\/=]*)$/', $site)) {
    echo "Erreur : site invalide.";
    return false;
}

$request = 'INSERT INTO coffre (site, pseudo, password) VALUES (:site, :pseudo, :password)';
        $statement = $conn->prepare($request);
        $statement->execute(['site' => $site, 'pseudo' => $pseudo, 'password' => $password]);
        echo 'Coffre fort mis à jour !';
        return true; // Indiquer que l'insertion s'est faite avec succès
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupération des données soumises via le formulaire
        $site = $_POST['site'] ?? '';
        $pseudo = $_POST['pseudo'] ?? '';
        $password = $_POST['password'] ?? '';

        // Valider les données
        if (empty($site) || empty($pseudo)) {
            echo "Erreur : Tous les champs sont requis.";
            return;
        }

        // Afficher le formulaire avec les messages d'erreur si la validation échoue
        if (!preg_match("/^[a-zA-Z0-9 ]{2,50}$/", $pseudo)) {
            echo "Erreur : Nom du produit invalide.";
            return;
        }


        // Si la validation réussit, effectuer le traitement
      if(  addPassword($site, $pseudo,$password)){ 
            echo 'Traitement effectué avec succès.';
      }else {
        echo 'echec de la requête';
      }

    }
// Essaie de connection


try{
  $conn = connectPDO();
  // Defini le mode d'erreur sur Exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo'Connexion reussie <br>';

  //  preparation de la requete
  $requete = $conn->prepare("SELECT * FROM  coffre where 1");

  // execution de la requete
  $requete->execute();

  $result= $requete->fetchAll(PDO::FETCH_ASSOC);

  if ($result) {
      // Affichage des résultats dans un tableau HTML
      echo '<div class="tab-container"><table class ="mdptable">';
      echo '<tr ><th class="mdptab">Site</th><th class="mdptab">Pseudo</th><th class="mdptab">Mot de passe</th>';
      foreach ($result as $row) {
          echo '<tr>';
          echo '<td class="column">'.$row['site'].'</td>';
          echo '<td class="column pseudo">'.$row['pseudo'].'</td>';
          echo '<td class="column pseudo">'.$row['password'].'</td>';
          echo '</tr>';
      }
      echo '</table> </div>';
  } else {
      echo 'Aucun résultat trouvé.';
  }
}

// Si la connexion échoue 
catch(PDOException $error){
  echo'Erreur de connexion : '.$error->getMessage();
}
?>
    </main>
    <footer class="pied">
    </footer>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->

    </body>

<?php
function generate_password()
{
    $chaine = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ?/!@$";
    $chaine = str_shuffle($chaine);
    $chaine = substr($chaine, 0, 8);
    echo $chaine;
}
?>
</html>