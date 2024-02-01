<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire HTML</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <h1 class="text-center py-2">Formulaire Intégration Articles</h1>

        <?php

        function slugify($phrase)
        {
            $slug = str_replace(' ', '-', $phrase);
            $slug = strtolower($slug);
            $slug = str_replace('titre-', '', $slug);

            return $slug;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $texte = htmlspecialchars(isset($_POST['texte']) ? $_POST['texte'] : '');
            $texte = filter_var($texte, FILTER_SANITIZE_STRING);
            $lines = explode("\n", $texte);
            $result = [];
            $inList = false;

            foreach ($lines as $line) {
                $line = trim($line);

                if (empty($line)) {
                    continue;
                } elseif (strpos($line, 'TITRE') === 0) {
                    if ($inList) {
                        $result[] = '</ul>';
                        $inList = false;
                    }
                    $result[] = '<h2 class="text-black text-bold text-200 my-5" id="' . slugify($line) . '">' . substr($line, 6) . '</h2>';
                } elseif (strpos($line, '-') === 0) {
                    if (!$inList) {
                        $result[] = '<ul class="list-styled list-bullet-primary">';
                        $inList = true;
                    }
                    $result[] = "<li>" . substr($line, 2) . "</li>";
                } else {
                    if ($inList) {
                        $result[] = '</ul>';
                        $inList = false;
                    }
                    $result[] = "<p>" . htmlspecialchars($line) . "</p>";
                }
            }

            if ($inList) {
                $result[] = '</ul>';
            }

            $result_text = implode("\n", $result);
            echo '<h2 class="text-center py-2">🎉 Résultat :</h2>';
            echo '<div class="d-flex justify-content-center"><textarea style="padding: 1rem; border-radius: 5px; width: 100%;" rows="15">' . $result_text . '</textarea></div>';
            $_SESSION['form_submitted'] = true;
        }
        ?>

        <div id="formContainer" class="text-center">
            <?php if (!isset($_SESSION['form_submitted']) || !$_SESSION['form_submitted']) : ?>
                <form method="post" action="" onsubmit="hideForm();">
                    <label class="py-3" for="texte">Copiez votre article :</label>
                    <br>
                    <textarea style="padding: 1rem; border-radius: 5px;" name="texte" rows="10" cols="40"></textarea>
                    <br>
                    <input class="btn btn-primary mt-3" type="submit" value="Convertir">
                </form>
            <?php endif; ?>
        </div>

        <?php if (isset($_SESSION['form_submitted']) && $_SESSION['form_submitted']) : ?>
            <div class="text-center">
                <button class="btn btn-primary mt-3" onclick="refreshPage();">Rafraîchir la page</button>
            </div>
        <?php endif; ?>

        <script>
            function hideForm() {
                document.getElementById("formContainer").style.display = "none";
            }
            function refreshPage() {
                window.location.href = window.location.href;
            }

        </script>
    </div>
</body>

</html>
