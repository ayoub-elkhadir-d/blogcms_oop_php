<?php
$users = [
    new Author(1,"AminaAdmin","amina@mediapress.com","admin_2025",null,new DateTime('2025-01-01 08:00:00'),new DateTime('2025-12-24 10:30:00')),
    new Author(2,"ThomasEdit","thomas@mediapress.com", "editor_pass",null, new DateTime('2025-02-15 09:15:00'),new DateTime('2025-12-20 16:45:00') ),
    new Author( 3,"LeaWriter", "lea@mediapress.com","writing_fun",null, new DateTime('2025-03-10 11:00:00'),new DateTime('2025-12-25 09:00:00')),
    new Author(4, "MarcoDev","marco@external.com","guest_pwd",null,new DateTime('2025-05-20 14:00:00'),new DateTime('2025-12-25 09:00:00'))
];
$articles = [
    new Article(1,"Bienvenue sur BlogCMS","Voici le premier article de notre système en ligne de commande.",5),
    new Article(2, "Maîtriser la POO PHP","La programmation orientée objet permet de structurer son code proprement.",3 ),
    new Article(3, "Maintenance Serveur","Une maintenance est prévue ce soir à 22h pour optimiser les performances.",1)
];
$categories = [
    
    new Category(1, "Technologie", null),
    new Category(2, "Corporate", null),
    new Category(3, "Hardware", null),
    new Category(4, "Programmation", 1),
    new Category(5, "Réseaux", 1),
    new Category(6, "PHP", 4),
    new Category(7, "JavaScript", 4),
    new Category(8, "Événements", 2)
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

    public function __construct($id, $username, $email, $password)
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->created_at = new DateTime();
        $this->lastLogin = new DateTime();
    }

    public function readArticle($id, $articles)
    {
        foreach($articles as $article){
            if($article->getId() === $id){
         return $article->getContent($id, $articles);           
         }
        } 

        
    }

    public function writeComment($id,$array_article, string $content)
    {
        foreach($array_article as $article){
            if($article->getId() === $id){
                $article->addComment(new Comment(1, $content, $this->id, $article->getId()));
            }

        }
     
    }

    public function getId()
    {
        return $this->id;
    } 

    public function  login (array $array_users,$user_,$pass){
        foreach($array_users as $user){
              if ($user->username === $user_ && $user->password === $pass) {
            return true;
            $is_login = true;
           }  
        }
    }
      
public function get_all_articles($articles){
    foreach($articles as $article){
        echo $this->readArticle($article->getId(), $articles);
    }
}

}



/*mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm*
**********************  AUTHOR  ***********************
mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm*/
class Author extends User
{
    public function createArticle($id,string $title, string $content, Category $category)
    {
        return new Article($id, $title, $content, $category->getId());
    }

    public function deleteOwnArticle($articles,$id)
    {
         foreach($articles as $article){
            if($article->getId() === $id){
             unset($articless[$id]);
         }
        } 
    }

    public function updateOwnArticle(Article $article, string $title, string $content)
    {
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

    public function publish(){}
    public function unpublish(){}
    public function archiver(){}

    public function getId()
    {
        return $this->id;
    }
     public function addComment(Comment $comment)
    {
        $this->comments[] = $comment;
    }

  public function getContent()
        {
            $count_cmt = count($this->comments);

            return "============== {$this->id} =================
        | {$this->title}
        ===================================
        | {$this->content}
        ===================================
        Comments: {$count_cmt}
        ===================================\n";
        }


    public function setTitle($title) { $this->title = $title; }
    public function setContent($content) { $this->content = $content; }
    
     public function get_all_articles($articles){
            foreach($articles as $art){
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

    public function delete(){
        
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
class Moderation
{
    public function createAssignArticle(){}
    public function deleteArticle(Article $article) {}
    public function updateArticle(Article $article) {}
    public function publierArticle(Article $article) {}
    public function archiveArticle(Article $article) {}

    public function createCategory() {}
    public function deleteCategory(Category $category) {}
    public function updateCategory(Category $category) {}

    public function updateComment(Comment $comment, string $content)
    {
        $comment->update($content);
    }

    public function deleteComment(Comment $comment)
    {
        $comment->delete();
    }
}


/*mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm*
************************ ADMIN ************************
mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm*/
class Admin extends Moderation
{
    private bool $isSuperAdmin;

    public function createUser(User $user) {}
    public function deleteUser(User $user) {}

}



/*===================================================*/
$admin = new Author(1,"AminaAdmin","amina@mediapress.com","admin_2025",null,new DateTime('2025-01-01 08:00:00'),new DateTime('2025-12-24 10:30:00'));
 $art= new Article(1,"Bienvenue sur BlogCMS","Voici le premier article de notre système en ligne de commande.",5);
while(true){
echo "================== \n 1)display all articles \n 2) write comment \n 3) se connect \n";
$choix = readline('Enter : ');

switch ($choix) {
   case 1:
    $admin->get_all_articles($articles);
       break;
   case 2:
       $choix_id = (int) readline('Enter id de post : ');
       $content = readline('Enter content de comment : ');
       $admin -> writeComment($choix_id,$articles,$content);
       break;
   case 3:
       echo "Your favorite color is green!";
       break;

}
}

// // echo $admin -> login($users,"AminaAdmin","admin_2025");
// $admin -> writeComment(1,$articles,"hi");

// array_push($articles,$admin-> createArticle(count($articles)+1,"title","article content4",new Category(8, "Événements", 2)));

// echo $admin ->readArticle(1,$articles);






?>
