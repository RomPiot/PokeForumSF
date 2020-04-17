document.addEventListener("DOMContentLoaded", function () {
	let onHunt = false;
	
	const body = document.querySelector('body');
	const userId = body.getAttribute("data-userid");
	
	const cursorPokeball = document.querySelector('#cursor-pokeball');

	const huntBtn = document.querySelector('a.hunt-btn');
	const pokemon = document.querySelector('a.pokemon');
	const pokemonImage = document.querySelector('img.pokemon-image');
	
	let pokemonData;

	document.addEventListener("mousemove", function (event) {
		let x = event.clientX;
		let y = event.clientY;
		cursorPokeball.style.left = x + "px";
		cursorPokeball.style.top = y + "px";
	});

	// Click on button 'Chasser un pokemon'
	huntBtn.addEventListener("click", function (event) {
		event.preventDefault();

		body.style.cursor = "none";
		cursorPokeball.classList.remove('display-none');


		const url = this.href;
		onHunt = true;
		
		axios.post(url, {
			"user_id": userId
		}).then(function(response) {
			// Remove a pokeball
			$('.pokeball-container').children().last().remove();
			
			pokemonData = JSON.parse(response.data.content);
			console.log(pokemonData);

			let idPokemon = pokemonData.idPokemon;

			// change image pokemon
			pokemonImage.setAttribute("src","/images/pokemons/"+pokemonData.idPokemon+".png")

			pokemon.setAttribute("data-id", pokemonData.idPokemon)

			// Display game
			$(".pokemon-main-container").fadeIn("0.5");
				movePokemon('.pokemon', pokemonData.difficulty);
		})
	});

	// On click in game
	document.addEventListener("click", function (event) {
		if (onHunt == true) {
			// If click on pokemon
			if (event.target.classList.contains("pokemon-image")) {
				event.preventDefault();

				const url = pokemon.getAttribute("href");
			
				console.log(pokemon.getAttribute("data-id"));
				
				axios.post(url, {
					"user_id": userId,
					"pokemon_id": pokemon.getAttribute("data-id")
				}).then(function(response) {
						console.log(response);
				})
			} 

			if (!event.target.classList.contains("hunt-btn")) {
				onHunt = false;
				$(".pokemon-main-container").fadeOut("0.5");
				body.style.cursor = "inherit";
				cursorPokeball.classList.add('display-none');
			}
		}
		
	});
	

	function makeNewPosition(){
		// Get viewport dimensions (remove the dimension of the div)
		var h = $(window).height() - 50;
		var w = $(window).width() - 50;
		
		var nh = Math.floor(Math.random() * h);
		var nw = Math.floor(Math.random() * w);
		
		return [nh,nw];    
	}

	function movePokemon(myclass, difficulty){
		var newq = makeNewPosition();
		duration = 10000 / (difficulty*0.5);
		
		$(myclass).animate({ top: newq[0], left: newq[1] }, duration,   function(){
		movePokemon(myclass, difficulty);        
		});
		
	};
	

});