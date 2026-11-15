/* Compile: gcc hello.c -o hello   Run: ./hello */
#include <stdio.h>

int main(void) {
    int n;
    printf("How many stars? ");
    if (scanf("%d", &n) != 1 || n < 0) {
        printf("Please enter a positive number.\n");
        return 1;
    }
    for (int i = 1; i <= n; i++) {
        for (int j = 0; j < i; j++) printf("*");
        printf("\n");
    }
    return 0;
}
