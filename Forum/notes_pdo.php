<?php



class Connection
{
    public $pdo = null;

    public function __construct()
    {
        try {
            $this->pdo = new PDO('mysql:server=localhost;dbname=unilink_database', 'root', '');
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "ERROR: " . $exception->getMessage();
        }

    }

    public function getNotes($userId, $searchQuery = '')
{
    // Base query to fetch notes for the user
    $sql = "SELECT * FROM notes WHERE user_id = :user_id";

    // If there's a search query, modify the query to filter notes by subject or content
    if ($searchQuery) {
        $sql .= " AND (subject LIKE :searchQuery OR content LIKE :searchQuery)";
    }

    $sql .= " ORDER BY created_at DESC"; // Sort by creation date

    // Prepare the statement
    $statement = $this->pdo->prepare($sql);

    // Bind the user ID
    $statement->bindValue(':user_id', $userId);

    // Bind the search query if it's provided
    if ($searchQuery) {
        $searchTerm = '%' . $searchQuery . '%';
        $statement->bindValue(':searchQuery', $searchTerm);
    }

    // Execute the statement
    $statement->execute();

    // Return the results
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

    


    public function getNotesByUserId($user_id)
{
    $statement = $this->pdo->prepare("SELECT * FROM notes WHERE user_id = :user_id ORDER BY created_at DESC");
    $statement->bindValue(':user_id', $user_id);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

public function addNote($note)
{
    $statement = $this->pdo->prepare("INSERT INTO notes (subject, content, created_at, user_id)
                                      VALUES (:subject, :content, NOW(), :user_id)");
    $statement->bindValue('subject', $note['subject']);
    $statement->bindValue('content', $note['content']);
    $statement->bindValue('user_id', $note['user_id']); // Bind user_id to the prepared statement
    return $statement->execute();
}





public function updateNote($id, $note)
{
    $statement = $this->pdo->prepare("UPDATE notes SET subject = :subject, content = :content, created_at = NOW() WHERE id = :id");
    $statement->bindValue('id', $id);
    $statement->bindValue('subject', $note['subject']);
    $statement->bindValue('content', $note['content']);
    return $statement->execute();
}



    public function removeNote($id)
    {
        $statement = $this->pdo->prepare("DELETE FROM notes WHERE id = :id");
        $statement->bindValue('id', $id);
        return $statement->execute();
    }

    public function getNoteById($id)
    {
        $statement = $this->pdo->prepare("SELECT * FROM notes WHERE id = :id");
        $statement->bindValue('id', $id);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }
    

    
}



return new Connection();

?>