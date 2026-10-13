<?php
// 2026-10-13 08:21:17

/* PHP
PHP Topic: Generators and the yield keyword  

Explanation:  
Generators allow you to create iterators without building an entire array in memory.  
When a function contains the yield statement, PHP returns a Generator object that produces values on demand.  
Each call to yield pauses the function, preserving its local state until the next value is requested.  
This approach is memory‑efficient for processing large data sets, streams, or infinite sequences.  
Generators can also receive values sent from the caller using the send() method, enabling two‑way communication.

Code example (with comments):

<?php
// A generator function that yields the squares of numbers from 1 to $limit
function squareNumbers(int $limit): Generator
{
    for ($i = 1; $i <= $limit; $i++) {
        // yield returns the current square and pauses execution
        yield $i => $i * $i;
    }
}

// Using the generator
$limit = 5;
$squares = squareNumbers($limit);

foreach ($squares as $number => $square) {
    // Each iteration fetches the next value without loading all results at once
    echo "The square of $number is $square\n";
}

// Demonstrating two‑way communication with send()
function counter(): Generator
{
    $i = 0;
    while (true) {
        // Yield the current count and wait for a value to be sent back
        $reset = yield $i++;
        // If a non‑null value is sent, reset the counter
        if ($reset !== null) {
            $i = (int)$reset;
        }
    }
}

$gen = counter();
echo $gen->current() . "\n"; // 0
$gen->next();               // advance to 1
echo $gen->current() . "\n"; // 1
$gen->send(10);             // reset counter to 10
echo $gen->current() . "\n"; // 10
?>
*/

/* Laravel
Topic: Laravel Eloquent Polymorphic Many‑to‑Many Relationships

Explanation:
- Polymorphic many‑to‑many allows a model to belong to more than one other model on a single association table.  
- Common use cases include tagging systems where posts, videos, and products can share tags.  
- The pivot table stores the related model type and its identifier, enabling flexible queries.  
- Defining the relationship requires a morphToMany method on the related models and a morphedByMany on the tag model.  
- Laravel automatically resolves the correct model class when retrieving or attaching records.

Code Example (Tag, Post, and Video models with migration and usage):

// migration for tags table
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagsTable extends Migration
{
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tags');
    }
}

// migration for taggables pivot table
class CreateTaggablesTable extends Migration
{
    public function up()
    {
        Schema::create('taggables', function (Blueprint $table) {
            $table->foreignId('tag_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('taggable_id');
            $table->string('taggable_type');
            $table->primary(['tag_id', 'taggable_id', 'taggable_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('taggables');
    }
}

// Tag model
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name'];

    // A tag can belong to many different models
    public function posts()
    {
        return $this->morphedByMany(Post::class, 'taggable');
    }

    public function videos()
    {
        return $this->morphedByMany(Video::class, 'taggable');
    }
}

// Post model
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title', 'content'];

    // A post can have many tags
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}

// Video model
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = ['title', 'url'];

    // A video can have many tags
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}

// Using the relationship in a controller or tinker
use App\Models\Post;
use App\Models\Video;
use App\Models\Tag;

// Create tags
$phpTag   = Tag::create(['name' => 'PHP']);
$laravelTag = Tag::create(['name' => 'Laravel']);

// Create a post and attach tags
$post = Post::create(['title' => 'Laravel Polymorphism', 'content' => '...']);
$post->tags()->attach($phpTag->id);
$post->tags()->attach($laravelTag->id);

// Create a video and attach a tag
$video = Video::create(['title' => 'Eloquent Tutorial', 'url' => 'https://example.com/video']);
$video->tags()->attach($laravelTag->id);

// Retrieve tags for a post
$postTags = $post->tags; // collection of Tag models

// Retrieve all posts that have the "Laravel" tag
$laravelPosts = Tag::where('name', 'Laravel')->first()->posts;
*/

/* MySQL
Topic: Common Table Expressions (CTE) in MySQL

Explanation:  
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.  
CTEs improve readability by allowing you to break complex queries into logical sub‑queries written at the top of the statement.  
MySQL supports both non‑recursive and recursive CTEs starting from version 8.0.  
Non‑recursive CTEs are useful for simplifying joins, aggregations, or window functions.  
Recursive CTEs enable hierarchical queries such as traversing organization charts or tree structures.  

Code example (non‑recursive CTE with comments):

-- Define a CTE named recent_sales that selects sales from the last 30 days
WITH recent_sales AS (
    SELECT
        sale_id,
        customer_id,
        sale_amount,
        sale_date
    FROM
        sales
    WHERE
        sale_date >= CURDATE() - INTERVAL 30 DAY
)

-- Main query uses the CTE to calculate total sales per customer
SELECT
    c.customer_name,
    COUNT(r.sale_id) AS total_transactions,
    SUM(r.sale_amount) AS total_spent
FROM
    recent_sales r
JOIN
    customers c ON r.customer_id = c.customer_id
GROUP BY
    c.customer_name
ORDER BY
    total_spent DESC
LIMIT 10;   -- show top 10 customers by spending in the last month.
*/

/* JavaScript
Topic: Closures in JavaScript

Explanation:  
A closure is a function that retains access to its lexical scope even when that function is executed outside of its original context. This means the inner function can reference variables declared in the outer function after the outer function has finished running. Closures are created every time a function is defined, allowing private data encapsulation and function factories. They are essential for patterns such as memoization, currying, and module creation. Understanding closures helps avoid common pitfalls like unintentionally sharing mutable state between calls.

Code example with comments:
function makeCounter(initialValue) {
    // The variable count is local to makeCounter but will be kept alive by the inner function
    let count = initialValue;
    
    // The returned function forms a closure over the variable count
    return function () {
        // Each call increments and returns the current count
        count += 1;
        return count;
    };
}

// Create a new counter starting at 10
const counter = makeCounter(10);

// Subsequent calls remember the updated count value
console.log(counter()); // 11
console.log(counter()); // 12
console.log(counter()); // 13

// A second counter has its own independent closure
const anotherCounter = makeCounter(100);
console.log(anotherCounter()); // 101
console.log(counter()); // 14 (original counter continues where it left off)
*/

/* AI
Topic: Few‑Shot Prompt Engineering with OpenAI’s Chat Completion API  

Explanation:  
Few‑shot prompting lets you guide a large language model to perform a new task by providing a handful of labeled examples inside the prompt. The model infers the pattern and applies it to unseen inputs, reducing the need for large training datasets. By carefully formatting the examples and using clear separators, you improve consistency and accuracy. This technique works well for classification, extraction, or transformation tasks. The example below shows how to classify user intent with three demonstration pairs and then query the model for a new utterance.

Code example (Python, requires openai package, API key set in environment):
import os
import openai

# Load API key from environment variable (OPENAI_API_KEY)
openai.api_key = os.getenv("OPENAI_API_KEY")

def classify_intent(user_message: str) -> str:
    """
    Sends a few‑shot prompt to the Chat Completion endpoint.
    Returns the model’s predicted intent label.
    """
    # Few‑shot examples: each line shows an input and the expected label.
    examples = [
        ("I want to cancel my subscription", "Cancel Subscription"),
        ("How do I reset my password?", "Password Reset"),
        ("What are your pricing plans?", "Pricing Inquiry")
    ]

    # Build the prompt with a clear instruction and the examples.
    prompt = "Classify the user intent of the following messages. Respond with only the intent label.\n\n"
    for inp, label in examples:
        prompt += f"Message: {inp}\nIntent: {label}\n\n"
    # Add the new message we want classified.
    prompt += f"Message: {user_message}\nIntent:"

    # Call the Chat Completion API (using the gpt-3.5-turbo model).
    response = openai.ChatCompletion.create(
        model="gpt-3.5-turbo",
        messages=[{"role": "user", "content": prompt}],
        temperature=0.0,               # deterministic output
        max_tokens=20,                 # short answer
    )

    # Extract and return the label text (strip whitespace and newlines).
    return response.choices[0].message.content.strip()

# Example usage
if __name__ == "__main__":
    test_message = "Can you tell me how to change my billing address?"
    intent = classify_intent(test_message)
    print(f"Predicted intent: {intent}")
*/

