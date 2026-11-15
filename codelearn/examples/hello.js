// Run with Node.js: node hello.js
const words = "the quick brown fox jumps over the lazy dog the end".split(" ");

const counts = words.reduce((acc, w) => {
  acc[w] = (acc[w] || 0) + 1;
  return acc;
}, {});

console.log("Word counts:", counts);
console.log("Most common:", Object.entries(counts).sort((a, b) => b[1] - a[1])[0]);
