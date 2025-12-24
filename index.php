<?php

/*****************************************************
**********************  USER  *************************
*****************************************************/

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

    public function readArticle(Article $article)
    {
        return $article->getContent();
    }

    public function writeComment(Article $article, string $content)
    {
        return new Comment(null, $content, $this->id, $article->getId());
    }

    public function getId()
    {
        return $this->id;
    }
}

/*****************************************************
**********************  AUTHOR  ***********************
*****************************************************/
class Author extends User
{
    protected string $bio;

    public function createArticle(string $title, string $content, Category $category)
    {
        return new Article(null, $title, $content, $category->getId());
    }

    public function deleteOwnArticle(Article $article)
    {
        return true;
    }

    public function updateOwnArticle(Article $article, string $title, string $content)
    {
        $article->setTitle($title);
        $article->setContent($content);
        return true;
    }
}

/*****************************************************
********************** ARTICLE ***********************
*****************************************************/
class Article
{
    private ?int $id;
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

    public function addCategory(int $category_id): bool
    {
        $this->category_id = $category_id;
        return true;
    }

    public function removeCategory(): bool
    {
        $this->category_id = 0;
        return true;
    }

    public function publish(): bool { return true; }
    public function unpublish(): bool { return true; }
    public function archiver(): bool { return true; }

    public function getId()
    {
        return $this->id;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setTitle($title) { $this->title = $title; }
    public function setContent($content) { $this->content = $content; }
}

/*****************************************************
********************** CATEGORY ***********************
*****************************************************/
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

    public function addSubCategory(Category $category): bool
    {
        $this->children[] = $category;
        return true;
    }

    public function getChildren(): array
    {
        return $this->children;
    }

    public function getParent(): ?int
    {
        return $this->parentCategoryId;
    }

    public function update(array $data): bool
    {
        if (isset($data['name'])) $this->name = $data['name'];
        return true;
    }

    public function delete(): bool
    {
        return true;
    }

    public function getId()
    {
        return $this->id;
    }
}

/*****************************************************
********************** COMMENT ************************
*****************************************************/
class Comment
{
    private ?int $id;
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
        return true;
    }

    public function delete()
    {
        return true;
    }
}

/*****************************************************
******************** MODERATION ***********************
*****************************************************/
class Moderation
{
    public function createAssignArticle() {}
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

/*****************************************************
*********************** EDITOR ************************
*****************************************************/
class Editor extends Moderation
{
    protected string $moderationLevel;
}

/*****************************************************
************************ ADMIN ************************
*****************************************************/
class Admin extends Moderation
{
    private bool $isSuperAdmin;

    public function createUser(User $user) {}
    public function deleteUser(User $user) {}
    public function assignRole(User $user, string $role) {}
}

?>
