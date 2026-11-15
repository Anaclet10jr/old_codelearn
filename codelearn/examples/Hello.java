// Compile: javac Hello.java   Run: java Hello
import java.util.Scanner;

public class Hello {
    static boolean isPalindrome(String s) {
        String clean = s.toLowerCase().replaceAll("[^a-z0-9]", "");
        return new StringBuilder(clean).reverse().toString().equals(clean);
    }

    public static void main(String[] args) {
        Scanner in = new Scanner(System.in);
        System.out.print("Enter a word or phrase: ");
        String text = in.nextLine();
        System.out.println(isPalindrome(text) ? "Palindrome!" : "Not a palindrome.");
    }
}
