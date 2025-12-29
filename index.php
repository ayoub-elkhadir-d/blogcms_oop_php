<?php
$users = [
  new Author(1, "AminaAdmin", "amina@mediapress.com", "admin_2025", [
    new Article(
      1,
      "Bienvenue sur BlogCMS",
      "Voici le premier article de notre système en ligne de commande.",
      5,
    ),
    new Article(
      2,
      "POO en PHP",
      "Introduction simple à la programmation orientée objet en PHP.",
      4,
    ),
  ]),
  new Admin(
    2,
    "ThomasEdit",
    "thomas@mediapress.com",
    "editor_pass",

    [
      new Article(
        3,
        "Édition de contenu",
        "Comment éditer efficacement des articles existants.",
        2,
      ),
    ],
  ),
  new Author(3, "LeaWriter", "lea@mediapress.com", "writing_fun", [
    new Article(
      4,
      "Écriture créative",
      "Conseils pour améliorer votre écriture.",
      3,
    ),
    new Article(
      5,
      "Discipline d’écriture",
      "Écrire un peu chaque jour fait la différence.",
      3,
    ),
  ]),
  new Author(4, "MarcoDev", "marco@external.com", "guest_pwd", [
    new Article(
      6,
      "Développement invité",
      "Retour d’expérience d’un développeur externe.",
      1,
    ),
  ]),
];

function count_articles($users): int
{
  $count = 0;
  foreach ($users as $user) {
    $count += count($user->getUserArticles());
  }
  return $count;
}

function display_categories_list($categories) {
    echo "\n--- Available Categories ---\n";
    foreach ($categories as $cat) {
        echo "ID: " . $cat->getId() . " | Name: " . $cat->getName() . "\n";
    }
    echo "----------------------------\n";
}

$categories = [
  new Category(1, "Technologie", null),
  new Category(2, "Corporate", null),
  new Category(3, "Hardware", null),
  new Category(4, "Programmation", 1),
  new Category(5, "Réseaux", 1),
  new Category(6, "PHP", 4),
  new Category(7, "JavaScript", 4),
  new Category(8, "Événements", 2),
];

// mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm*/
// **********************  USER  *************************
// mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm*/
class User
{
  protected int $id;
  protected string $username;
  protected string $email;
  protected string $password;
  protected array $articles = [];
  protected DateTime $created_at;
  protected DateTime $lastLogin;

  private $current_user = null;
  private $Role = "Visitor";

  public function __construct(
    $id,
    $username,
    $email,
    $password,
    array $articles = [],
  ) {
    $this->id = $id;
    $this->username = $username;
    $this->email = $email;
    $this->password = $password;
    $this->created_at = new DateTime();
    $this->lastLogin = new DateTime();
    $this->articles = $articles;
  }

  public function readArticle($id, $articles)
  {
    foreach ($articles as $article) {
      if ($article->getId() === $id) {
        return $article->getContent();
      }
    }
  }
  public function read_Article_of_user()
  {
    if ($this->isLoggedIn()) {
      foreach ($this->getCurrentUser()->articles as $art) {
        print_r($art->getContent());
      }
    }
  }

  public function writeComment(int $id, Comment $comment, array $users)
  {
    foreach ($users as $user) {
      foreach ($user->getUserArticles() as $article) {
        if ($article->getId() === $id) {
          $article->addComment($comment);
          echo "comment added successfully\n";
          return;
        }
      }
    }

    echo "article not found\n";
  }

  public function write_article(Article $art)
  {
    if ($this->isLoggedIn()) {
      array_push($this->getCurrentUser()->articles, $art);
    }
  }

  public function getId()
  {
    return $this->id;
  }
  public function getRole()
  {
    return $this->Role;
  }
  public function getUserArticles()
  {
    return $this->articles;
  }
public function addArticle(Article $article)
{
    $this->articles[] = $article;
}

  public function getUsername()
  {
    return $this->username;
  }

  public function getEmail()
  {
    return $this->email;
  }

  public function getRoleName()
  {
    return get_class($this);
  }


  public function login(array $array_users, $user_, $pass)
  {
    foreach ($array_users as $user) {
      if ($user->username === $user_ && $user->password === $pass) {
        $this->Role = get_class($user);
        $this->current_user = $user;
        return true;
      }
    }
  }
  public function getCurrentUser()
  {
    return $this->current_user;
  }

  public function isLoggedIn()
  {
    return $this->current_user !== null;
  }
  public function logout()
  {
    $this->current_user = null;
    $this->Role = "Visitor";
    return;
  }
  public function get_all_articles($users)
  {
    foreach ($users as $user) {
      foreach ($user->getUserArticles() as $article) {
        echo $article->getContent();
      }
    }
  }
}

/*mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm*
 **********************  AUTHOR  ***********************
 mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm*/
class Author extends User
{
  public function createArticle(
    $id,
    string $title,
    string $content,
    Category $category,
  ) {
    return new Article($id, $title, $content, $category->getId());
  }



  public function updateOwnArticle(
    Article $article,
    string $title,
    string $content,
  ) {
    $article->setTitle($title);
    $article->setContent($content);
  }
}

/*mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm
 ********************** ARTICLE ***********************
 mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm*/
class Article
{
  private int $id;
  private string $title;
  private string $content;
  private int $category_id;
  private array $comments = [];
  private DateTime $created_at;
  private DateTime $updated_at;

  public function __construct($id, $title, $content, $category_id)
  {
    $this->id = $id;
    $this->title = $title;
    $this->content = $content;
    $this->category_id = $category_id;
    $this->created_at = new DateTime();
    $this->updated_at = new DateTime();
  }

  public function addCategory(int $category_id)
  {
    $this->category_id = $category_id;
  }

  public function getId()
  {
    return $this->id;
  }

  public function addComment(Comment $comment)
  {
    $this->comments[] = $comment;
  }

  public function getComments(): array
  {
    return $this->comments;
  }

  public function getContent()
  {
    $count_cmt = count($this->comments);

    return "
        ============== {$this->id} =================
        | {$this->title}
        ===================================
        | {$this->content}
        ===================================
        Comments: {$count_cmt}
        ===================================\n";
  }

  public function setTitle($title)
  {
    $this->title = $title;
  }
  public function setContent($content)
  {
    $this->content = $content;
  }
    public function removeCommentById($commentId)
  {
      foreach ($this->comments as $key => $comment) {
          if ($comment->getId() === $commentId) {
              unset($this->comments[$key]);
             
              $this->comments = array_values($this->comments);
              return true;
          }
      }
      return false;
  }
}

/*mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm*
 ********************** CATEGORY **********************
 mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm*/
class Category
{
    private int $id;
    private string $name;
    private ?int $parentCategoryId;

    public function __construct($id, $name, $parent = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->parentCategoryId = $parent;
    }

    public function getId()
    {
        return $this->id;
    }


    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}

/*mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm*
 ********************** COMMENT ************************
 mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm*/
class Comment
{
  private int $id;
  private string $content;
  private int $user_id;
  private int $article_id;

  public function __construct($id, $content, $user_id, $article_id)
  {
    $this->id = $id;
    $this->content = $content;
    $this->user_id = $user_id;
    $this->article_id = $article_id;
  }

   public function getId() {
      return $this->id;
  }
  
  public function getContent() {
      return $this->content;
  }

}

/*mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm*
 ******************** MODERATION ***********************
 mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm*/
class Moderation extends User
{
    public function createAssignArticle(
        Article $article,
        int $authorId,
        array &$users
    ) {
        if (!($this instanceof Moderation)) {
            echo "Access denied\n";
            return;
        }
        foreach ($users as $user) {
            if ($user->getId() === $authorId) {
                $user->addArticle($article);
                echo "Article assigned to Author ID {$authorId} successfully\n";
                return;
            }
        }
        echo "Author not found\n";
    }


   
    public function deleteArticle(int $articleId, array &$users)
    {
        foreach ($users as $user) {
            foreach ($user->getUserArticles() as $key => $article) {
                if ($article->getId() === $articleId) {
                    unset($user->getUserArticles()[$key]);
                    echo "Article deleted successfully\n";
                    return;
                }
            }
        }
        echo "Article not found\n";
    }

      public function deleteComment(int $commentId, array &$users)
    {
        foreach ($users as $user) {
            foreach ($user->getUserArticles() as $article) {
                if ($article->removeCommentById($commentId)) {
                    echo "Comment deleted successfully from Article ID: " . $article->getId() . "\n";
                    return;
                }
            }
        }
        echo "Comment ID not found.\n";
    }
 
    public function updateArticle(
        int $articleId,
        string $title,
        string $content,
        array $users
    ) {
        foreach ($users as $user) {
            foreach ($user->getUserArticles() as $article) {
                if ($article->getId() === $articleId) {
                    $article->setTitle($title);
                    $article->setContent($content);
                    echo "Article updated successfully\n";
                    return;
                }
            }
        }
        echo "Article not found\n";
    }

 


    public function createCategory(array &$categories, Category $category)
    {
        $categories[] = $category;
        echo "Category created successfully: " . $category->getName() . "\n";
    }

    public function deleteCategory(array &$categories, int $id)
    {
        foreach ($categories as $key => $cat) {
            if ($cat->getId() === $id) {
                unset($categories[$key]);
                echo "Category deleted successfully\n";
                return;
            }
        }
        echo "Category not found\n";
    }

    public function updateCategory(array $categories, int $id, string $name)
    {
        foreach ($categories as $cat) {
            if ($cat->getId() === $id) {
                $cat->setName($name);
                echo "Category updated successfully to: $name\n";
                return;
            }
        }
        echo "Category not found\n";
    }

   
    public function updateComment(Comment $comment, string $content)
    {
        $comment->update($content);
        echo "Comment updated successfully\n";
    }


}
/*mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm*
 ************************ Editor ************************
 mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm*/

class Editor extends Moderation
{}

/*mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm*
 ************************ ADMIN ************************
 mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm*/
class Admin extends Moderation
{
    private bool $isSuperAdmin = true;

    public function createUser(array &$users, User $user)
    {
        $users[] = $user;
        echo "User created successfully\n";
    }

    public function deleteUser(array &$users, int $id)
    {
        foreach ($users as $key => $user) {
            if ($user->getId() === $id) {
                unset($users[$key]);
                echo "User deleted successfully\n";
                return;
            }
        }
        echo "User not found\n";
    }

    
    public function displayAllUsers(array $users)
    {
        echo "\n------ USERS LIST ------\n";
        foreach ($users as $user) {
            echo "ID: " . $user->getId() .
                 " | Username: " . $user->getUsername() .
                 " | Role: " . get_class($user) . "\n";
        }
        echo "------------------------\n";
    }
}


/*===================================================*/
// $obj = new Admin();

$obj = new User(1, "ppp", "amina@mediapress.com", "adminppp_2025", [
  new Article(
    6,
    "Développement invité",
    "Retour d’expérience d’un développeur externe.",
    1,
  ),
]);
$art = new Article(0, "test", "test", 0);

while (true) {
  $Role = $obj->getRole();
  $menu = $Role;

  if ($menu === "Author" && $obj->isLoggedIn()) {
    echo "\n====== Author =========\n";
    echo "1) Display all articles\n";
    echo "2) Write comment\n";
    echo "3) Manage my article\n";
    echo "0) Logout\n";
    echo "========================\n";
    $choix = readline("Enter : ");
    switch ($choix) {
      case 1:
        popen("cls", "w");
        $obj->get_all_articles($users);
        break;
      case 2:
        popen("cls", "w");
        $obj->get_all_articles($users);
        $id = (int) readline("Enter article id : ");
        $content = readline("Enter content : ");
        $obj->writeComment($id, new Comment(17, $content, 4, $id), $users);
        break;
      case 3:
        
        popen("cls", "w");
        echo "\n====== Article Manage =========\n";
        echo "1) Display my articles\n";
        echo "2) Write Article\n";
        echo "3) Update Article\n";
        echo "0) Exit";
        echo "========================\n";

        $choix = readline("Enter : ");
        switch ($choix) {
          case 1:
            popen("cls", "w");
            $obj->read_Article_of_user();
            break;
          case 2:
            popen("cls", "w");
             echo "\n----------- Choisir Category ----------\n";
           display_categories_list($categories);
                        
          $titre = readline("Enter titre : ");
          $desc = readline("Enter description: ");
          $catId = (int)readline("Enter Category ID form list: ");

          $obj->write_article(
              new Article(count_articles($users) + 1, $titre, $desc, $catId)
          );
          echo "Article created successfully!\n";
            break;
           case 3:
            
           popen("cls", "w");
            echo "\n--- Your Articles ---\n";
            $obj->read_Article_of_user(); 
            $idUpdate = (int) readline("Enter Article ID to update: ");
            $foundArticle = null;
            $myArticles = $obj->getCurrentUser()->getUserArticles();
            
            foreach ($myArticles as $art) {
                if ($art->getId() === $idUpdate) {
                    $foundArticle = $art;
                    break;
                }
            }
            if ($foundArticle !== null) {
                popen("cls", "w");
                echo "--- Updating Article ID: $idUpdate ---\n";
                $newTitle = readline("Enter new Title: ");
                $newContent = readline("Enter new Description: ");
                $obj->getCurrentUser()->updateOwnArticle($foundArticle, $newTitle, $newContent);
                echo "Article updated successfully!\n";
            } else {
                popen("cls", "w");
                echo " Article ID not found \n";
            }
            break;

          case 0:
            $key = "";
            break;
        }

        break;

      case 0:
        $obj->logout();

        break;
    }
  } elseif ($menu === "Admin") {
    echo "\n====== Admin =========\n";
    echo "1) Display all articles\n";
    echo "2) Write comment\n";
    echo "3) Manage articles\n";
    echo "4) Manage users\n";
    echo "5) Manage Categories\n";
    echo "6) Manage comments\n";
    echo "0) Logout\n";
    echo "======================\n";

    $choix = readline("Enter : ");
    switch ($choix) {
      case 1:
        popen("cls", "w");
        echo "\n----------- All article ----------\n";
        $obj->get_all_articles($users);
        break;
      case 2:
        popen("cls", "w");
         echo "\n----------- Write  Comment ----------\n";
        $obj->get_all_articles($users);
        $id = (int) readline("Enter article id : ");
        $content = readline("Enter content : ");
        $obj->writeComment($id, new Comment(17, $content, 4, $id), $users);
        break;
      case 3:
        case 3:
            if ($obj->getCurrentUser() instanceof Moderation) {
                popen("cls", "w");
                echo "\n====== Manage Articles ======\n";
                echo "1) Assign article to author\n";
                echo "2) Update article\n";
                echo "3) Delete article\n";
                echo "0) Back\n";
                echo "==============================\n";

                $choiceArt = readline("Enter : ");

                switch ($choiceArt) {

                
                    case 1:
                            popen("cls", "w");
                            echo "\n----------- Creat article ----------\n";
                            foreach($users as $user){
                             echo "ID: " . $user->getId() . "|  Username: ".$user->getUsername()."\n";
                            }
                            $authorId = (int) readline("Enter Author ID : ");
                            $title = readline("Article title : ");
                            $content = readline("Article content : ");
                            
                      
                            display_categories_list($categories);
                            $category = (int) readline("Category ID : ");

                            $newArticle = new Article(
                                count_articles($users) + 1,
                                $title,
                                $content,
                                $category
                            );
                            $obj->getCurrentUser()->createAssignArticle($newArticle, $authorId, $users);
                           
                        break;

      
                    case 2:
                        popen("cls", "w");
                        echo "\n----------- Update article ----------\n";
                        $obj->get_all_articles($users);

                        $articleId = (int) readline("Enter Article ID : ");
                        $newTitle = readline("New title : ");
                        $newContent = readline("New content : ");

                        $obj->getCurrentUser()
                            ->updateArticle($articleId, $newTitle, $newContent, $users);
                        break;

                
                    case 3:
                       popen("cls", "w");
                       echo "\n----------- Delet Article ----------\n";
                        $obj->get_all_articles($users);

                        $articleId = (int) readline("Enter Article ID to delete : ");
                        $obj->getCurrentUser()
                            ->deleteArticle($articleId, $users);
                        break;

                    case 0:
                        break;
                }
            } else {
                echo "Access denied\n";
            }
            break;

        break;

      case 4:
        if ($obj->getCurrentUser() instanceof Admin) {

        echo "\n====== Manage Users ======\n";
        echo "1) Display all users\n";
        echo "2) Create user\n";
        echo "3) Delete user\n";
        echo "0) Back\n";
        echo "==========================\n";

        $choiceUser = readline("Enter : ");

        switch ($choiceUser) {
            case 1:
               popen("cls", "w");
               echo "\n----------- All Users ----------\n";
                $obj->getCurrentUser()->displayAllUsers($users);
                break;

            case 2:
                popen("cls", "w");
                echo "\n----------- Creat User ----------\n";
                $id = count($users) + 1;
                $username = readline("Username : ");
                $email = readline("Email : ");
                $password = readline("Password : ");
                $role = readline("Role (Author/Admin) : ");

                if ($role === "Admin") {
                    $newUser = new Admin($id, $username, $email, $password);
                } else {
                    $newUser = new Author($id, $username, $email, $password);
                }

                $obj->getCurrentUser()->createUser($users, $newUser);
                break;

            case 3:
                popen("cls", "w");
                echo "\n----------- Delet User ----------\n";
                $obj->getCurrentUser()->displayAllUsers($users);
                $idDelete = (int) readline("Enter user id to delete : ");
                $obj->getCurrentUser()->deleteUser($users, $idDelete);
                break;
        }

                }
         break;

        case 5:
                     popen("cls", "w");
                    echo "\n====== Manage Categories ======\n";
                    echo "1) List Categories\n";
                    echo "2) Add Category\n";
                    echo "3) Delete Category\n";
                    echo "4) Update Category\n";
                    echo "0) Back\n";
                    echo "===============================\n";

                    $catChoice = readline("Enter : ");

                    switch ($catChoice) {
                        case 1:
                             popen("cls", "w"); 
                             echo "\n----------- all categoryes ----------\n";
                            display_categories_list($categories);
                            break;
                        case 2:
                            popen("cls", "w");
                            echo "\n----------- creat category ----------\n";
                            $catName = readline("Enter Category Name: ");
                            $newId = end($categories)->getId() + 1; 
                            $newCat = new Category($newId, $catName);
                            $obj->getCurrentUser()->createCategory($categories, $newCat);
                            break;
                        case 3:
                            popen("cls", "w");
                            echo "\n----------- delet category ----------\n";
                            display_categories_list($categories);
                            $delId = (int)readline("Enter Category ID to delete: ");
                            $obj->getCurrentUser()->deleteCategory($categories, $delId);
                            break;
                        case 4:
                            popen("cls", "w");
                            echo "\n----------- Update category ----------\n";
                            display_categories_list($categories);
                            $updId = (int)readline("Enter Category ID to update: ");
                            $newName = readline("Enter new name: ");
                            $obj->getCurrentUser()->updateCategory($categories, $updId, $newName);
                            break;

                        case 0: break;
                    
                }
                break;
              case 0:
       $obj->logout();

        break;
        case 6:
         popen("cls", "w");
         echo "\n====== Manage Comments ======\n";
         echo "1) List All Comments\n";
         echo "2) Delete Comment\n";
         echo "0) Back\n";
         echo "=============================\n";
         
         $cmtChoice = readline("Enter: ");
         switch($cmtChoice) {
             case 1:
                 echo "\n--- All System Comments ---\n";
                 foreach($users as $u) {
                     foreach($u->getUserArticles() as $a) {
                         foreach($a->getComments() as $c) {
                             echo "ID: " . $c->getId() . " | Article: " . $a->getId() . " | Content: " . $c->getContent() . "\n";
                         }
                     }
                 }
                 echo "---------------------------\n";
                 break;
             case 2:
                 $cmtId = (int)readline("Enter Comment ID to delete: ");
                 $obj->getCurrentUser()->deleteComment($cmtId, $users);
                 break;
             case 0: break;
         }
         break;

    }
  } else {
    while ($menu === "Visitor") {
      echo "\n======visitor=========\n";
      echo "1) Display all articles\n";
      echo "2) Write comment\n";
      echo "0) Se Connect\n";
      echo "========================\n";

      $choix = readline("Enter : ");
      switch ($choix) {
        case 1:
          popen("cls", "w");
          echo "\n----------- All articles ----------\n";
          $obj->get_all_articles($users);
          break;
        case 2:
          popen("cls", "w");
          echo "\n----------- write comment ----------\n";
          $obj->get_all_articles($users);
          $id = (int) readline("Enter article id : ");
          $content = readline("Enter content : ");
          $obj->writeComment($id, new Comment(17, $content, 4, $id), $users);
          break;

        case 0:
          popen("cls", "w");
          echo "\n----------- log in ----------\n";
          $user_name = readline("Enter user name : ");
          $password = readline("Enter password : ");
          if ($obj->login($users, $user_name, $password)) {
            $menu = $obj->getRole();
            print_r($menu);
          } else {
            echo "incorect";
          }
          break;
      }
    }
  }
}


?>
