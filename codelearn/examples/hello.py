# Run: python hello.py
def fizzbuzz(n):
    if n % 15 == 0:
        return "FizzBuzz"
    if n % 3 == 0:
        return "Fizz"
    if n % 5 == 0:
        return "Buzz"
    return str(n)

if __name__ == "__main__":
    name = input("Your name: ")
    print(f"Hello, {name}! Here is FizzBuzz up to 15:")
    print(", ".join(fizzbuzz(i) for i in range(1, 16)))
