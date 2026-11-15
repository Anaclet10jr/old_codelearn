<?php
return [
'slug' => 'java', 'title' => 'Java', 'icon' => '☕', 'color' => '#e76f00', 'level' => 'Intermediate',
'description' => 'Strongly-typed, object-oriented programming used for enterprise systems and Android: classes, methods, inheritance and interfaces.',
'lessons' => [

['title' => 'Hello Java',
'content' => <<<'LESSON_END'
<p><strong>Java</strong> programs are made of classes. Source is compiled to <em>bytecode</em> that runs on the Java Virtual Machine (JVM) – "write once, run anywhere".</p>
<ul>
  <li>The file name must match the public class name: <code>Hello.java</code>.</li>
  <li>Execution starts at <code>public static void main(String[] args)</code>.</li>
  <li>Compile: <code>javac Hello.java</code> · Run: <code>java Hello</code></li>
  <li>Primitive types: <code>int</code>, <code>double</code>, <code>boolean</code>, <code>char</code>, <code>long</code>. <code>String</code> is a class.</li>
  <li>Java is statically typed – every variable has a declared type.</li>
</ul>
<div class="exercise"><strong>Practice:</strong> compute the average of three <code>double</code> values and print it with <code>System.out.printf("%.2f%n", avg)</code>.</div>
LESSON_END,
'code' => <<<'CODE_END'
public class Hello {
    public static void main(String[] args) {
        String name = "Amina";
        int age = 21;
        double height = 1.68;
        boolean student = true;

        System.out.println("Hello, " + name + "!");
        System.out.println(name + " is " + age + " years old.");
        System.out.printf("Height: %.2f m, student: %b%n", height, student);
    }
}
CODE_END],

['title' => 'Control Flow and Arrays',
'content' => <<<'LESSON_END'
<p>Java offers <code>if/else</code>, <code>switch</code>, <code>for</code>, enhanced <code>for-each</code>, <code>while</code> and <code>do-while</code>.</p>
<p>An <strong>array</strong> has a fixed size and one element type: <code>int[] nums = new int[5];</code> or <code>int[] nums = {4, 8, 15};</code>. Its length is <code>nums.length</code> (a field, not a method).</p>
<p>For resizable collections use <code>ArrayList&lt;Integer&gt;</code> from <code>java.util</code>.</p>
<div class="exercise"><strong>Practice:</strong> find the largest value in the array without sorting it.</div>
LESSON_END,
'code' => <<<'CODE_END'
import java.util.ArrayList;

public class Loops {
    public static void main(String[] args) {
        int[] scores = {72, 95, 60, 88};
        int total = 0;

        for (int i = 0; i < scores.length; i++) {
            total += scores[i];
        }
        System.out.println("Average: " + (double) total / scores.length);

        for (int s : scores) {                       // for-each
            String grade = s >= 80 ? "A" : (s >= 60 ? "B" : "C");
            System.out.println(s + " -> " + grade);
        }

        ArrayList<String> names = new ArrayList<>();
        names.add("Amina");
        names.add("Jean");
        System.out.println(names + " size=" + names.size());
    }
}
CODE_END],

['title' => 'Classes and Objects',
'content' => <<<'LESSON_END'
<p>A <strong>class</strong> is a blueprint; an <strong>object</strong> is an instance created with <code>new</code>.</p>
<ul>
  <li><em>Fields</em> store state; mark them <code>private</code> (encapsulation).</li>
  <li>A <em>constructor</em> has the class name and no return type.</li>
  <li><em>Getters/setters</em> give controlled access to private fields.</li>
  <li><code>static</code> members belong to the class, not to individual objects.</li>
</ul>
<div class="exercise"><strong>Practice:</strong> add a <code>withdraw(double)</code> method that throws <code>IllegalArgumentException</code> when funds are insufficient.</div>
LESSON_END,
'code' => <<<'CODE_END'
public class Account {
    private String owner;
    private double balance;

    public Account(String owner, double balance) {
        this.owner = owner;
        this.balance = balance;
    }

    public void deposit(double amount) {
        if (amount <= 0) throw new IllegalArgumentException("Amount must be positive");
        balance += amount;
    }

    public double getBalance() { return balance; }

    public static void main(String[] args) {
        Account a = new Account("Amina", 1000);
        a.deposit(250.50);
        System.out.println(a.owner + " has " + a.getBalance());
    }
}
CODE_END],

['title' => 'Inheritance and Interfaces',
'content' => <<<'LESSON_END'
<p><strong>Inheritance</strong> lets a class reuse another using <code>extends</code>. Override a method with <code>@Override</code>. <code>super</code> refers to the parent.</p>
<p>An <strong>interface</strong> defines a contract – methods a class must provide – and a class <code>implements</code> it. A class can implement many interfaces but extend only one class.</p>
<p><strong>Polymorphism</strong>: code written against the parent type/interface works with any child, and the correct overridden method runs at runtime.</p>
<div class="exercise"><strong>Practice:</strong> add a <code>Triangle</code> class that implements <code>Shape</code>.</div>
LESSON_END,
'code' => <<<'CODE_END'
interface Shape {
    double area();
}

class Circle implements Shape {
    private final double r;
    Circle(double r) { this.r = r; }
    public double area() { return Math.PI * r * r; }
}

class Rectangle implements Shape {
    private final double w, h;
    Rectangle(double w, double h) { this.w = w; this.h = h; }
    public double area() { return w * h; }
}

public class Shapes {
    public static void main(String[] args) {
        Shape[] shapes = { new Circle(2), new Rectangle(3, 4) };
        for (Shape s : shapes) {
            System.out.printf("%s area = %.2f%n", s.getClass().getSimpleName(), s.area());
        }
    }
}
CODE_END],

],
'questions' => [
 ['q' => 'Which method is the entry point of a Java program?', 'options' => ['public static void main(String[] args)', 'void start()', 'public void run()', 'static main()'], 'answer' => 0, 'explain' => 'The JVM looks for exactly this signature.'],
 ['q' => 'A file containing "public class Student" must be named:', 'options' => ['Student.java', 'student.txt', 'Main.java', 'Class.java'], 'answer' => 0, 'explain' => 'A public class must live in a file with the same name.'],
 ['q' => 'Which keyword makes one class inherit from another?', 'options' => ['implements', 'extends', 'inherits', 'super'], 'answer' => 1, 'explain' => 'extends is used for class inheritance; implements is for interfaces.'],
 ['q' => 'Which primitive type stores true or false?', 'options' => ['bool', 'boolean', 'bit', 'Bool'], 'answer' => 1, 'explain' => 'The Java primitive is boolean.'],
 ['q' => 'Which command compiles Hello.java?', 'options' => ['javac Hello.java', 'compile Hello', 'gcc Hello.java', 'jrun Hello'], 'answer' => 0, 'explain' => 'javac is the Java compiler and produces Hello.class.'],
],
];
