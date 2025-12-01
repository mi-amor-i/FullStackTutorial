// A. Simple greeting
function greet(name) {
return "Hello " + name;
}

let greets=(name) => (name)
// B. Callback inside an array method
let movies = ["Kantara", "Spirited Away"];
movies.forEach(movie =>console.log("Movie:"+movie));  


console.log(greets("Rubina"));
const movie = {
title: "The Shawshank Redemption",
year: 1994,
// This works fine
getInfo: function() {
console.log(`${this.title} (${this.year})`);
;
},
// This is broken - fix it!
delayedInfo: function() {
setTimeout(()=> {
console.log(`${this.title} was released in ${this.year}`);
}, 1000);
}
};
movie.getInfo();
movie.delayedInfo();


const title = "The Shawshank Redemption";
const year = 1994;
// Expected Output string:
// <div class="card">
// <h2>The Shawshank Redemption</h2>
// <p>Released: 1994</p>
// </div>
console.log(`
	<div class="card">
	<h2>${title}</h2>
	<p>Released: ${year}</p>
	</div>
`)