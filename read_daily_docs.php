<?php
// 2026-08-14 03:41:51

/* PHP
**Topic:** PDO Prepared Statements in PHP

PDO prepared statements are used to prevent SQL injection attacks and improve the performance of database queries. They allow you to separate the SQL query from the data that will be used in the query. This helps to keep your code organized and makes it easier to read and understand. PDO prepared statements also cache the compiled SQL, so subsequent calls to the same query run faster.

```php
<?php
// Connect to database
$dsn = 'mysql:host=localhost;dbname=mydb';
$username = 'myuser';
$password = 'mypassword';
try {
    $pdo = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

// Prepare a SQL query
$stmt = $pdo->prepare('SELECT * FROM users WHERE name = :name AND email = :email');
// Bind the query parameters
$stmt->bindParam(':name', $name);
$stmt->bindParam(':email', $email);
// Set the values for the parameters
$name = 'John Doe';
$email = 'john@example.com';
// Execute the query
$stmt->execute();
// Fetch the results
$users = $stmt->fetchAll();
foreach ($users as $user) {
    echo $user['id'] . ' ' . $user['name'] . ' ' . $user['email'] . "\n";
}
$pdo = null;
?>
```
*/

/* Laravel
**Laravel Eloquent Relationship - One-To-One**

Eloquent relationship in Laravel allows you to perform CRUD operations on associated models. A one-to-one relationship is established when one instance of a model is related to one instance of another model. This type of relationship is useful when a model has a separate model associated with it, such as a user having a profile.

```php
// User Model
class User extends Model
{
    protected $fillable = [
        'name', 'email', 'password'
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
}

// Profile Model
class Profile extends Model
{
    protected $fillable = [
        'bio', 'image'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

// To get a user with their profile
$user = User::with('profile')->find(1);

// To create a profile for a user
$user->profile()->create([
    'bio' => 'Hello, I am John Doe',
    'image' => 'profile-image.jpg'
]);

// To get a profile by user ID
$profile = Profile::where('user_id', 1)->first();
```
*/

/* MySQL
Indexing in MySQL

Indexing is a key technique used to speed up querying in database systems like MySQL. It does this by pre-organizing data in an efficient manner that allows for faster lookup, insertion, and deletion times. An index is created on one or more columns of a table and can either be a clustered index where data is physically stored in the order it appears in the index or a non-clustered index where data is stored in a random order. Creating an index can also slow down insertion and deletion of data because the index has to be updated. 

```sql
-- Create a table without any indexes
CREATE TABLE employees (
    id INT AUTO_INCREMENT,
    name VARCHAR(255),
    position VARCHAR(255),
    PRIMARY KEY (id)
);

-- Insert some data
INSERT INTO employees (name, position) VALUES ('John Doe', 'Software Engineer');
INSERT INTO employees (name, position) VALUES ('Jane Doe', 'Data Analyst');
INSERT INTO employees (name, position) VALUES ('John Smith', 'Quality Assurance');

-- Create an index on the position column
CREATE INDEX idx_position ON employees (position);

-- Create an index on the name column
CREATE UNIQUE INDEX idx_name ON employees (name);

-- Now queries like this one will be faster
SELECT * FROM employees WHERE position = 'Software Engineer';

-- To drop the index you just created
DROP INDEX idx_name ON employees;
```
*/

/* JavaScript
**Closures with Callback Functions**

A closure is a function that has access to its own scope and the scope of its outer functions, even when the outer function has returned. This can be combined with callback functions to create more complex and dynamic coding solutions.

When a callback function is passed to a function that uses closures, the callback function can access the variables and data of the outer function even after the outer function has returned. This can be used to create more flexibility and reusability in code.

```javascript
function outerFunction(name, age, callback) {
    // Create a closure with a variable and assign it a value
    var person = {
        name: name,
        age: age
    };

    // Use the callback function to process the closure data
    callback(person);
}

// Define a callback function to process the closure data
function callbackFunction(person) {
    // Access the person object data and use it
    console.log('Hello, my name is ' + person.name + ' and I am ' + person.age + ' years old.');
}

// Create a new person object and pass it to the outerFunction
outerFunction('John Doe', 30, callbackFunction);
```
*/

/* AI
**Topic: Neural Network Backpropagation Tutorial**

Backpropagation is a fundamental algorithm used to train neural networks by minimizing the error between predicted and actual outputs. It works by iteratively adjusting the weights and biases of the network based on the error gradient. The process involves three main steps: forward pass, error calculation, and weight update. During the forward pass, the input is propagated through the network to produce the output. The error is then calculated between the predicted output and the actual output. Finally, the weight update rule is applied to adjust the weights and biases of the network.

```python
# Import necessary libraries
import numpy as np

# Define the number of inputs, hidden units, and outputs
num_inputs = 3
num_hidden = 2
num_outputs = 1

# Initialize the weights and biases
weights1 = np.random.rand(num_inputs, num_hidden)
weights2 = np.random.rand(num_hidden, num_outputs)
bias1 = np.zeros((1, num_hidden))
bias2 = np.zeros((1, num_outputs))

# Define the activation functions
def sigmoid(x):
    return 1 / (1 + np.exp(-x))

def ReLU(x):
    return np.maximum(x, 0)

# Define the derivative of the activation functions
def sigmoid_derivative(x):
    return x * (1 - x)

def ReLU_derivative(x):
    return 1 * (x > 0)

# Define the learning rate and input data
learning_rate = 0.1
input_data = np.array([[0, 0, 1]])

# Forward pass
hidden_layer = np.dot(input_data, weights1) + bias1
hidden_layer = sigmoid(hidden_layer)
output_layer = np.dot(hidden_layer, weights2) + bias2
output_layer = sigmoid(output_layer)

# Error calculation
error = np.mean((output_layer - np.array([1]))**2)

# Backward pass
delta_output = 2 * (output_layer - np.array([1])) * sigmoid_derivative(output_layer)
delta_hidden = delta_output.dot(weights2.T) * sigmoid_derivative(hidden_layer)

# Weight update
weights2 -= learning_rate * hidden_layer.T.dot(delta_output)
bias2 -= learning_rate * np.sum(delta_output, axis=0, keepdims=True)
weights1 -= learning_rate * input_data.T.dot(delta_hidden)
bias1 -= learning_rate * np.sum(delta_hidden, axis=0, keepdims=True)
```

This code example demonstrates the basic steps of backpropagation in a simple neural network with one hidden layer. The weights and biases are updated based on the error gradient, and the error is calculated between the predicted output and the actual output.
*/
