<?php
// 2026-09-20 06:52:43

/* PHP
Topic: PDO Prepared Statements for Secure Database Access  

Explanation:  
PDO (PHP Data Objects) provides a consistent interface for accessing different databases.  
Prepared statements separate SQL code from data, preventing SQL injection attacks.  
Placeholders in the query are bound to variables, allowing the database engine to optimize execution.  
Error handling with exceptions makes debugging easier and keeps the code clean.  
Using PDO you can switch between MySQL, PostgreSQL, SQLite, etc., without changing query logic.  

Code Example:  
<?php  
// Database connection parameters  
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8';  
$username = 'dbuser';  
$password = 'dbpass';  

// PDO options for error mode and fetch style  
$options = [  
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,  
];  

try {  
    // Create PDO instance  
    $pdo = new PDO($dsn, $username, $password, $options);  
} catch (PDOException $e) {  
    // Handle connection errors  
    die('Connection failed: ' . $e->getMessage());  
}  

// SQL query with named placeholders  
$sql = 'SELECT id, name FROM users WHERE email = :email AND status = :status';  

// Prepare the statement  
$stmt = $pdo->prepare($sql);  

// Values to bind to placeholders  
$email = 'example@example.com';  
$status = 'active';  

// Bind parameters securely  
$stmt->bindParam(':email', $email, PDO::PARAM_STR);  
$stmt->bindParam(':status', $status, PDO::PARAM_STR);  

// Execute the query  
$stmt->execute();  

// Fetch all matching rows  
$users = $stmt->fetchAll();  

// Output results  
foreach ($users as $user) {  
    echo $user['id'] . ' - ' . $user['name'] . PHP_EOL;  
}  
?>
*/

/* Laravel
Topic: Polymorphic Many‑to‑Many Relationships in Laravel Eloquent

Explanation:
A polymorphic many‑to‑many relationship lets a model belong to more than one other model on a single association. It is useful for features like tags, where posts, videos, and products can all share the same tags table. Laravel handles the intermediate table automatically, storing the related model’s type and ID. Defining the relationship requires a pivot table with morph columns (e.g., taggable_id and taggable_type). Once set up, you can attach, detach, and sync related models just like regular many‑to‑many relationships.

Code example (Tag model, Post model, and migration):

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    // Tags can belong to many different models
    public function posts()
    {
        return $this->morphedByMany(Post::class, 'taggable');
    }

    public function videos()
    {
        return $this->morphedByMany(Video::class, 'taggable');
    }
}

class Post extends Model
{
    // A post can have many tags
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}

// Migration for the polymorphic pivot table
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaggablesTable extends Migration
{
    public function up()
    {
        Schema::create('taggables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tag_id')->constrained()->onDelete('cascade'); // reference tags
            $table->unsignedBigInteger('taggable_id');   // ID of the related model
            $table->string('taggable_type');            // Class name of the related model
            $table->timestamps();

            $table->index(['taggable_id', 'taggable_type']); // improve query performance
        });
    }

    public function down()
    {
        Schema::dropIfExists('taggables');
    }
}

// Using the relationship in a controller or tinker
$post = Post::find(1);
$tag  = Tag::first();

// Attach a tag to the post
$post->tags()->attach($tag->id);

// Retrieve all tags for the post
$tags = $post->tags; // collection of Tag models

// Sync tags (replace existing tags with new set)
$post->tags()->sync([2, 3, 4]); // attach tags with IDs 2,3,4 and detach others

// Detach a specific tag
$post->tags()->detach($tag->id);
?>
*/

/* MySQL
Topic: Common Table Expressions (CTEs) and Recursive Queries

Explanation:
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.  
CTEs are defined using the WITH clause and improve readability by allowing you to break complex queries into logical building blocks.  
Recursive CTEs enable hierarchical or graph‑like data traversal, such as retrieving all descendants of a node in a tree.  
The recursion stops when the anchor query no longer produces rows that satisfy the recursive member.  
MySQL supports both non‑recursive and recursive CTEs starting with version 8.0.

Code Example:
-- Create a simple hierarchical table
CREATE TABLE employees (
    emp_id INT PRIMARY KEY,
    name   VARCHAR(50),
    manager_id INT NULL   -- references emp_id of the manager
);

INSERT INTO employees (emp_id, name, manager_id) VALUES
(1, 'Alice', NULL),      -- top‑level manager
(2, 'Bob',   1),
(3, 'Carol', 1),
(4, 'Dave',  2),
(5, 'Eve',   2),
(6, 'Frank', 4);

-- Recursive CTE to list all subordinates of a given manager (e.g., manager_id = 1)
WITH RECURSIVE subordinates AS (
    -- Anchor member: start with the direct reports of the chosen manager
    SELECT emp_id, name, manager_id, 1 AS level
    FROM employees
    WHERE manager_id = 1

    UNION ALL

    -- Recursive member: find employees whose manager is already in the result set
    SELECT e.emp_id, e.name, e.manager_id, s.level + 1
    FROM employees e
    INNER JOIN subordinates s ON e.manager_id = s.emp_id
)
SELECT emp_id, name, manager_id, level
FROM subordinates
ORDER BY level, emp_id;

-- The query returns:
-- emp_id | name  | manager_id | level
--   2    | Bob   |      1     | 1
--   3    | Carol |      1     | 1
--   4    | Dave  |      2     | 2
--   5    | Eve   |      2     | 2
--   6    | Frank |      4     | 3   (indirect subordinate)
*/

/* JavaScript
Topic: JavaScript Closures

Explanation:
A closure is a function that retains access to the variables of its outer (enclosing) function even after that outer function has finished executing. This happens because the inner function forms a lexical environment that includes the outer scope’s variables. Closures enable data privacy, function factories, and can be used to maintain state across multiple calls without exposing variables globally. They are created every time a function is defined inside another function. Understanding closures is essential for mastering asynchronous code, callbacks, and module patterns in JavaScript.

Code example with comments:
function makeCounter(initialValue) {                 // outer function creates a counter
    let count = initialValue;                       // private variable, not accessible outside
    return function() {                            // inner function forms a closure
        count += 1;                                 // can modify the outer variable
        console.log('Current count:', count);      // uses the private state
    };
}

const counterA = makeCounter(0);   // each call gets its own independent closure
counterA(); // Current count: 1
counterA(); // Current count: 2

const counterB = makeCounter(10);
counterB(); // Current count: 11
counterA(); // Current count: 3   // counterA maintains its own state, unaffected by counterB.
*/

/* AI
Topic: Prompt Engineering for Few‑Shot Learning with the OpenAI API  

Explanation:  
1. Few‑shot prompting supplies the model with a small number of example input‑output pairs to teach it the desired pattern.  
2. By carefully designing the examples and the instruction, you can steer the model to perform classification, transformation, or reasoning tasks without fine‑tuning.  
3. The prompt should include a clear task description, a delimiter separating examples, and a final user query awaiting the model’s response.  
4. Temperature close to zero makes the model’s output deterministic, which is useful for reproducible results in programmatic pipelines.  
5. Using the OpenAI Python client you can construct the prompt dynamically, send it to the API, and parse the returned text for further processing.  

Code example (Python, requires `openai` library and a valid API key):  

import os
import openai

# Set your OpenAI API key, e.g., from an environment variable
openai.api_key = os.getenv("OPENAI_API_KEY")

def classify_sentiment(text):
    """
    Uses a few‑shot prompt to classify the sentiment of a single sentence.
    Returns 'Positive', 'Negative', or 'Neutral'.
    """
    # Construct the prompt with two labeled examples and the new query
    prompt = (
        "Classify the sentiment of the following sentences as Positive, Negative, or Neutral.\n\n"
        "Sentence: I love the new phone I bought!\n"
        "Sentiment: Positive\n\n"
        "Sentence: The traffic today was terrible.\n"
        "Sentiment: Negative\n\n"
        f"Sentence: {text}\n"
        "Sentiment:"
    )

    # Call the OpenAI Completion endpoint (use gpt-3.5-turbo for chat-like behavior)
    response = openai.ChatCompletion.create(
        model="gpt-3.5-turbo",
        messages=[{"role": "user", "content": prompt}],
        temperature=0.0,               # deterministic output
        max_tokens=10,                 # we only need a short label
        n=1,
        stop=None
    )

    # Extract the generated sentiment label
    sentiment = response.choices[0].message.content.strip()
    return sentiment

# Example usage
if __name__ == "__main__":
    test_sentence = "The movie was okay, not great but not bad either."
    print(f"Input: {test_sentence}")
    print("Predicted Sentiment:", classify_sentiment(test_sentence))
*/

