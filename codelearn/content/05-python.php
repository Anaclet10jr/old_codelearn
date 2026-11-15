<?php
return [
'slug' => 'python', 'title' => 'Python', 'icon' => '🐍', 'color' => '#3776ab', 'level' => 'Beginner',
'description' => 'The friendliest general-purpose language: variables, loops, functions, data structures and object-oriented programming.',
'lessons' => [

['title' => 'Hello Python and Variables',
'content' => <<<'LESSON_END'
<p><strong>Python</strong> is known for readable code. There are no semicolons or braces – <em>indentation</em> defines blocks.</p>
<ul>
  <li>Variables are created by assignment: <code>age = 21</code> (no type keyword).</li>
  <li>Types: <code>int</code>, <code>float</code>, <code>str</code>, <code>bool</code>, <code>list</code>, <code>dict</code>, <code>tuple</code>, <code>set</code>.</li>
  <li><code>print()</code> shows output, <code>input()</code> reads from the keyboard (always returns text).</li>
  <li>Comments start with <code>#</code>.</li>
</ul>
<p>Install Python from python.org, save the code as <code>hello.py</code> and run <code>python hello.py</code>.</p>
<div class="exercise"><strong>Practice:</strong> ask the user for two numbers with <code>input()</code> and print their sum (remember <code>int()</code>).</div>
LESSON_END,
'code' => <<<'CODE_END'
# hello.py
name = "Amina"
age = 21
height = 1.68

print("Hello,", name)
print(f"{name} is {age} years old and {height} m tall.")   # f-string
print(type(age), type(height), type(name))

# Arithmetic
print(7 / 2)    # 3.5  (true division)
print(7 // 2)   # 3    (floor division)
print(2 ** 10)  # 1024 (power)
CODE_END],

['title' => 'Control Flow and Loops',
'content' => <<<'LESSON_END'
<p>Use <code>if / elif / else</code> to make decisions. Python has two loops:</p>
<ul>
  <li><code>for item in sequence:</code> – iterate over a list, string, range…</li>
  <li><code>while condition:</code> – repeat while something is true</li>
</ul>
<p><code>range(5)</code> gives 0,1,2,3,4. <code>break</code> leaves a loop, <code>continue</code> skips to the next iteration. Logical operators are the words <code>and</code>, <code>or</code>, <code>not</code>.</p>
<div class="exercise"><strong>Practice:</strong> print the multiplication table of 7 using a for loop.</div>
LESSON_END,
'code' => <<<'CODE_END'
score = 72

if score >= 80:
    print("Grade A")
elif score >= 60:
    print("Grade B")
else:
    print("Grade C")

for i in range(1, 6):
    print(i, "squared =", i * i)

# FizzBuzz
for n in range(1, 16):
    if n % 15 == 0:
        print("FizzBuzz")
    elif n % 3 == 0:
        print("Fizz")
    elif n % 5 == 0:
        print("Buzz")
    else:
        print(n)
CODE_END],

['title' => 'Functions and Modules',
'content' => <<<'LESSON_END'
<p>Define a function with <code>def</code>. Functions can have default arguments, return multiple values and carry a <em>docstring</em> that documents them.</p>
<p>A <strong>module</strong> is just a <code>.py</code> file. Import ready-made ones from the huge standard library: <code>math</code>, <code>random</code>, <code>datetime</code>, <code>json</code>, <code>sqlite3</code>, <code>csv</code>…</p>
<div class="exercise"><strong>Practice:</strong> write <code>celsius_to_fahrenheit(c)</code> and test it with 0 and 100.</div>
LESSON_END,
'code' => <<<'CODE_END'
import math
import random

def area_circle(radius, digits=2):
    """Return the area of a circle."""
    return round(math.pi * radius ** 2, digits)

def min_max(numbers):
    return min(numbers), max(numbers)      # returns a tuple

print(area_circle(5))
low, high = min_max([4, 9, 1, 7])
print("min:", low, "max:", high)
print("random pick:", random.choice(["red", "green", "blue"]))

if __name__ == "__main__":
    print("This file was run directly")
CODE_END],

['title' => 'Lists, Dictionaries and Comprehensions',
'content' => <<<'LESSON_END'
<table>
<tr><th>Type</th><th>Example</th><th>Notes</th></tr>
<tr><td>list</td><td><code>[1, 2, 3]</code></td><td>ordered, changeable</td></tr>
<tr><td>tuple</td><td><code>(1, 2, 3)</code></td><td>ordered, fixed</td></tr>
<tr><td>dict</td><td><code>{"a": 1}</code></td><td>key → value pairs</td></tr>
<tr><td>set</td><td><code>{1, 2, 3}</code></td><td>unique items only</td></tr>
</table>
<p>A <strong>list comprehension</strong> builds a list in one line: <code>[x * x for x in range(5) if x % 2 == 0]</code>.</p>
<div class="exercise"><strong>Practice:</strong> count how often each word appears in a sentence using a dictionary.</div>
LESSON_END,
'code' => <<<'CODE_END'
fruits = ["apple", "banana", "cherry"]
fruits.append("mango")
print(fruits[0], fruits[-1], len(fruits))

student = {"name": "Jean", "age": 20}
student["course"] = "Python"
for key, value in student.items():
    print(key, "->", value)

squares = [x * x for x in range(1, 6)]
evens   = [x for x in squares if x % 2 == 0]
print(squares)   # [1, 4, 9, 16, 25]
print(evens)     # [4, 16]

words = "the cat and the hat".split()
counts = {}
for w in words:
    counts[w] = counts.get(w, 0) + 1
print(counts)    # {'the': 2, 'cat': 1, 'and': 1, 'hat': 1}
CODE_END],

['title' => 'Classes and Objects',
'content' => <<<'LESSON_END'
<p><strong>Object-oriented programming</strong> bundles data and behaviour into <em>classes</em>. An object is an instance of a class.</p>
<ul>
  <li><code>__init__</code> is the constructor; <code>self</code> refers to the current object.</li>
  <li>Methods are functions defined inside the class.</li>
  <li><em>Inheritance</em>: <code>class Student(Person):</code> reuses and extends another class.</li>
  <li><code>__str__</code> defines how an object prints.</li>
</ul>
<div class="exercise"><strong>Practice:</strong> add a <code>withdraw()</code> method to <code>BankAccount</code> that refuses to go below zero.</div>
LESSON_END,
'code' => <<<'CODE_END'
class BankAccount:
    def __init__(self, owner, balance=0):
        self.owner = owner
        self.balance = balance

    def deposit(self, amount):
        self.balance += amount

    def __str__(self):
        return f"{self.owner}: {self.balance} RWF"

class SavingsAccount(BankAccount):          # inheritance
    def add_interest(self, rate=0.05):
        self.balance += self.balance * rate

acc = SavingsAccount("Amina", 1000)
acc.deposit(500)
acc.add_interest()
print(acc)          # Amina: 1575.0 RWF
CODE_END],

],
'questions' => [
 ['q' => 'How do you write a single-line comment in Python?', 'options' => ['// comment', '# comment', '-- comment', '/* comment */'], 'answer' => 1, 'explain' => 'Python comments start with #.'],
 ['q' => 'Which of these creates a list?', 'options' => ['{1, 2, 3}', '(1, 2, 3)', '[1, 2, 3]', '<1, 2, 3>'], 'answer' => 2, 'explain' => 'Square brackets create a list; {} makes a set/dict and () a tuple.'],
 ['q' => 'What does len("code") return?', 'options' => ['3', '4', '5', 'It causes an error'], 'answer' => 1, 'explain' => 'The string has 4 characters.'],
 ['q' => 'Which keyword defines a function?', 'options' => ['function', 'def', 'fn', 'func'], 'answer' => 1, 'explain' => 'Python functions are defined with def.'],
 ['q' => 'What does [x*x for x in range(3)] produce?', 'options' => ['[0, 1, 4]', '[1, 4, 9]', '[0, 1, 2]', '[0, 2, 4]'], 'answer' => 0, 'explain' => 'range(3) is 0,1,2 so the squares are 0,1,4.'],
],
];
