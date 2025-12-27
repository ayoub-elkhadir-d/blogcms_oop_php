<?php
$users = [
    new Author(
        1,
        "AminaAdmin",
        "amina@mediapress.com",
        "admin_2025",
        [new Article(1,"Bienvenue sur BlogCMS","Voici le premier article de notre système en ligne de commande.",5),
    new Article( 2,"POO en PHP","Introduction simple à la programmation orientée objet en PHP.", 4 ),]
    ),
    new Admin(
        2,
        "ThomasEdit",
        "thomas@mediapress.com",
        "editor_pass",
        
        [new Article( 3, "Édition de contenu","Comment éditer efficacement des articles existants.",2)]
    ),
    new Author(
        3,
        "LeaWriter",
        "lea@mediapress.com",
        "writing_fun",
        [new Article(4,"Écriture créative","Conseils pour améliorer votre écriture.",3),
        new Article(5,"Discipline d’écriture","Écrire un peu chaque jour fait la différence.",3),]
    ),
    new Author(
        4,
        "MarcoDev",
        "marco@external.com",
        "guest_pwd",
        [new Article(6,"Développement invité","Retour d’expérience d’un développeur externe.",1)]),
];

    function count_articles($users):int {
        $count = 0;
        foreach($users as $user){
           $count += count($user->getUserArticles());
        }
        return $count;
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
    private $Role= "Visitor";

    public function __construct($id, $username, $email, $password,array $articles = [])
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->created_at = new DateTime();
        $this->lastLogin = new DateTime();
        $this->articles = $articles;
    }
     
    public function readArticle($id,$articles)
    {
        foreach ($articles as $article) {
            if ($article->getId() === $id) {
                return $article->getContent();
            }
        }
    }
        public function read_Article_of_user()
    {
        if($this->isLoggedIn()){
           foreach($this->getCurrentUser()->articles as $art){
          print_r($art->getContent());

        }
        }
       
    }

public function writeComment(int $id, Comment $comment)
{
    if (!$this->isLoggedIn()) {
        echo "login first\n";
        return;
    }

    foreach ($this->getCurrentUser()->articles as $article) {
        if ($article->getId() === $id) {
            $article->addComment($comment);
           
            return;
        }
    }

   
}

    public function write_article(Article $art){
        if($this->isLoggedIn()){
       array_push($this->getCurrentUser()->articles,$art);
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


    public function login(array $array_users, $user_, $pass)
    {
        foreach ($array_users as $user) {
           
            if ($user->username === $user_ && $user->password === $pass) {
                $this -> Role=get_class($user); 
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
        $this-> Role= "Visitor";
        return;
    }
    public function get_all_articles($articles)
    {
        foreach ($articles as $article) {
            echo $this->readArticle($article->getId(), $articles);
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
        Category $category
    ) {
        return new Article($id, $title, $content, $category->getId());
    }

    public function deleteOwnArticle($articles, $id)
    {
        foreach ($articles as $article) {
            if ($article->getId() === $id) {
                unset($articless[$id]);
            }
        }
    }

    public function updateOwnArticle(
        Article $article,
        string $title,
        string $content
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

    public function publish()
    {
    }
    public function unpublish()
    {
    }
    public function archiver()
    {
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

    public function get_all_articles($articles)
    {
        foreach ($articles as $art) {
            readArticle($art->$id, $articles);
        }
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
    private array $children = [];

    public function __construct($id, $name, $parent = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->parentCategoryId = $parent;
    }

    public function update(array $data)
    {
    }

    public function delete()
    {
    }

    public function getId()
    {
        return $this->id;
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

    public function update(string $content)
    {
        $this->content = $content;
    }

    public function delete()
    {
    }
    // public function get_all_comments($id,$articles)
    // {
    //       foreach($articles as $art){
    //           if ($art->id === $id ) {
    //             foreach($art->comments as $cmt){
    //                echo  $cmt->content;
    //             }
    //      }
    //     }
    // }
}

/*mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm*
 ******************** MODERATION ***********************
 mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm*/
class Moderation extends User
{
    public function createAssignArticle()
    {
    
    }
    public function deleteArticle(Article $article)
    {
    }
    public function updateArticle(Article $article)
    {
    }
    public function publierArticle(Article $article)
    {
    }
    public function archiveArticle(Article $article)
    {
    }

    public function createCategory()
    {
    }
    public function deleteCategory(Category $category)
    {
    }
    public function updateCategory(Category $category)
    {
    }

    public function updateComment(Comment $comment, string $content)
    {
        $comment->update($content);
    }

    public function deleteComment(Comment $comment)
    {
        $comment->delete();
    }
}

/*mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm*
 ************************ ADMIN ************************
 mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm*/
class Admin extends Moderation
{
    private bool $isSuperAdmin;

    public function createUser(User $user)
    {
    }
    public function deleteUser(User $user)
    {
    }
}

/*===================================================*/
// $obj = new Admin();

$obj = new User(
    1,
    "ppp",
    "amina@mediapress.com",
    "adminppp_2025",
    [new Article(6,"Développement invité","Retour d’expérience d’un développeur externe.",1)]
);




while (true) {

    $Role = $obj -> getRole();
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
                    
                    $obj->get_all_articles($articles);
                    break;
                case 2:
                     
                    break;
                case 3:
                    echo "\n====== Article Manage =========\n";
                    echo "1) Display my articles\n";
                    echo "2) Write Article\n";
                    echo "3) Write Comment\n";
                    echo "0) Exit";
                    echo "========================\n";

                 $choix = readline("Enter : ");
                      switch($choix){
                        case 1:
                           $obj->read_Article_of_user();
                            break;
                        case 2:
                            $titre = readline("Enter titre : ");
                            $desc = readline("Enter description: ");
                            $obj->write_article(new Article(count_articles($users)+1,$titre,$desc,3));
                            break;
                        case 3:
                           $obj->writeComment(6 , new Comment(17, "kjhgf",4, 6));
                            break;
                        case 0;
                          $obj->read_Article_of_user();
                            break;
                      }
        
                    break;
                case 0:
                $obj->logout();
                     
                break;
            }
        

    }elseif($menu === "Admin"){
       
        echo "\n======Admin=========\n";
        echo "1) Display all articles\n";
        echo "2) Write comment\n";
        echo "3) Manage article\n";
        echo "3) Manage users\n";
        echo "0) Logout\n";
        echo "========================\n";
     $choix = readline("Enter : ");
            switch ($choix) {
                case 1:
                  popen('cls', 'w');
                    $obj->get_all_articles($articles);
                    break;
                case 2:
                  
                    break;
                case 3:
                   
                    break;
                case 0:
               print_r($obj->logout());
               
                break;
            }
    
    }else {
        
        while ($menu === "Visitor") {
            echo "\n======visitor=========\n";
            echo "1) Display all articles\n";
            echo "2) Write comment\n";
            echo "0) Se Connect\n";
            echo "========================\n";

            $choix = readline("Enter : ");
            switch ($choix) {
                case 1:
              
                   popen('cls', 'w');
                    $obj->get_all_articles($articles);
                     break;
                case 2:
                    $choix_id = (int) readline("Enter id de post : ");
                    $content = readline("Enter content de comment : ");
                    $obj->writeComment($choix_id, $articles, $content);
                    break;
             
                 
                case 0:
                    $user_name = readline("Enter user name : ");
                    $password = readline("Enter password : ");
                    if ($obj->login($users, $user_name, $password)) {
                         $menu = $obj -> getRole();
                         print_r($menu);
                    } else {
                        echo "incorect";
                    }
                    break;
            }
        }
    }
}

// echo $obj -> login($users,"AminaAdmin","admin_2025");
// $admin -> writeComment(1,$articles,"hi");

// array_push($articles,$admin-> createArticle(count($articles)+1,"title","article content4",new Category(8, "Événements", 2)));

// add user article


?>
