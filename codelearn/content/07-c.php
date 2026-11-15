<?php
return [
'slug' => 'c', 'title' => 'C', 'icon' => '⚙️', 'color' => '#00599c', 'level' => 'Advanced',
'description' => 'Learn how computers really work. Compile native programs, work with arrays, strings, pointers and manual memory management.',
'lessons' => [

['title' => 'Hello C',
'content' => <<<'LESSON_END'
<p><strong>C</strong> is a compiled, low-level language that powers operating systems, databases and embedded devices. You write source, a compiler turns it into a native executable.</p>
<ul>
  <li>Compile: <code>gcc hello.c -o hello</code> · Run: <code>./hello</code></li>
  <li><code>#include &lt;stdio.h&gt;</code> brings in <code>printf</code> and <code>scanf</code>.</li>
  <li>Execution starts in <code>int main(void)</code>, which returns 0 on success.</li>
  <li>Types: <code>int</code>, <code>long</code>, <code>float</code>, <code>double</code>, <code>char</code>.</li>
  <li>Format specifiers: <code>%d</code> int, <code>%f</code> float/double, <code>%c</code> char, <code>%s</code> string.</li>
</ul>
<div class="exercise"><strong>Practice:</strong> read two integers with <code>scanf("%d %d", &amp;a, &amp;b)</code> and print their product.</div>
LESSON_END,
'code' => <<<'CODE_END'
#include <stdio.h>

int main(void) {
    int age = 21;
    double height = 1.68;
    char grade = 'A';

    printf("Hello, C world!\n");
    printf("Age: %d, height: %.2f, grade: %c\n", age, height, grade);
    printf("Size of int: %zu bytes\n", sizeof(int));
    return 0;
}
CODE_END],

['title' => 'Control Flow and Loops',
'content' => <<<'LESSON_END'
<p>C has <code>if / else</code>, <code>switch</code>, <code>for</code>, <code>while</code> and <code>do-while</code>. There is no built-in boolean in older C: <strong>0 means false, anything else means true</strong> (C99 adds <code>&lt;stdbool.h&gt;</code>).</p>
<p>Functions must be declared before use. Pass-by-value is the default: the function receives a <em>copy</em>.</p>
<div class="exercise"><strong>Practice:</strong> write <code>int factorial(int n)</code> using a loop.</div>
LESSON_END,
'code' => <<<'CODE_END'
#include <stdio.h>

int is_prime(int n) {
    if (n < 2) return 0;
    for (int i = 2; i * i <= n; i++) {
        if (n % i == 0) return 0;
    }
    return 1;
}

int main(void) {
    for (int i = 1; i <= 20; i++) {
        if (is_prime(i)) {
            printf("%d is prime\n", i);
        }
    }
    int count = 3;
    while (count > 0) {
        printf("%d...\n", count--);
    }
    printf("Liftoff!\n");
    return 0;
}
CODE_END],

['title' => 'Arrays and Strings',
'content' => <<<'LESSON_END'
<p>An <strong>array</strong> is a block of same-typed values stored side by side: <code>int a[5] = {1,2,3,4,5};</code>. C does <em>not</em> check bounds – reading <code>a[10]</code> is undefined behaviour.</p>
<p>A <strong>string</strong> is a <code>char</code> array that ends with the null character <code>'\0'</code>. Use <code>&lt;string.h&gt;</code>: <code>strlen</code>, <code>strcpy</code>, <code>strcmp</code>, <code>strcat</code>.</p>
<ul><li>Compare strings with <code>strcmp</code>, never with <code>==</code>.</li><li>Make sure a destination buffer is big enough before copying.</li></ul>
<div class="exercise"><strong>Practice:</strong> reverse a string in place.</div>
LESSON_END,
'code' => <<<'CODE_END'
#include <stdio.h>
#include <string.h>

int main(void) {
    int scores[5] = {70, 85, 90, 60, 75};
    int sum = 0;
    for (int i = 0; i < 5; i++) sum += scores[i];
    printf("Average: %.1f\n", sum / 5.0);

    char name[20] = "Amina";
    printf("Length of %s = %zu\n", name, strlen(name));

    strcat(name, " K.");
    printf("%s\n", name);

    if (strcmp("abc", "abd") < 0) {
        printf("\"abc\" comes before \"abd\"\n");
    }
    return 0;
}
CODE_END],

['title' => 'Pointers',
'content' => <<<'LESSON_END'
<p>A <strong>pointer</strong> stores a memory <em>address</em>.</p>
<ul>
  <li><code>&amp;x</code> – the address of x</li>
  <li><code>int *p = &amp;x;</code> – p points to x</li>
  <li><code>*p</code> – the value stored at that address (dereferencing)</li>
  <li>Array names decay to pointers, so <code>a[i]</code> equals <code>*(a + i)</code>.</li>
</ul>
<p>Pointers let a function change the caller's variables (pass by reference) – this is how <code>scanf</code> works.</p>
<div class="exercise"><strong>Practice:</strong> write <code>void swap(int *a, int *b)</code> and test it.</div>
LESSON_END,
'code' => <<<'CODE_END'
#include <stdio.h>

void swap(int *a, int *b) {
    int tmp = *a;
    *a = *b;
    *b = tmp;
}

int main(void) {
    int x = 10, y = 20;
    int *p = &x;

    printf("x = %d, address = %p\n", x, (void *)&x);
    printf("*p = %d\n", *p);

    swap(&x, &y);
    printf("after swap: x = %d, y = %d\n", x, y);

    int arr[3] = {5, 6, 7};
    printf("arr[1] = %d, *(arr+1) = %d\n", arr[1], *(arr + 1));
    return 0;
}
CODE_END],

['title' => 'Structs and Dynamic Memory',
'content' => <<<'LESSON_END'
<p>A <code>struct</code> groups related variables into one type. Access members with <code>.</code>, or with <code>-&gt;</code> through a pointer.</p>
<p><strong>Dynamic memory</strong> comes from the heap:</p>
<ul>
  <li><code>malloc(size)</code> – allocate (check for <code>NULL</code>!)</li>
  <li><code>free(ptr)</code> – release it when finished</li>
</ul>
<p>Forgetting <code>free</code> causes <em>memory leaks</em>; using memory after freeing is a serious bug. Tools such as <code>valgrind</code> and <code>-fsanitize=address</code> help find these.</p>
<div class="exercise"><strong>Practice:</strong> read <em>n</em> from the user and store <em>n</em> student records in a dynamic array.</div>
LESSON_END,
'code' => <<<'CODE_END'
#include <stdio.h>
#include <stdlib.h>

typedef struct {
    char name[30];
    int  score;
} Student;

int main(void) {
    int n = 3;
    Student *list = malloc(n * sizeof(Student));
    if (list == NULL) {
        fprintf(stderr, "Out of memory\n");
        return 1;
    }

    const char *names[] = {"Amina", "Jean", "Eric"};
    int marks[] = {88, 72, 95};

    for (int i = 0; i < n; i++) {
        snprintf(list[i].name, sizeof list[i].name, "%s", names[i]);
        list[i].score = marks[i];
    }
    for (int i = 0; i < n; i++) {
        printf("%-8s %d\n", list[i].name, list[i].score);
    }

    free(list);          // always release what you allocate
    return 0;
}
CODE_END],

],
'questions' => [
 ['q' => 'Which header declares printf?', 'options' => ['<stdio.h>', '<stdlib.h>', '<string.h>', '<math.h>'], 'answer' => 0, 'explain' => 'stdio.h = standard input/output.'],
 ['q' => 'Which format specifier prints an int?', 'options' => ['%d', '%s', '%f', '%c'], 'answer' => 0, 'explain' => '%d prints a decimal integer.'],
 ['q' => 'What does &x give you?', 'options' => ['The value of x', 'The memory address of x', 'The size of x', 'The type of x'], 'answer' => 1, 'explain' => 'The address-of operator returns where x is stored.'],
 ['q' => 'C strings are terminated by which character?', 'options' => ["'\\n'", "'\\0'", "';'", 'EOF'], 'answer' => 1, 'explain' => "The null character '\\0' marks the end of a string."],
 ['q' => 'Which function releases memory obtained with malloc?', 'options' => ['delete', 'free', 'release', 'dispose'], 'answer' => 1, 'explain' => 'Every malloc needs a matching free.'],
],
];
